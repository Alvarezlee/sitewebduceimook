<x-admin-layout :title="$page->exists ? 'Modifier la page' : 'Nouvelle page'">
    <form method="POST" action="{{ $page->exists ? route('admin.content.pages.update', $page) : route('admin.content.pages.store') }}" class="glass-card p-6 max-w-2xl space-y-4">
        @csrf
        @if ($page->exists) @method('PUT') @endif

        <div>
            <x-input-label for="title" value="Titre" />
            <x-text-input id="title" name="title" class="block mt-1 w-full" :value="old('title', $page->title)" required />
        </div>
        <div>
            <x-input-label for="content" value="Contenu" />
            <textarea id="content" name="content" rows="10" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10">{{ old('content', $page->content) }}</textarea>
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $page->is_published ?? true))>
            Publiée
        </label>

        <x-primary-button>Enregistrer</x-primary-button>
    </form>
</x-admin-layout>
