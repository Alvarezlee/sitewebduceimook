<?php

use App\Models\Quiz\QuizQuestion;
use App\Models\Quiz\QuizSubject;
use App\Models\User;
use App\Services\Ai\Contracts\AIProviderInterface;
use Tests\Fakes\FakeAIProvider;

test('a non-teacher cannot access the question bank', function () {
    $user = User::factory()->create();
    $user->assignRole('eleve');

    $this->actingAs($user)->get(route('teacher.questions.index'))->assertForbidden();
});

test('a teacher can generate ai questions pending validation and then validate them', function () {
    app()->instance(AIProviderInterface::class, new FakeAIProvider);

    $teacher = User::factory()->create();
    $teacher->assignRole('enseignant');
    $subject = QuizSubject::factory()->create();

    $this->actingAs($teacher)->post(route('teacher.questions.generate'), [
        'quiz_subject_id' => $subject->id,
        'count' => 2,
        'difficulty' => 'medium',
    ])->assertRedirect(route('teacher.questions.index'));

    $this->assertDatabaseHas('quiz_questions', [
        'quiz_subject_id' => $subject->id,
        'source' => 'ai',
        'is_validated' => false,
    ]);

    $question = QuizQuestion::query()->where('source', 'ai')->firstOrFail();

    $this->actingAs($teacher)
        ->post(route('teacher.questions.validate', $question))
        ->assertRedirect(route('teacher.questions.index'));

    expect($question->fresh()->is_validated)->toBeTrue();
});

test('a teacher can manually create a question with options', function () {
    $teacher = User::factory()->create();
    $teacher->assignRole('enseignant');
    $subject = QuizSubject::factory()->create();

    $this->actingAs($teacher)->post(route('teacher.questions.store'), [
        'quiz_subject_id' => $subject->id,
        'question' => 'Quelle est la capitale du Cameroun ?',
        'difficulty' => 'easy',
        'options' => [
            ['label' => 'Yaoundé'],
            ['label' => 'Douala'],
            ['label' => 'Bafoussam'],
            ['label' => 'Garoua'],
        ],
        'correct_option' => 0,
    ])->assertRedirect(route('teacher.questions.index'));

    $this->assertDatabaseHas('quiz_questions', [
        'question' => 'Quelle est la capitale du Cameroun ?',
        'source' => 'teacher',
        'is_validated' => true,
    ]);
});
