<x-admin-layout :title="$event->exists ? 'Modifier l\'événement' : 'Nouvel événement'">
    <form method="POST" action="{{ $event->exists ? route('admin.content.events.update', $event) : route('admin.content.events.store') }}" class="glass-card p-6 max-w-2xl space-y-4">
        @csrf
        @if ($event->exists) @method('PUT') @endif

        <div>
            <x-input-label for="title" value="Titre" />
            <x-text-input id="title" name="title" class="block mt-1 w-full" :value="old('title', $event->title)" required />
        </div>
        <div>
            <x-input-label for="location" value="Lieu" />
            <x-text-input id="location" name="location" class="block mt-1 w-full" :value="old('location', $event->location)" />
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="starts_at" value="Début" />
                <x-text-input id="starts_at" name="starts_at" type="datetime-local" class="block mt-1 w-full" :value="old('starts_at', $event->starts_at?->format('Y-m-d\TH:i'))" required />
            </div>
            <div>
                <x-input-label for="ends_at" value="Fin" />
                <x-text-input id="ends_at" name="ends_at" type="datetime-local" class="block mt-1 w-full" :value="old('ends_at', $event->ends_at?->format('Y-m-d\TH:i'))" />
            </div>
        </div>
        <div>
            <x-input-label for="description" value="Description" />
            <textarea id="description" name="description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10">{{ old('description', $event->description) }}</textarea>
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $event->is_published ?? true))>
            Publié
        </label>

        <x-primary-button>Enregistrer</x-primary-button>
    </form>
</x-admin-layout>
