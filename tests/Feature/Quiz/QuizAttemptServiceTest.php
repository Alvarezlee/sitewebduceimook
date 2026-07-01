<?php

use App\Models\Quiz\QuizCandidate;
use App\Models\Quiz\QuizEdition;
use App\Models\Quiz\QuizQuestion;
use App\Models\Quiz\QuizSubject;
use App\Models\User;
use App\Services\Quiz\QuizAttemptService;

function makeQuestionsFor(int $count = 10): void
{
    $subject = QuizSubject::factory()->create();

    QuizQuestion::factory($count)->create(['quiz_subject_id' => $subject->id, 'is_validated' => true]);
}

test('starting an attempt requires a paid registration', function () {
    $user = User::factory()->create();
    $edition = QuizEdition::factory()->create(['questions_per_attempt' => 5]);
    $candidate = QuizCandidate::factory()->create([
        'user_id' => $user->id,
        'quiz_edition_id' => $edition->id,
        'registration_status' => 'pending',
    ]);

    expect(fn () => app(QuizAttemptService::class)->start($candidate))
        ->toThrow(RuntimeException::class);
});

test('starting an attempt draws random validated questions and sets the timer', function () {
    makeQuestionsFor(10);

    $user = User::factory()->create();
    $edition = QuizEdition::factory()->create(['questions_per_attempt' => 5, 'duration_minutes' => 20]);
    $candidate = QuizCandidate::factory()->create([
        'user_id' => $user->id,
        'quiz_edition_id' => $edition->id,
        'registration_status' => 'paid',
    ]);

    $attempt = app(QuizAttemptService::class)->start($candidate);

    expect($attempt->status)->toBe('in_progress')
        ->and(count($attempt->question_order))->toBe(5)
        ->and((int) $attempt->started_at->diffInMinutes($attempt->expires_at))->toBe(20);
});

test('a candidate cannot start a second attempt', function () {
    makeQuestionsFor(10);

    $user = User::factory()->create();
    $edition = QuizEdition::factory()->create(['questions_per_attempt' => 5]);
    $candidate = QuizCandidate::factory()->create([
        'user_id' => $user->id,
        'quiz_edition_id' => $edition->id,
        'registration_status' => 'paid',
    ]);

    app(QuizAttemptService::class)->start($candidate);

    expect(fn () => app(QuizAttemptService::class)->start($candidate))
        ->toThrow(RuntimeException::class);
});

test('submitting an attempt grades qcm answers, computes score, rank and issues a certificate', function () {
    $subject = QuizSubject::factory()->create();
    $questions = QuizQuestion::factory(4)->create(['quiz_subject_id' => $subject->id, 'is_validated' => true]);

    $user = User::factory()->create();
    $edition = QuizEdition::factory()->create(['questions_per_attempt' => 4]);
    $candidate = QuizCandidate::factory()->create([
        'user_id' => $user->id,
        'quiz_edition_id' => $edition->id,
        'registration_status' => 'paid',
    ]);

    $service = app(QuizAttemptService::class);
    $attempt = $service->start($candidate);

    // Answer all questions correctly.
    foreach ($attempt->question_order as $questionId) {
        $question = $questions->firstWhere('id', $questionId);
        $correctOption = $question->options()->where('is_correct', true)->first();
        $service->saveAnswer($attempt, $questionId, $correctOption->id, null);
    }

    $submitted = $service->submit($attempt);

    expect($submitted->status)->toBe('graded')
        ->and((float) $submitted->score)->toBe(100.0)
        ->and($submitted->rank)->toBe(1)
        ->and($submitted->certificate)->not->toBeNull();
});

test('the anti-cheat flag is recorded without blocking the attempt', function () {
    makeQuestionsFor(5);

    $user = User::factory()->create();
    $edition = QuizEdition::factory()->create(['questions_per_attempt' => 5]);
    $candidate = QuizCandidate::factory()->create([
        'user_id' => $user->id,
        'quiz_edition_id' => $edition->id,
        'registration_status' => 'paid',
    ]);

    $service = app(QuizAttemptService::class);
    $attempt = $service->start($candidate);

    $service->flagAntiCheat($attempt, 'tab_hidden');

    expect($attempt->fresh()->status)->toBe('in_progress')
        ->and($attempt->fresh()->anti_cheat_flags)->toHaveCount(1);
});

test('an expired attempt is auto-submitted when trying to save an answer', function () {
    makeQuestionsFor(5);

    $user = User::factory()->create();
    $edition = QuizEdition::factory()->create(['questions_per_attempt' => 5, 'duration_minutes' => 10]);
    $candidate = QuizCandidate::factory()->create([
        'user_id' => $user->id,
        'quiz_edition_id' => $edition->id,
        'registration_status' => 'paid',
    ]);

    $service = app(QuizAttemptService::class);
    $attempt = $service->start($candidate);
    $attempt->update(['expires_at' => now()->subMinute()]);

    expect(fn () => $service->saveAnswer($attempt, $attempt->question_order[0], null, null))
        ->toThrow(RuntimeException::class);

    expect($attempt->fresh()->status)->not->toBe('in_progress');
});
