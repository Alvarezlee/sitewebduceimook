<x-admin-layout :title="$document->exists ? 'Modifier le document' : 'Nouveau document'">
    <form method="POST" action="{{ $document->exists ? route('admin.library.documents.update', $document) : route('admin.library.documents.store') }}" enctype="multipart/form-data" class="glass-card p-6 max-w-xl space-y-4">
        @csrf
        @if ($document->exists) @method('PUT') @endif

        <div>
            <x-input-label for="title" value="Titre" />
            <x-text-input id="title" name="title" class="block mt-1 w-full" :value="old('title', $document->title)" required />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="library_category_id" value="Catégorie" />
                <select id="library_category_id" name="library_category_id" class="mt-1 w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10 text-sm">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('library_category_id', $document->library_category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="type" value="Type" />
                <select id="type" name="type" class="mt-1 w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10 text-sm">
                    @foreach (['probatoire','baccalaureat','bepc','cap','bts','concours','corrige','fascicule','livre'] as $type)
                        <option value="{{ $type }}" @selected(old('type', $document->type) === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <x-input-label for="description" value="Description" />
            <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10">{{ old('description', $document->description) }}</textarea>
        </div>

        <div>
            <x-input-label for="year" value="Année" />
            <x-text-input id="year" name="year" type="number" class="block mt-1 w-full" :value="old('year', $document->year)" />
        </div>

        <div>
            <x-input-label for="file" value="Fichier PDF" />
            <input id="file" name="file" type="file" accept="application/pdf" class="block mt-1 w-full text-sm" @if(!$document->exists) required @endif>
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_free" value="1" @checked(old('is_free', $document->is_free))>
            Gratuit
        </label>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $document->is_published ?? true))>
            Publié
        </label>

        <x-primary-button>Enregistrer</x-primary-button>
    </form>
</x-admin-layout>
