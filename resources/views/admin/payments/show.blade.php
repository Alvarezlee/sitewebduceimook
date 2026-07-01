<x-admin-layout title="Détail paiement">
    <div class="glass-card p-6 max-w-xl space-y-2 text-sm">
        <p><strong>Utilisateur :</strong> {{ $payment->user->full_name }} ({{ $payment->user->email }})</p>
        <p><strong>Type :</strong> {{ class_basename($payment->payable_type) }}</p>
        <p><strong>Référence gateway :</strong> {{ $payment->gateway_reference ?? '—' }}</p>
        <p><strong>Montant :</strong> {{ number_format((float) $payment->amount, 0, ',', ' ') }} {{ $payment->currency }}</p>
        <p><strong>Téléphone :</strong> {{ $payment->phone_number }}</p>
        <p><strong>Statut :</strong> {{ $payment->status }}</p>
        <p><strong>Payé le :</strong> {{ $payment->paid_at?->translatedFormat('d/m/Y H:i') ?? '—' }}</p>
    </div>
</x-admin-layout>
