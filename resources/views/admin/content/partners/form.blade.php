<x-admin-layout :title="$partner->exists ? 'Modifier le partenaire' : 'Nouveau partenaire'">
    <form method="POST" action="{{ $partner->exists ? route('admin.content.partners.update', $partner) : route('admin.content.partners.store') }}" enctype="multipart/form-data" class="glass-card p-6 max-w-xl space-y-4">
        @csrf
        @if ($partner->exists) @method('PUT') @endif

        <div>
            <x-input-label for="name" value="Nom" />
            <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $partner->name)" required />
        </div>
        <div>
            <x-input-label for="website_url" value="Site web" />
            <x-text-input id="website_url" name="website_url" class="block mt-1 w-full" :value="old('website_url', $partner->website_url)" />
        </div>
        <div>
            <x-input-label for="logo" value="Logo" />
            <input id="logo" name="logo" type="file" accept="image/*" class="block mt-1 w-full text-sm" @if(!$partner->exists) required @endif>
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $partner->is_published ?? true))>
            Publié
        </label>

        <x-primary-button>Enregistrer</x-primary-button>
    </form>
</x-admin-layout>
