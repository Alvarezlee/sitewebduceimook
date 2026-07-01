<x-site-layout :title="$category->name">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <span class="section-eyebrow">Boutique</span>
        <h1 class="section-title mt-1 mb-10">{{ $category->name }}</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($products as $product)
                <div class="glass-card p-5 flex flex-col">
                    <a href="{{ route('shop.show', $product) }}">
                        <p class="text-xs font-semibold text-accent-600 uppercase">{{ $product->type }}</p>
                        <h3 class="mt-2 font-bold">{{ $product->name }}</h3>
                    </a>
                    <p class="mt-2 text-brand-600 font-bold">{{ number_format((float) $product->price, 0, ',', ' ') }} XAF</p>
                    <form method="POST" action="{{ route('shop.cart.add', $product) }}" class="mt-4">
                        @csrf
                        <button type="submit" class="btn-primary w-full text-sm">Ajouter au panier</button>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="mt-10">{{ $products->links() }}</div>
    </div>
</x-site-layout>
