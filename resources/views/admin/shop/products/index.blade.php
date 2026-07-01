<x-admin-layout title="Produits boutique">
    <a href="{{ route('admin.shop.products.create') }}" class="btn-primary mb-6 inline-flex">Nouveau produit</a>

    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Nom</th><th class="p-3">Catégorie</th><th class="p-3">Prix</th><th class="p-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $product->name }}</td>
                        <td class="p-3">{{ $product->category->name }}</td>
                        <td class="p-3">{{ number_format((float) $product->price, 0, ',', ' ') }} XAF</td>
                        <td class="p-3 flex gap-3">
                            <a href="{{ route('admin.shop.products.edit', $product) }}" class="text-brand-600 hover:underline">Modifier</a>
                            <form method="POST" action="{{ route('admin.shop.products.destroy', $product) }}" onsubmit="return confirm('Supprimer ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $products->links() }}</div>
</x-admin-layout>
