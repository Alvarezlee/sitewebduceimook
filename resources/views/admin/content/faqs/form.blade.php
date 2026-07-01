<x-admin-layout :title="$faq->exists ? 'Modifier la question' : 'Nouvelle question'">
    <form method="POST" action="{{ $faq->exists ? route('admin.content.faqs.update', $faq) : route('admin.content.faqs.store') }}" class="glass-card p-6 max-w-2xl space-y-4">
        @csrf
        @if ($faq->exists) @method('PUT') @endif

        <div>
            <x-input-label for="question" value="Question" />
            <x-text-input id="question" name="question" class="block mt-1 w-full" :value="old('question', $faq->question)" required />
        </div>
        <div>
            <x-input-label for="answer" value="Réponse" />
            <textarea id="answer" name="answer" rows="4" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10">{{ old('answer', $faq->answer) }}</textarea>
        </div>
        <div>
            <x-input-label for="category" value="Catégorie" />
            <x-text-input id="category" name="category" class="block mt-1 w-full" :value="old('category', $faq->category)" />
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $faq->is_published ?? true))>
            Publiée
        </label>

        <x-primary-button>Enregistrer</x-primary-button>
    </form>
</x-admin-layout>
