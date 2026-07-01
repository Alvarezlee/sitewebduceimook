<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\QuizSubject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        $subjects = QuizSubject::query()->withCount('questions')->orderBy('name')->paginate(20);

        return view('admin.quiz.subjects.index', compact('subjects'));
    }

    public function create(): View
    {
        return view('admin.quiz.subjects.form', ['subject' => new QuizSubject]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = Str::slug($validated['name']);

        QuizSubject::query()->create($validated);

        return redirect()->route('admin.quiz.subjects.index')->with('success', 'Sujet créé.');
    }

    public function edit(QuizSubject $subject): View
    {
        return view('admin.quiz.subjects.form', compact('subject'));
    }

    public function update(Request $request, QuizSubject $subject): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = Str::slug($validated['name']);

        $subject->update($validated);

        return redirect()->route('admin.quiz.subjects.index')->with('success', 'Sujet mis à jour.');
    }

    public function destroy(QuizSubject $subject): RedirectResponse
    {
        $subject->delete();

        return redirect()->route('admin.quiz.subjects.index')->with('success', 'Sujet supprimé.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);
    }
}
