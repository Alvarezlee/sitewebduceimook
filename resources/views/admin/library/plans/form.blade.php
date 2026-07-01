<x-admin-layout :title="$plan->exists ? 'Modifier la formule' : 'Nouvelle formule'">
    <form method="POST" action="{{ $plan->exists ? route('admin.library.plans.update', $plan) : route('admin.library.plans.store') }}" class="glass-card p-6 max-w-xl space-y-4">
        @csrf
        @if ($plan->exists) @method('PUT') @endif

        <div>
            <x-input-label for="name" value="Nom" />
            <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $plan->name)" required />
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="price" value="Prix (XAF)" />
                <x-text-input id="price" name="price" type="number" step="1" class="block mt-1 w-full" :value="old('price', $plan->price)" required />
            </div>
            <div>
                <x-input-label for="duration_days" value="Durée (jours)" />
                <x-text-input id="duration_days" name="duration_days" type="number" class="block mt-1 w-full" :value="old('duration_days', $plan->duration_days)" required />
            </div>
        </div>
        <div>
            <x-input-label for="max_downloads" value="Quota de téléchargements (vide = illimité)" />
            <x-text-input id="max_downloads" name="max_downloads" type="number" class="block mt-1 w-full" :value="old('max_downloads', $plan->max_downloads)" />
        </div>
        <div>
            <x-input-label for="description" value="Description" />
            <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10">{{ old('description', $plan->description) }}</textarea>
        </div>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan->is_active ?? true))>
            Actif
        </label>

        <x-primary-button>Enregistrer</x-primary-button>
    </form>
</x-admin-layout>
