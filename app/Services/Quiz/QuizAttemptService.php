<?php

namespace App\Services\Quiz;

use App\Models\Quiz\QuizAttempt;
use App\Models\Quiz\QuizAttemptAnswer;
use App\Models\Quiz\QuizCandidate;
use App\Models\Quiz\QuizQuestion;
use RuntimeException;

class QuizAttemptService
{
    public function __construct(private readonly CertificateService $certificates) {}

    /**
     * Démarre la tentative unique d'un candidat : tire aléatoirement les
     * questions parmi la banque validée et fixe le chronomètre serveur.
     */
    public function start(QuizCandidate $candidate): QuizAttempt
    {
        if ($candidate->registration_status !== 'paid') {
            throw new RuntimeException("L'inscription doit être payée avant de démarrer le quiz.");
        }

        if ($candidate->attempt()->exists()) {
            throw new RuntimeException('Une tentative existe déjà pour ce candidat.');
        }

        $edition = $candidate->edition;

        $questionIds = QuizQuestion::query()
            ->validated()
            ->inRandomOrder()
            ->take($edition->questions_per_attempt)
            ->pluck('id')
            ->all();

        if (count($questionIds) === 0) {
            throw new RuntimeException('Aucune question disponible pour ce concours.');
        }

        $startedAt = now();

        return QuizAttempt::query()->create([
            'quiz_candidate_id' => $candidate->id,
            'started_at' => $startedAt,
            'expires_at' => $startedAt->copy()->addMinutes($edition->duration_minutes),
            'status' => 'in_progress',
            'question_order' => $questionIds,
        ]);
    }

    /**
     * Enregistre (ou met à jour) la réponse à une question — sauvegarde
     * automatique. Revérifie systématiquement le chronomètre côté serveur.
     */
    public function saveAnswer(QuizAttempt $attempt, int $questionId, ?int $optionId, ?string $openText): QuizAttemptAnswer
    {
        $this->guardNotExpired($attempt);

        if (! in_array($questionId, $attempt->question_order, true)) {
            throw new RuntimeException('Cette question ne fait pas partie de la tentative en cours.');
        }

        return QuizAttemptAnswer::query()->updateOrCreate(
            ['quiz_attempt_id' => $attempt->id, 'quiz_question_id' => $questionId],
            [
                'quiz_question_option_id' => $optionId,
                'open_answer_text' => $openText,
                'answered_at' => now(),
            ],
        );
    }

    /**
     * Enregistre un signalement anti-triche non bloquant (ex: changement
     * d'onglet), pour revue humaine ultérieure — ne bloque pas la tentative
     * afin d'éviter les faux positifs.
     */
    public function flagAntiCheat(QuizAttempt $attempt, string $reason): void
    {
        $flags = $attempt->anti_cheat_flags ?? [];
        $flags[] = ['reason' => $reason, 'at' => now()->toIso8601String()];

        $attempt->update(['anti_cheat_flags' => $flags]);
    }

    /**
     * Soumet la tentative (manuellement ou automatiquement à expiration),
     * corrige les QCM, calcule le score et le classement.
     */
    public function submit(QuizAttempt $attempt): QuizAttempt
    {
        if ($attempt->status !== 'in_progress') {
            return $attempt;
        }

        $answers = $attempt->answers()->with('question.options')->get();

        $gradableCount = 0;
        $earnedPoints = 0.0;

        foreach ($answers as $answer) {
            if ($answer->question->is_open_ended) {
                continue;
            }

            $gradableCount++;
            $correctOption = $answer->question->options->firstWhere('is_correct', true);
            $isCorrect = $correctOption && $answer->quiz_question_option_id === $correctOption->id;

            $answer->update([
                'is_correct' => $isCorrect,
                'points_awarded' => $isCorrect ? 1 : 0,
            ]);

            if ($isCorrect) {
                $earnedPoints++;
            }
        }

        $totalQuestions = count($attempt->question_order);
        $score = $totalQuestions > 0 ? round(($earnedPoints / $totalQuestions) * 100, 2) : 0;

        $hasOpenEnded = $answers->contains(fn ($a) => $a->question->is_open_ended);

        $attempt->update([
            'finished_at' => now(),
            'status' => $hasOpenEnded ? 'submitted' : 'graded',
            'score' => $score,
        ]);

        $this->recalculateRanks($attempt->candidate->quiz_edition_id);

        $attempt = $attempt->fresh();

        if ($attempt->status === 'graded') {
            $this->certificates->issue($attempt);
        }

        return $attempt;
    }

    /**
     * Recalcule le classement de toutes les tentatives corrigées d'une
     * édition (score décroissant, puis rapidité).
     */
    public function recalculateRanks(int $quizEditionId): void
    {
        $attempts = QuizAttempt::query()
            ->whereIn('quiz_candidate_id', function ($query) use ($quizEditionId) {
                $query->select('id')->from('quiz_candidates')->where('quiz_edition_id', $quizEditionId);
            })
            ->where('status', 'graded')
            ->orderByDesc('score')
            ->orderBy('finished_at')
            ->get();

        $rank = 1;
        foreach ($attempts as $attempt) {
            $attempt->update(['rank' => $rank]);
            $rank++;
        }
    }

    private function guardNotExpired(QuizAttempt $attempt): void
    {
        if ($attempt->status !== 'in_progress') {
            throw new RuntimeException('Cette tentative est déjà terminée.');
        }

        if ($attempt->isExpired()) {
            $this->submit($attempt);

            throw new RuntimeException('Le temps imparti est écoulé, la tentative a été soumise.');
        }
    }
}
