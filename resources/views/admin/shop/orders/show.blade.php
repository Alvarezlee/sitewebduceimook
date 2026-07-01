<x-admin-layout :title="'Commande '.$order->order_number">
    <div class="glass-card p-6 mb-6">
        <p class="font-bold">{{ $order->order_number }}</p>
        <p class="text-sm text-gray-500">{{ $order->user->full_name }} — {{ $order->user->email }}</p>

        @foreach ($order->items as $item)
            <div class="flex justify-between py-2 border-t border-gray-100 dark:border-white/5 mt-2">
                <p>{{ $item->product->name }} × {{ $item->quantity }}</p>
                <p>{{ number_format((float) $item->total_price, 0, ',', ' ') }} XAF</p>
            </div>
        @endforeach

        <div class="flex justify-between pt-4 font-bold">
            <p>Total</p>
            <p>{{ number_format((float) $order->total, 0, ',', ' ') }} XAF</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.shop.orders.update', $order) }}" class="glass-card p-6 max-w-md flex items-end gap-3">
        @csrf @method('PUT')
        <div class="flex-1">
            <x-input-label for="status" value="Statut" />
            <select id="status" name="status" class="mt-1 w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-white/10 text-sm">
                @foreach (['pending','paid','processing','completed','cancelled'] as $status)
                    <option value="{{ $status }}" @selected($order->status === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <x-primary-button>Mettre à jour</x-primary-button>
    </form>
</x-admin-layout>
