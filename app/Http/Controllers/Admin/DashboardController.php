<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business\Business;
use App\Models\Library\LibrarySubscription;
use App\Models\Payment\Payment;
use App\Models\Quiz\QuizCandidate;
use App\Models\Shop\Order;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'users' => User::query()->count(),
            'active_library_subscriptions' => LibrarySubscription::query()->where('status', 'active')->count(),
            'quiz_candidates' => QuizCandidate::query()->count(),
            'orders' => Order::query()->count(),
            'businesses_pending' => Business::query()->where('is_published', false)->count(),
            'revenue_total' => Payment::query()->successful()->sum('amount'),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
