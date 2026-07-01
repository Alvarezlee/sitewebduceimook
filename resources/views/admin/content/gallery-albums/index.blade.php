<x-admin-layout title="Albums galerie">
    <a href="{{ route('admin.content.gallery-albums.create') }}" class="btn-primary mb-6 inline-flex">Nouvel album</a>

    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Titre</th><th class="p-3">Type</th><th class="p-3">Éléments</th><th class="p-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($albums as $album)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $album->title }}</td>
                        <td class="p-3">{{ $album->type }}</td>
                        <td class="p-3">{{ $album->items_count }}</td>
                        <td class="p-3 flex gap-3">
                            <a href="{{ route('admin.content.gallery-albums.edit', $album) }}" class="text-brand-600 hover:underline">Gérer</a>
                            <form method="POST" action="{{ route('admin.content.gallery-albums.destroy', $album) }}" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $albums->links() }}</div>
</x-admin-layout>
