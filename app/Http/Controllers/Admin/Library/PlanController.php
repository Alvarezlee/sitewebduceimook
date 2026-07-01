<?php

namespace App\Http\Controllers\Admin\Library;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(): View
    {
        $plans = LibraryPlan::query()->orderBy('price')->paginate(20);

        return view('admin.library.plans.index', compact('plans'));
    }

    public function create(): View
    {
        return view('admin.library.plans.form', ['plan' => new LibraryPlan]);
    }

    public function store(Request $request): RedirectResponse
    {
        LibraryPlan::query()->create($this->validated($request));

        return redirect()->route('admin.library.plans.index')->with('success', 'Formule créée.');
    }

    public function edit(LibraryPlan $plan): View
    {
        return view('admin.library.plans.form', compact('plan'));
    }

    public function update(Request $request, LibraryPlan $plan): RedirectResponse
    {
        $plan->update($this->validated($request));

        return redirect()->route('admin.library.plans.index')->with('success', 'Formule mise à jour.');
    }

    public function destroy(LibraryPlan $plan): RedirectResponse
    {
        $plan->delete();

        return redirect()->route('admin.library.plans.index')->with('success', 'Formule supprimée.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'max_downloads' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
