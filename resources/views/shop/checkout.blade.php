<x-site-layout title="Commande">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-16">
        <span class="section-eyebrow">Boutique</span>
        <h1 class="section-title mt-1 mb-10">Finaliser la commande</h1>

        <div class="glass-card p-6 mb-8">
            @foreach ($items as $entry)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-white/5 last:border-0">
                    <p>{{ $entry['product']->name }} × {{ $entry['quantity'] }}</p>
                    <p class="font-semibold">{{ number_format($entry['total'], 0, ',', ' ') }} XAF</p>
                </div>
            @endforeach
            <div class="flex items-center justify-between pt-4 font-bold text-lg">
                <p>Total</p>
                <p>{{ number_format($subtotal, 0, ',', ' ') }} XAF</p>
            </div>
        </div>

        <form method="POST" action="{{ route('shop.checkout.store') }}" class="glass-card p-6 space-y-4">
            @csrf
            <div>
                <x-input-label for="phone" value="Numéro Mobile Money" />
                <x-text-input id="phone" name="phone" class="block mt-1 w-full" placeholder="6XXXXXXXX" required />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>
            <button type="submit" class="btn-primary w-full">Payer et confirmer la commande</button>
        </form>
    </div>
</x-site-layout>
