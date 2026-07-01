<?php

use App\Models\Quiz\QuizSubject;
use App\Services\Ai\Contracts\AIProviderInterface;
use App\Services\Quiz\QuizGenerationService;
use Tests\Fakes\FakeAIProvider;

test('the quiz generation service creates unvalidated ai questions with options', function () {
    app()->instance(AIProviderInterface::class, new FakeAIProvider);

    $subject = QuizSubject::factory()->create(['name' => 'Réseaux']);

    $questions = app(QuizGenerationService::class)->generate($subject, 3, 'medium');

    expect($questions)->toHaveCount(3);

    foreach ($questions as $question) {
        expect($question->source)->toBe('ai')
            ->and($question->is_validated)->toBeFalse()
            ->and($question->options)->toHaveCount(4)
            ->and($question->options->where('is_correct', true))->toHaveCount(1);
    }
});
