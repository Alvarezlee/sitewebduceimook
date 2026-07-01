<?php

namespace App\Http\Controllers\Site\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\SubscribeRequest;
use App\Models\Library\LibraryPlan;
use App\Models\Library\LibrarySubscription;
use App\Services\Payment\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function create(): View
    {
        $plans = LibraryPlan::query()->where('is_active', true)->orderBy('price')->get();

        return view('library.subscribe', compact('plans'));
    }

    public function store(SubscribeRequest $request, LibraryPlan $plan, PaymentService $payments): RedirectResponse
    {
        $subscription = LibrarySubscription::query()->create([
            'user_id' => Auth::id(),
            'library_plan_id' => $plan->id,
            'starts_at' => now(),
            'ends_at' => now()->addDays($plan->duration_days),
            'status' => 'pending',
        ]);

        $payment = $payments->initiate(
            Auth::user(),
            $subscription,
            (float) $plan->price,
            $request->validated('phone'),
        );

        return redirect()->away($payment->_payment_url);
    }
}
