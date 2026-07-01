<?php

namespace App\Livewire\Quiz;

use App\Models\Quiz\QuizAttempt;
use App\Models\Quiz\QuizCandidate;
use App\Models\Quiz\QuizEdition;
use App\Models\Quiz\QuizQuestion;
use App\Services\Quiz\QuizAttemptService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class AttemptRunner extends Component
{
    public int $editionId;

    public int $currentIndex = 0;

    public ?int $selectedOptionId = null;

    public string $openAnswerText = '';

    public function mount(QuizEdition $edition): void
    {
        $this->editionId = $edition->id;
        $this->loadCurrentAnswer();
    }

    public function getAttemptProperty(): QuizAttempt
    {
        return $this->candidate->attempt;
    }

    public function getCandidateProperty(): QuizCandidate
    {
        return QuizCandidate::query()
            ->where('user_id', auth()->id())
            ->where('quiz_edition_id', $this->editionId)
            ->with('attempt')
            ->firstOrFail();
    }

    public function getQuestionIdsProperty(): array
    {
        return $this->attempt->question_order;
    }

    public function getCurrentQuestionProperty(): ?QuizQuestion
    {
        $questionId = $this->questionIds[$this->currentIndex] ?? null;

        return $questionId ? QuizQuestion::query()->with('options')->find($questionId) : null;
    }

    public function getRemainingSecondsProperty(): int
    {
        return max(0, (int) now()->diffInSeconds($this->attempt->expires_at, false));
    }

    public function selectOption(int $optionId): void
    {
        $this->selectedOptionId = $optionId;
    }

    public function saveAndGo(int $direction, QuizAttemptService $attempts): void
    {
        $question = $this->currentQuestion;

        if ($question) {
            $attempts->saveAnswer(
                $this->attempt,
                $question->id,
                $question->is_open_ended ? null : $this->selectedOptionId,
                $question->is_open_ended ? $this->openAnswerText : null,
            );
        }

        $this->currentIndex = max(0, min(count($this->questionIds) - 1, $this->currentIndex + $direction));
        $this->loadCurrentAnswer();
    }

    public function finish(QuizAttemptService $attempts): void
    {
        $question = $this->currentQuestion;

        if ($question) {
            $attempts->saveAnswer(
                $this->attempt,
                $question->id,
                $question->is_open_ended ? null : $this->selectedOptionId,
                $question->is_open_ended ? $this->openAnswerText : null,
            );
        }

        $attempts->submit($this->attempt);

        $this->redirectRoute('quiz.attempt.result', ['edition' => $this->editionId], navigate: false);
    }

    public function checkExpiry(QuizAttemptService $attempts): void
    {
        if ($this->remainingSeconds <= 0) {
            $attempts->submit($this->attempt);
            $this->redirectRoute('quiz.attempt.result', ['edition' => $this->editionId], navigate: false);
        }
    }

    public function reportTabSwitch(QuizAttemptService $attempts): void
    {
        $attempts->flagAntiCheat($this->attempt, 'tab_hidden');
    }

    private function loadCurrentAnswer(): void
    {
        $question = $this->currentQuestion;
        $existing = $question
            ? $this->attempt->answers()->where('quiz_question_id', $question->id)->first()
            : null;

        $this->selectedOptionId = $existing?->quiz_question_option_id;
        $this->openAnswerText = $existing?->open_answer_text ?? '';
    }

    public function render(): View
    {
        return view('livewire.quiz.attempt-runner');
    }
}
