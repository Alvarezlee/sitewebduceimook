<?php

namespace App\Http\Controllers\Site\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\QuizAttempt;
use App\Models\Quiz\QuizEdition;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    public function __invoke(QuizEdition $edition): View
    {
        $ranking = QuizAttempt::query()
            ->whereHas('candidate', fn ($q) => $q->where('quiz_edition_id', $edition->id))
            ->where('status', 'graded')
            ->with('candidate.user')
            ->orderBy('rank')
            ->paginate(50);

        return view('quiz.leaderboard', compact('edition', 'ranking'));
    }
}
