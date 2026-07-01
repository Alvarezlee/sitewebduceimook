<x-admin-layout :title="$category->exists ? 'Modifier la catégorie' : 'Nouvelle catégorie'">
    <form method="POST" action="{{ $category->exists ? route('admin.shop.categories.update', $category) : route('admin.shop.categories.store') }}" class="glass-card p-6 max-w-xl space-y-4">
        @csrf
        @if ($category->exists) @method('PUT') @endif

        <div>
            <x-input-label for="name" value="Nom" />
            <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $category->name)" required />
        </div>

        <x-primary-button>Enregistrer</x-primary-button>
    </form>
</x-admin-layout>
