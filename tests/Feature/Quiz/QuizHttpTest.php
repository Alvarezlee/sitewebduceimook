<?php

use App\Livewire\Quiz\AttemptRunner;
use App\Models\Quiz\QuizCandidate;
use App\Models\Quiz\QuizEdition;
use App\Models\Quiz\QuizQuestion;
use App\Models\Quiz\QuizSubject;
use App\Models\User;
use App\Services\Quiz\QuizAttemptService;
use Livewire\Livewire;

test('the quiz landing page renders', function () {
    QuizEdition::factory()->create(['is_active' => true, 'starts_at' => now()->subDay(), 'ends_at' => now()->addMonth()]);

    $this->get(route('quiz.landing'))->assertOk();
});

test('a registered user can register for the quiz and is redirected to monetbil', function () {
    $edition = QuizEdition::factory()->create(['starts_at' => now()->subDay(), 'ends_at' => now()->addMonth()]);
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('quiz.register.store', $edition), [
        'institution' => 'Lycée de Nkongsamba',
        'class_level' => 'Tle D',
        'region' => 'Littoral',
        'department' => 'Moungo',
        'arrondissement' => 'Nkongsamba I',
        'parent_name' => 'M. Test',
        'parent_phone' => '650000010',
        'phone' => '650000011',
    ]);

    $response->assertRedirect();
    expect($response->headers->get('Location'))->toContain(config('services.monetbil.service_key'));

    $this->assertDatabaseHas('quiz_candidates', [
        'user_id' => $user->id,
        'quiz_edition_id' => $edition->id,
        'registration_status' => 'pending',
    ]);
});

test('the leaderboard page renders', function () {
    $edition = QuizEdition::factory()->create();

    $this->get(route('quiz.leaderboard', $edition))->assertOk();
});

test('the attempt runner livewire component can answer and navigate questions', function () {
    $subject = QuizSubject::factory()->create();
    QuizQuestion::factory(3)->create(['quiz_subject_id' => $subject->id, 'is_validated' => true]);

    $user = User::factory()->create();
    $edition = QuizEdition::factory()->create(['questions_per_attempt' => 3, 'duration_minutes' => 20]);
    $candidate = QuizCandidate::factory()->create([
        'user_id' => $user->id,
        'quiz_edition_id' => $edition->id,
        'registration_status' => 'paid',
    ]);

    app(QuizAttemptService::class)->start($candidate);

    $this->actingAs($user);

    $component = Livewire::test(AttemptRunner::class, ['edition' => $edition]);

    $firstOption = $component->get('currentQuestion')->options->first();
    $component->call('selectOption', $firstOption->id);
    $component->call('saveAndGo', 1);

    expect($component->get('currentIndex'))->toBe(1);

    $this->assertDatabaseHas('quiz_attempt_answers', [
        'quiz_question_option_id' => $firstOption->id,
    ]);
});
