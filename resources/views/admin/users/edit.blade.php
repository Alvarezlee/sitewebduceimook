<x-admin-layout title="Modifier l'utilisateur">
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="glass-card p-6 max-w-xl space-y-4">
        @csrf
        @method('PUT')

        <div>
            <p class="font-semibold">{{ $user->full_name }}</p>
            <p class="text-sm text-gray-500">{{ $user->email }}</p>
        </div>

        <div>
            <x-input-label for="status" value="Statut" />
            <select id="status" name="status" class="mt-1 w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10 text-sm">
                @foreach (['active', 'suspended', 'pending'] as $status)
                    <option value="{{ $status }}" @selected($user->status === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <x-input-label value="Rôles" />
            <div class="mt-2 space-y-2">
                @foreach ($roles as $role)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" @checked($user->hasRole($role->name))>
                        {{ $role->name }}
                    </label>
                @endforeach
            </div>
        </div>

        <x-primary-button>Enregistrer</x-primary-button>
    </form>
</x-admin-layout>
