<x-admin-layout title="Éditions Quiz">
    <a href="{{ route('admin.quiz.editions.create') }}" class="btn-primary mb-6 inline-flex">Nouvelle édition</a>

    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Nom</th><th class="p-3">Candidats</th><th class="p-3">Active</th><th class="p-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($editions as $edition)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $edition->name }}</td>
                        <td class="p-3">{{ $edition->candidates_count }}</td>
                        <td class="p-3">{{ $edition->is_active ? 'Oui' : 'Non' }}</td>
                        <td class="p-3 flex gap-3">
                            <a href="{{ route('admin.quiz.candidates.index', ['edition' => $edition->id]) }}" class="text-brand-600 hover:underline">Candidats</a>
                            <a href="{{ route('admin.quiz.editions.edit', $edition) }}" class="text-brand-600 hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.quiz.editions.destroy', $edition) }}" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $editions->links() }}</div>
</x-admin-layout>
