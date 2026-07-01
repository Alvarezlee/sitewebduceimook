<?php

namespace App\Http\Controllers\Site\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\Business\BusinessContactRequest;
use App\Models\Business\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BusinessDirectoryController extends Controller
{
    public function index(): View
    {
        $businesses = Business::query()->published()->latest()->paginate(12);

        return view('business.index', compact('businesses'));
    }

    public function show(Business $business): View
    {
        abort_unless($business->is_published, 404);

        $business->load(['services', 'media']);

        return view('business.show', compact('business'));
    }

    public function contact(BusinessContactRequest $request, Business $business): RedirectResponse
    {
        abort_unless($business->is_published, 404);

        $business->contactMessages()->create($request->validated());

        return back()->with('success', 'Votre message a été envoyé à l\'entreprise.');
    }
}
