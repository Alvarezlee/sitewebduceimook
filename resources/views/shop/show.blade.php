<x-site-layout :title="$product->name" :description="$product->description">
    @push('head')
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $product->name,
                'description' => $product->description,
                'offers' => [
                    '@type' => 'Offer',
                    'priceCurrency' => 'XAF',
                    'price' => (float) $product->price,
                    'availability' => $product->isInStock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                    'url' => url()->current(),
                ],
            ]) !!}
        </script>
    @endpush

    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-16">
        <p class="text-xs font-semibold text-accent-600 uppercase">{{ $product->type }}</p>
        <h1 class="section-title mt-2">{{ $product->name }}</h1>
        <p class="mt-4 text-gray-600 dark:text-gray-400">{{ $product->description }}</p>

        <div class="mt-8 glass-card p-6 flex items-center justify-between">
            <p class="text-3xl font-extrabold text-brand-600">{{ number_format((float) $product->price, 0, ',', ' ') }} <span class="text-sm font-medium">XAF</span></p>

            <form method="POST" action="{{ route('shop.cart.add', $product) }}" class="flex items-center gap-3">
                @csrf
                <input type="number" name="quantity" value="1" min="1" class="w-20 rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10 text-sm">
                <button type="submit" class="btn-primary">Ajouter au panier</button>
            </form>
        </div>
    </div>
</x-site-layout>
