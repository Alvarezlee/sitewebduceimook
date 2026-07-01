<?php

namespace App\Services\Quiz;

use App\Models\Quiz\QuizQuestion;
use App\Models\Quiz\QuizSubject;
use App\Models\User;
use App\Services\Ai\Contracts\AIProviderInterface;
use Illuminate\Support\Collection;

class QuizGenerationService
{
    public function __construct(private readonly AIProviderInterface $provider) {}

    /**
     * Génère des questions via l'IA pour un sujet donné et les enregistre
     * en base comme non validées (source="ai"), en attente de relecture
     * par un enseignant ou un administrateur avant d'intégrer la banque
     * de questions utilisée dans les tentatives de quiz.
     *
     * @return Collection<int, QuizQuestion>
     */
    public function generate(QuizSubject $subject, int $count, string $difficulty, ?User $author = null): Collection
    {
        $raw = $this->provider->generateQuizQuestions($subject->name, $count, $difficulty);

        return collect($raw)->map(function (array $item) use ($subject, $difficulty, $author) {
            $question = QuizQuestion::query()->create([
                'quiz_subject_id' => $subject->id,
                'created_by' => $author?->id,
                'source' => 'ai',
                'question' => $item['question'] ?? '',
                'explanation' => $item['explanation'] ?? null,
                'difficulty' => $difficulty,
                'is_open_ended' => false,
                'is_validated' => false,
            ]);

            foreach (($item['options'] ?? []) as $index => $option) {
                $question->options()->create([
                    'label' => $option['label'] ?? '',
                    'is_correct' => (bool) ($option['is_correct'] ?? false),
                    'order' => $index,
                ]);
            }

            return $question;
        });
    }
}
