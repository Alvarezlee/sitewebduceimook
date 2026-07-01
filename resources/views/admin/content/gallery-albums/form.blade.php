<x-admin-layout :title="$album->exists ? 'Gérer l\'album' : 'Nouvel album'">
    <form method="POST" action="{{ $album->exists ? route('admin.content.gallery-albums.update', $album) : route('admin.content.gallery-albums.store') }}" enctype="multipart/form-data" class="glass-card p-6 max-w-2xl space-y-4">
        @csrf
        @if ($album->exists) @method('PUT') @endif

        <div>
            <x-input-label for="title" value="Titre" />
            <x-text-input id="title" name="title" class="block mt-1 w-full" :value="old('title', $album->title)" required />
        </div>
        <div>
            <x-input-label for="type" value="Type" />
            <select id="type" name="type" class="mt-1 w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10 text-sm">
                <option value="photo" @selected(old('type', $album->type) === 'photo')>Photo</option>
                <option value="video" @selected(old('type', $album->type) === 'video')>Vidéo</option>
            </select>
        </div>

        @if ($album->exists)
            <div>
                <x-input-label for="items" value="Ajouter des fichiers" />
                <input id="items" name="items[]" type="file" multiple class="block mt-1 w-full text-sm">
            </div>
        @endif

        <x-primary-button>Enregistrer</x-primary-button>
    </form>

    @if ($album->exists && $album->items->isNotEmpty())
        <div class="mt-8 grid grid-cols-4 sm:grid-cols-6 gap-3 max-w-2xl">
            @foreach ($album->items as $item)
                <div class="aspect-square rounded-lg bg-gray-100 dark:bg-white/5"></div>
            @endforeach
        </div>
    @endif
</x-admin-layout>
