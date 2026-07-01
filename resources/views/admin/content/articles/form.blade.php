<x-admin-layout :title="$article->exists ? 'Modifier l\'actualité' : 'Nouvelle actualité'">
    <form method="POST" action="{{ $article->exists ? route('admin.content.articles.update', $article) : route('admin.content.articles.store') }}" class="glass-card p-6 max-w-2xl space-y-4">
        @csrf
        @if ($article->exists) @method('PUT') @endif

        <div>
            <x-input-label for="category" value="Catégorie" />
            <x-text-input id="category" name="category" class="block mt-1 w-full" :value="old('category', $article->category)" />
        </div>
        <div>
            <x-input-label for="title" value="Titre" />
            <x-text-input id="title" name="title" class="block mt-1 w-full" :value="old('title', $article->title)" required />
        </div>
        <div>
            <x-input-label for="excerpt" value="Résumé" />
            <textarea id="excerpt" name="excerpt" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10">{{ old('excerpt', $article->excerpt) }}</textarea>
        </div>
        <div>
            <x-input-label for="content" value="Contenu" />
            <textarea id="content" name="content" rows="10" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10">{{ old('content', $article->content) }}</textarea>
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $article->is_published)) >
            Publiée
        </label>

        <x-primary-button>Enregistrer</x-primary-button>
    </form>
</x-admin-layout>
