<x-admin-layout title="Actualités">
    <a href="{{ route('admin.content.articles.create') }}" class="btn-primary mb-6 inline-flex">Nouvelle actualité</a>

    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Titre</th><th class="p-3">Publiée</th><th class="p-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($articles as $article)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $article->title }}</td>
                        <td class="p-3">{{ $article->is_published ? 'Oui' : 'Non' }}</td>
                        <td class="p-3 flex gap-3">
                            <a href="{{ route('admin.content.articles.edit', $article) }}" class="text-brand-600 hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.content.articles.destroy', $article) }}" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $articles->links() }}</div>
</x-admin-layout>
