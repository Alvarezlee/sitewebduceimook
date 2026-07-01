<x-admin-layout title="Paramètres">
    <form method="POST" action="{{ route('admin.settings.update') }}" class="glass-card p-6 max-w-2xl space-y-4">
        @csrf @method('PUT')

        @foreach ($settings as $key => $value)
            <div>
                <x-input-label :for="$key" :value="$key" />
                <x-text-input :id="$key" name="values[{{ $key }}]" class="block mt-1 w-full" :value="old('values.'.$key, $value)" />
            </div>
        @endforeach

        <x-primary-button>Enregistrer</x-primary-button>
    </form>
</x-admin-layout>
