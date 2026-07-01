<x-admin-layout :title="$banner->exists ? 'Modifier la bannière' : 'Nouvelle bannière'">
    <form method="POST" action="{{ $banner->exists ? route('admin.content.banners.update', $banner) : route('admin.content.banners.store') }}" enctype="multipart/form-data" class="glass-card p-6 max-w-xl space-y-4">
        @csrf
        @if ($banner->exists) @method('PUT') @endif

        <div>
            <x-input-label for="title" value="Titre" />
            <x-text-input id="title" name="title" class="block mt-1 w-full" :value="old('title', $banner->title)" />
        </div>
        <div>
            <x-input-label for="link_url" value="Lien" />
            <x-text-input id="link_url" name="link_url" class="block mt-1 w-full" :value="old('link_url', $banner->link_url)" />
        </div>
        <div>
            <x-input-label for="position" value="Position" />
            <select id="position" name="position" class="mt-1 w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10 text-sm">
                @foreach (['home_hero','home_carousel','shop'] as $position)
                    <option value="{{ $position }}" @selected(old('position', $banner->position) === $position)>{{ $position }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <x-input-label for="image" value="Image" />
            <input id="image" name="image" type="file" accept="image/*" class="block mt-1 w-full text-sm" @if(!$banner->exists) required @endif>
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->is_active ?? true))>
            Active
        </label>

        <x-primary-button>Enregistrer</x-primary-button>
    </form>
</x-admin-layout>
