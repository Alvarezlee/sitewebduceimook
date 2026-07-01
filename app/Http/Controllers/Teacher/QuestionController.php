<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\GenerateAiQuestionsRequest;
use App\Http\Requests\Teacher\StoreQuestionRequest;
use App\Models\Quiz\QuizQuestion;
use App\Models\Quiz\QuizSubject;
use App\Services\Quiz\QuizGenerationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function index(): View
    {
        $subjects = QuizSubject::query()->orderBy('name')->get();

        $questions = QuizQuestion::query()
            ->with(['subject', 'options'])
            ->latest()
            ->paginate(15);

        return view('teacher.questions.index', compact('subjects', 'questions'));
    }

    public function store(StoreQuestionRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $question = QuizQuestion::query()->create([
            'quiz_subject_id' => $validated['quiz_subject_id'],
            'created_by' => Auth::id(),
            'source' => 'teacher',
            'question' => $validated['question'],
            'explanation' => $validated['explanation'] ?? null,
            'difficulty' => $validated['difficulty'],
            'is_open_ended' => false,
            'is_validated' => true,
        ]);

        foreach ($validated['options'] as $index => $option) {
            $question->options()->create([
                'label' => $option['label'],
                'is_correct' => $index === (int) $validated['correct_option'],
                'order' => $index,
            ]);
        }

        return redirect()->route('teacher.questions.index')->with('success', 'Question créée.');
    }

    public function generate(GenerateAiQuestionsRequest $request, QuizGenerationService $generator): RedirectResponse
    {
        $subject = QuizSubject::query()->findOrFail($request->validated('quiz_subject_id'));

        $generator->generate(
            $subject,
            (int) $request->validated('count'),
            $request->validated('difficulty'),
            Auth::user(),
        );

        return redirect()->route('teacher.questions.index')
            ->with('success', 'Questions générées par IA, en attente de validation.');
    }

    public function validateQuestion(QuizQuestion $question): RedirectResponse
    {
        $question->update(['is_validated' => true]);

        return redirect()->route('teacher.questions.index')->with('success', 'Question validée.');
    }

    public function destroy(QuizQuestion $question): RedirectResponse
    {
        $question->delete();

        return redirect()->route('teacher.questions.index')->with('success', 'Question supprimée.');
    }
}
