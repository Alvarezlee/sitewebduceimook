<x-admin-layout title="Pages statiques">
    <a href="{{ route('admin.content.pages.create') }}" class="btn-primary mb-6 inline-flex">Nouvelle page</a>

    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Titre</th><th class="p-3">Slug</th><th class="p-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($pages as $page)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $page->title }}</td>
                        <td class="p-3">{{ $page->slug }}</td>
                        <td class="p-3 flex gap-3">
                            <a href="{{ route('admin.content.pages.edit', $page) }}" class="text-brand-600 hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.content.pages.destroy', $page) }}" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $pages->links() }}</div>
</x-admin-layout>
