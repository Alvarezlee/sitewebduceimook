<x-site-layout title="Boutique" description="Livres, fascicules, cours, supports PDF, logiciels, formations et abonnements CEIMO.">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center justify-between mb-10">
            <div>
                <span class="section-eyebrow">Boutique</span>
                <h1 class="section-title mt-1">Ressources & formations</h1>
            </div>
            <a href="{{ route('shop.cart') }}" class="btn-secondary">🛒 Mon panier</a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-12">
            @foreach ($categories as $category)
                <a href="{{ route('shop.category', $category) }}" class="glass-card p-4 text-center hover:shadow-lg transition">
                    <p class="font-semibold text-sm">{{ $category->name }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $category->products_count }}</p>
                </a>
            @endforeach
        </div>

        <h2 class="text-xl font-bold mb-4">En vedette</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($featured as $product)
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
    </div>
</x-site-layout>
