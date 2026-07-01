<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\QuizEdition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EditionController extends Controller
{
    public function index(): View
    {
        $editions = QuizEdition::query()->withCount('candidates')->latest('starts_at')->paginate(20);

        return view('admin.quiz.editions.index', compact('editions'));
    }

    public function create(): View
    {
        return view('admin.quiz.editions.form', ['edition' => new QuizEdition]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = Str::slug($validated['name']).'-'.Str::random(4);

        QuizEdition::query()->create($validated);

        return redirect()->route('admin.quiz.editions.index')->with('success', 'Édition créée.');
    }

    public function edit(QuizEdition $edition): View
    {
        return view('admin.quiz.editions.form', compact('edition'));
    }

    public function update(Request $request, QuizEdition $edition): RedirectResponse
    {
        $edition->update($this->validated($request));

        return redirect()->route('admin.quiz.editions.index')->with('success', 'Édition mise à jour.');
    }

    public function destroy(QuizEdition $edition): RedirectResponse
    {
        $edition->delete();

        return redirect()->route('admin.quiz.editions.index')->with('success', 'Édition supprimée.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'registration_price' => ['required', 'numeric', 'min:0'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'max_attempts' => ['required', 'integer', 'min:1'],
            'questions_per_attempt' => ['required', 'integer', 'min:1'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
