<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Banque de questions Quiz</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="font-semibold mb-4">Générer des questions par IA</h3>
                <form method="POST" action="{{ route('teacher.questions.generate') }}" class="flex flex-wrap gap-3 items-end">
                    @csrf
                    <div>
                        <x-input-label for="quiz_subject_id" value="Sujet" />
                        <select id="quiz_subject_id" name="quiz_subject_id" class="mt-1 rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 text-sm">
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="count" value="Nombre" />
                        <input type="number" id="count" name="count" min="1" max="20" value="5" class="mt-1 w-24 rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 text-sm">
                    </div>
                    <div>
                        <x-input-label for="difficulty" value="Difficulté" />
                        <select id="difficulty" name="difficulty" class="mt-1 rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 text-sm">
                            <option value="easy">Facile</option>
                            <option value="medium" selected>Moyen</option>
                            <option value="hard">Difficile</option>
                        </select>
                    </div>
                    <x-primary-button>Générer</x-primary-button>
                </form>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="font-semibold mb-4">Créer une question manuellement</h3>
                <form method="POST" action="{{ route('teacher.questions.store') }}" class="space-y-3">
                    @csrf
                    <select name="quiz_subject_id" class="w-full rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 text-sm">
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                    <input name="question" placeholder="Intitulé de la question" required class="w-full rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 text-sm">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="flex items-center gap-2">
                            <input type="radio" name="correct_option" value="{{ $i }}" @checked($i === 0) required>
                            <input name="options[{{ $i }}][label]" placeholder="Option {{ $i + 1 }}" required class="flex-1 rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 text-sm">
                        </div>
                    @endfor
                    <select name="difficulty" class="rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 text-sm">
                        <option value="easy">Facile</option>
                        <option value="medium" selected>Moyen</option>
                        <option value="hard">Difficile</option>
                    </select>
                    <x-primary-button>Créer la question</x-primary-button>
                </form>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="font-semibold mb-4">Questions ({{ $questions->total() }})</h3>
                @foreach ($questions as $question)
                    <div class="py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">{{ $question->subject->name }} · {{ $question->source }} · {{ $question->difficulty }}</p>
                                <p class="font-medium">{{ $question->question }}</p>
                                @unless ($question->is_validated)
                                    <span class="text-xs font-semibold text-amber-600">En attente de validation</span>
                                @endunless
                            </div>
                            <div class="flex gap-2 shrink-0">
                                @unless ($question->is_validated)
                                    <form method="POST" action="{{ route('teacher.questions.validate', $question) }}">
                                        @csrf
                                        <button class="text-emerald-600 text-sm hover:underline">Valider</button>
                                    </form>
                                @endunless
                                <form method="POST" action="{{ route('teacher.questions.destroy', $question) }}">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 text-sm hover:underline">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="mt-4">{{ $questions->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
