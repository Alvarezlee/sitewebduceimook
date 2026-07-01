<x-admin-layout :title="$edition->exists ? 'Modifier l\'édition' : 'Nouvelle édition'">
    <form method="POST" action="{{ $edition->exists ? route('admin.quiz.editions.update', $edition) : route('admin.quiz.editions.store') }}" class="glass-card p-6 max-w-xl space-y-4">
        @csrf
        @if ($edition->exists) @method('PUT') @endif

        <div>
            <x-input-label for="name" value="Nom" />
            <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $edition->name)" required />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="starts_at" value="Début des inscriptions" />
                <x-text-input id="starts_at" name="starts_at" type="datetime-local" class="block mt-1 w-full" :value="old('starts_at', $edition->starts_at?->format('Y-m-d\TH:i'))" required />
            </div>
            <div>
                <x-input-label for="ends_at" value="Fin des inscriptions" />
                <x-text-input id="ends_at" name="ends_at" type="datetime-local" class="block mt-1 w-full" :value="old('ends_at', $edition->ends_at?->format('Y-m-d\TH:i'))" required />
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="registration_price" value="Prix d'inscription (XAF)" />
                <x-text-input id="registration_price" name="registration_price" type="number" class="block mt-1 w-full" :value="old('registration_price', $edition->registration_price)" required />
            </div>
            <div>
                <x-input-label for="duration_minutes" value="Durée (minutes)" />
                <x-text-input id="duration_minutes" name="duration_minutes" type="number" class="block mt-1 w-full" :value="old('duration_minutes', $edition->duration_minutes)" required />
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="questions_per_attempt" value="Nombre de questions" />
                <x-text-input id="questions_per_attempt" name="questions_per_attempt" type="number" class="block mt-1 w-full" :value="old('questions_per_attempt', $edition->questions_per_attempt)" required />
            </div>
            <div>
                <x-input-label for="max_attempts" value="Tentatives max" />
                <x-text-input id="max_attempts" name="max_attempts" type="number" class="block mt-1 w-full" :value="old('max_attempts', $edition->max_attempts ?? 1)" required />
            </div>
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $edition->is_active ?? true))>
            Active
        </label>

        <x-primary-button>Enregistrer</x-primary-button>
    </form>
</x-admin-layout>
