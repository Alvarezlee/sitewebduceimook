<x-site-layout title="Mes commandes">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-16">
        <span class="section-eyebrow">Boutique</span>
        <h1 class="section-title mt-1 mb-10">Mes commandes</h1>

        <div class="space-y-4">
            @foreach ($orders as $order)
                <a href="{{ route('shop.orders.show', $order) }}" class="glass-card p-6 flex items-center justify-between hover:shadow-lg transition">
                    <div>
                        <p class="font-semibold">{{ $order->order_number }}</p>
                        <p class="text-sm text-gray-500">{{ $order->created_at->translatedFormat('d/m/Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold">{{ number_format((float) $order->total, 0, ',', ' ') }} XAF</p>
                        <p class="text-xs uppercase font-semibold text-brand-600">{{ $order->status }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-10">{{ $orders->links() }}</div>
    </div>
</x-site-layout>
