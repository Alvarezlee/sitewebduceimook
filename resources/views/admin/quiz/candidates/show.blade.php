<x-admin-layout title="Détails candidat">
    <div class="glass-card p-6 mb-6">
        <p class="font-bold text-lg">{{ $candidate->user->full_name }}</p>
        <p class="text-sm text-gray-500">{{ $candidate->institution }} — {{ $candidate->class_level }}</p>
        <p class="text-sm text-gray-500">Édition : {{ $candidate->edition->name }}</p>
        <p class="text-sm text-gray-500">Statut : {{ $candidate->registration_status }}</p>
    </div>

    @if ($candidate->attempt)
        <div class="glass-card p-6">
            <p class="font-semibold mb-4">Tentative — Score : {{ $candidate->attempt->score ?? 'en cours' }}</p>

            @foreach ($candidate->attempt->answers as $answer)
                <div class="py-3 border-b border-gray-100 dark:border-white/5 last:border-0">
                    <p class="font-medium">{{ $answer->question->question }}</p>
                    @if ($answer->question->is_open_ended)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $answer->open_answer_text }}</p>
                        @if (is_null($answer->points_awarded))
                            <form method="POST" action="{{ route('admin.quiz.answers.grade', $answer) }}" class="mt-2 flex items-center gap-2">
                                @csrf
                                <input type="number" step="0.01" min="0" max="1" name="points_awarded" class="w-20 rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10 text-sm" required>
                                <button class="btn-secondary text-xs">Noter</button>
                            </form>
                        @else
                            <p class="text-xs text-emerald-600 mt-1">Noté : {{ $answer->points_awarded }}</p>
                        @endif
                    @else
                        <p class="text-sm {{ $answer->is_correct ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $answer->selectedOption?->label }} — {{ $answer->is_correct ? 'Correct' : 'Incorrect' }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">Le candidat n'a pas encore démarré sa tentative.</p>
    @endif
</x-admin-layout>
