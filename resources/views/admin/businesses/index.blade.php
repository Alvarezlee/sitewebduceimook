<x-admin-layout title="Entreprises des membres">
    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Nom</th><th class="p-3">Propriétaire</th><th class="p-3">Publiée</th><th class="p-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($businesses as $business)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $business->name }}</td>
                        <td class="p-3">{{ $business->user->full_name }}</td>
                        <td class="p-3">{{ $business->is_published ? 'Oui' : 'Non' }}</td>
                        <td class="p-3 flex gap-3">
                            <form method="POST" action="{{ route('admin.businesses.toggle-publish', $business) }}">
                                @csrf @method('PUT')
                                <button class="text-brand-600 hover:underline">{{ $business->is_published ? 'Dépublier' : 'Publier' }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.businesses.destroy', $business) }}" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $businesses->links() }}</div>
</x-admin-layout>
