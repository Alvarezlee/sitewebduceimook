<x-admin-layout :title="$category->exists ? 'Modifier la catégorie' : 'Nouvelle catégorie'">
    <form method="POST" action="{{ $category->exists ? route('admin.library.categories.update', $category) : route('admin.library.categories.store') }}" class="glass-card p-6 max-w-xl space-y-4">
        @csrf
        @if ($category->exists) @method('PUT') @endif

        <div>
            <x-input-label for="name" value="Nom" />
            <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $category->name)" required />
        </div>
        <div>
            <x-input-label for="description" value="Description" />
            <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10">{{ old('description', $category->description) }}</textarea>
        </div>
        <div>
            <x-input-label for="icon" value="Icône (nom)" />
            <x-text-input id="icon" name="icon" class="block mt-1 w-full" :value="old('icon', $category->icon)" />
        </div>

        <x-primary-button>Enregistrer</x-primary-button>
    </form>
</x-admin-layout>
