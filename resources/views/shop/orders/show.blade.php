<x-site-layout :title="'Commande '.$order->order_number">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-16">
        <span class="section-eyebrow">Boutique</span>
        <h1 class="section-title mt-1 mb-2">{{ $order->order_number }}</h1>
        <p class="text-sm uppercase font-semibold text-brand-600 mb-8">{{ $order->status }}</p>

        <div class="glass-card p-6">
            @foreach ($order->items as $item)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-white/5 last:border-0">
                    <p>{{ $item->product->name }} × {{ $item->quantity }}</p>
                    <p class="font-semibold">{{ number_format((float) $item->total_price, 0, ',', ' ') }} XAF</p>
                </div>
            @endforeach
            <div class="flex items-center justify-between pt-4 font-bold text-lg">
                <p>Total</p>
                <p>{{ number_format((float) $order->total, 0, ',', ' ') }} XAF</p>
            </div>
        </div>

        @if ($order->invoice_path)
            <a href="{{ URL::temporarySignedRoute('shop.orders.invoice', now()->addMinutes(10), ['order' => $order->id]) }}" class="btn-primary mt-8 inline-flex">
                Télécharger la facture PDF
            </a>
        @endif
    </div>
</x-site-layout>
