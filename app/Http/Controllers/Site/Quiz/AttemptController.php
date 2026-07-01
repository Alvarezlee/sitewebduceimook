<?php

namespace App\Http\Controllers\Site\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\QuizCandidate;
use App\Models\Quiz\QuizEdition;
use App\Services\Quiz\QuizAttemptService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use RuntimeException;

class AttemptController extends Controller
{
    public function start(QuizEdition $edition, QuizAttemptService $attempts): RedirectResponse
    {
        $candidate = QuizCandidate::query()
            ->where('user_id', Auth::id())
            ->where('quiz_edition_id', $edition->id)
            ->firstOrFail();

        try {
            $attempts->start($candidate);
        } catch (RuntimeException $e) {
            return redirect()->route('quiz.attempt.show', $edition)->with('error', $e->getMessage());
        }

        return redirect()->route('quiz.attempt.show', $edition);
    }

    public function show(QuizEdition $edition): View
    {
        $candidate = QuizCandidate::query()
            ->with('attempt')
            ->where('user_id', Auth::id())
            ->where('quiz_edition_id', $edition->id)
            ->firstOrFail();

        abort_unless($candidate->isPaid(), 403);

        return view('quiz.attempt', compact('edition', 'candidate'));
    }

    public function result(QuizEdition $edition): View
    {
        $candidate = QuizCandidate::query()
            ->with(['attempt.certificate'])
            ->where('user_id', Auth::id())
            ->where('quiz_edition_id', $edition->id)
            ->firstOrFail();

        abort_if(is_null($candidate->attempt), 404);

        return view('quiz.result', compact('edition', 'candidate'));
    }
}
