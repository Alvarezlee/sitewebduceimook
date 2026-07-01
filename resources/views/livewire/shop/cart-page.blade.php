<div>
    @if ($items->isEmpty())
        <p class="text-gray-500">Votre panier est vide.</p>
        <a href="{{ route('shop.index') }}" class="btn-primary mt-4 inline-flex">Découvrir la boutique</a>
    @else
        <div class="space-y-4">
            @foreach ($items as $entry)
                <div class="glass-card p-4 flex items-center justify-between gap-4">
                    <div>
                        <p class="font-semibold">{{ $entry['product']->name }}</p>
                        <p class="text-sm text-gray-500">{{ number_format((float) $entry['product']->price, 0, ',', ' ') }} XAF</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <input
                            type="number"
                            min="1"
                            value="{{ $entry['quantity'] }}"
                            wire:change="updateQuantity({{ $entry['product']->id }}, $event.target.value)"
                            class="w-20 rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10 text-sm"
                        >
                        <p class="w-24 text-right font-semibold">{{ number_format($entry['total'], 0, ',', ' ') }} XAF</p>
                        <button wire:click="remove({{ $entry['product']->id }})" class="text-red-600 text-sm hover:underline">Retirer</button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 flex items-center justify-between glass-card p-6">
            <p class="text-lg font-bold">Sous-total : {{ number_format($subtotal, 0, ',', ' ') }} XAF</p>
            <a href="{{ route('shop.checkout') }}" class="btn-primary">Passer la commande</a>
        </div>
    @endif
</div>
