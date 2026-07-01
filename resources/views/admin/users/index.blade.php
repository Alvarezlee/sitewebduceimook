<x-admin-layout title="Utilisateurs">
    <form method="GET" class="mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10 text-sm w-64">
    </form>

    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr>
                    <th class="p-3">Nom</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Rôles</th>
                    <th class="p-3">Statut</th>
                    <th class="p-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $user->full_name }}</td>
                        <td class="p-3">{{ $user->email }}</td>
                        <td class="p-3">{{ $user->roles->pluck('name')->join(', ') }}</td>
                        <td class="p-3">{{ $user->status }}</td>
                        <td class="p-3"><a href="{{ route('admin.users.edit', $user) }}" class="text-brand-600 hover:underline">Modifier</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
</x-admin-layout>
