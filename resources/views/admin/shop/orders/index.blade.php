<x-admin-layout title="Commandes">
    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">N°</th><th class="p-3">Client</th><th class="p-3">Total</th><th class="p-3">Statut</th><th class="p-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $order->order_number }}</td>
                        <td class="p-3">{{ $order->user->full_name }}</td>
                        <td class="p-3">{{ number_format((float) $order->total, 0, ',', ' ') }} XAF</td>
                        <td class="p-3">{{ $order->status }}</td>
                        <td class="p-3"><a href="{{ route('admin.shop.orders.show', $order) }}" class="text-brand-600 hover:underline">Détails</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $orders->links() }}</div>
</x-admin-layout>
