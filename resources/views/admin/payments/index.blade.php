<x-admin-layout title="Paiements">
    <div class="glass-card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/5 text-left">
                <tr><th class="p-3">Utilisateur</th><th class="p-3">Type</th><th class="p-3">Montant</th><th class="p-3">Statut</th><th class="p-3"></th></tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr class="border-t border-gray-100 dark:border-white/5">
                        <td class="p-3">{{ $payment->user->full_name }}</td>
                        <td class="p-3">{{ class_basename($payment->payable_type) }}</td>
                        <td class="p-3">{{ number_format((float) $payment->amount, 0, ',', ' ') }} {{ $payment->currency }}</td>
                        <td class="p-3">{{ $payment->status }}</td>
                        <td class="p-3"><a href="{{ route('admin.payments.show', $payment) }}" class="text-brand-600 hover:underline">Détails</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $payments->links() }}</div>
</x-admin-layout>
