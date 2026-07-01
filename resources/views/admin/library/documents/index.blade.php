<x-admin-layout title="Documents bibliothèque">
    <a href="{{ route('admin.library.documents.create') }}" class="btn-primary mb-6 inline-flex">Nouveau document</a>

    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Titre</th><th class="p-3">Catégorie</th><th class="p-3">Type</th><th class="p-3">Téléchargements</th><th class="p-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($documents as $document)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $document->title }}</td>
                        <td class="p-3">{{ $document->category->name }}</td>
                        <td class="p-3">{{ $document->type }}</td>
                        <td class="p-3">{{ $document->downloads_count }}</td>
                        <td class="p-3 flex gap-3">
                            <a href="{{ route('admin.library.documents.edit', $document) }}" class="text-brand-600 hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.library.documents.destroy', $document) }}" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $documents->links() }}</div>
</x-admin-layout>
