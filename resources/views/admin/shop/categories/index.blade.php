<x-admin-layout title="Catégories boutique">
    <a href="{{ route('admin.shop.categories.create') }}" class="btn-primary mb-6 inline-flex">Nouvelle catégorie</a>

    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Nom</th><th class="p-3">Produits</th><th class="p-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $category->name }}</td>
                        <td class="p-3">{{ $category->products_count }}</td>
                        <td class="p-3 flex gap-3">
                            <a href="{{ route('admin.shop.categories.edit', $category) }}" class="text-brand-600 hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.shop.categories.destroy', $category) }}" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $categories->links() }}</div>
</x-admin-layout>
