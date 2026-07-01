<?php

namespace Tests\Fakes;

use App\Services\Ai\Contracts\AIProviderInterface;

class FakeAIProvider implements AIProviderInterface
{
    public array $lastMessages = [];

    public function chat(array $messages): string
    {
        $this->lastMessages = $messages;

        return "Bonjour, je suis l'assistant CEIMO, comment puis-je vous aider ?";
    }

    public function generateQuizQuestions(string $subject, int $count, string $difficulty): array
    {
        $questions = [];

        for ($i = 0; $i < $count; $i++) {
            $questions[] = [
                'question' => "Question {$i} sur {$subject} ({$difficulty})",
                'explanation' => 'Explication.',
                'options' => [
                    ['label' => 'Bonne réponse', 'is_correct' => true],
                    ['label' => 'Mauvaise réponse 1', 'is_correct' => false],
                    ['label' => 'Mauvaise réponse 2', 'is_correct' => false],
                    ['label' => 'Mauvaise réponse 3', 'is_correct' => false],
                ],
            ];
        }

        return $questions;
    }
}
