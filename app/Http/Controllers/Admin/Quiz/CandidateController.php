<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\QuizAttemptAnswer;
use App\Models\Quiz\QuizCandidate;
use App\Services\Quiz\QuizAttemptService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CandidateController extends Controller
{
    public function index(Request $request): View
    {
        $candidates = QuizCandidate::query()
            ->with(['user', 'edition', 'attempt'])
            ->when($request->integer('edition'), fn ($q, $editionId) => $q->where('quiz_edition_id', $editionId))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.quiz.candidates.index', compact('candidates'));
    }

    public function show(QuizCandidate $candidate): View
    {
        $candidate->load(['user', 'edition', 'attempt.answers.question.options']);

        return view('admin.quiz.candidates.show', compact('candidate'));
    }

    public function gradeAnswer(Request $request, QuizAttemptAnswer $answer, QuizAttemptService $attempts): RedirectResponse
    {
        $validated = $request->validate([
            'points_awarded' => ['required', 'numeric', 'min:0', 'max:1'],
        ]);

        $answer->update([
            'points_awarded' => $validated['points_awarded'],
            'is_correct' => $validated['points_awarded'] > 0,
            'graded_by' => Auth::id(),
        ]);

        $attempt = $answer->attempt;
        $stillPending = $attempt->answers()
            ->whereHas('question', fn ($q) => $q->where('is_open_ended', true))
            ->whereNull('points_awarded')
            ->exists();

        if (! $stillPending && $attempt->status === 'submitted') {
            $totalQuestions = count($attempt->question_order);
            $earned = (float) $attempt->answers()->sum('points_awarded');
            $attempt->update([
                'status' => 'graded',
                'score' => $totalQuestions > 0 ? round(($earned / $totalQuestions) * 100, 2) : 0,
            ]);
            $attempts->recalculateRanks($attempt->candidate->quiz_edition_id);
        }

        return back()->with('success', 'Réponse notée.');
    }
}
