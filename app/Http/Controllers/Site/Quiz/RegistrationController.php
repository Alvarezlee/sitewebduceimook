<?php

namespace App\Http\Controllers\Site\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\QuizRegistrationRequest;
use App\Models\Quiz\QuizCandidate;
use App\Models\Quiz\QuizEdition;
use App\Services\Payment\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(QuizEdition $edition): View
    {
        abort_unless($edition->isOpenForRegistration(), 404);

        return view('quiz.register', compact('edition'));
    }

    public function store(QuizRegistrationRequest $request, QuizEdition $edition, PaymentService $payments): RedirectResponse
    {
        abort_unless($edition->isOpenForRegistration(), 404);

        $existing = QuizCandidate::query()
            ->where('user_id', Auth::id())
            ->where('quiz_edition_id', $edition->id)
            ->first();

        if ($existing) {
            return redirect()->route('quiz.landing')->with('error', 'Vous êtes déjà inscrit à cette édition.');
        }

        $photoPath = $request->file('photo')?->store('quiz/candidates', 'local');

        $candidate = QuizCandidate::query()->create([
            ...$request->safe()->except(['phone', 'photo']),
            'user_id' => Auth::id(),
            'quiz_edition_id' => $edition->id,
            'photo_path' => $photoPath,
            'registration_status' => 'pending',
        ]);

        $payment = $payments->initiate(
            Auth::user(),
            $candidate,
            (float) $edition->registration_price,
            $request->validated('phone'),
        );

        return redirect()->away($payment->_payment_url);
    }
}
