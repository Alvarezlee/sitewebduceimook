<?php

namespace App\Http\Controllers\Site\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\QuizCandidate;
use App\Models\Quiz\QuizEdition;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function index(): View
    {
        $edition = QuizEdition::query()->where('is_active', true)->latest('starts_at')->first();

        $candidate = null;
        if ($edition && Auth::check()) {
            $candidate = QuizCandidate::query()
                ->with('attempt')
                ->where('user_id', Auth::id())
                ->where('quiz_edition_id', $edition->id)
                ->first();
        }

        return view('quiz.landing', compact('edition', 'candidate'));
    }
}
