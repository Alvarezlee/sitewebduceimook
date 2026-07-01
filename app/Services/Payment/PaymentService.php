<?php

namespace App\Services\Payment;

use App\Events\PaymentConfirmed;
use App\Models\Payment\Payment;
use App\Models\User;
use App\Services\Payment\Contracts\PaymentGatewayInterface;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;

class PaymentService
{
    public function __construct(private readonly PaymentGatewayInterface $gateway) {}

    /**
     * Crée une transaction de paiement en attente pour la ressource donnée
     * (abonnement bibliothèque, candidature quiz, commande) et retourne
     * l'enregistrement Payment ainsi que l'URL de redirection vers la
     * passerelle Monetbil.
     */
    public function initiate(User $user, Model $payable, float $amount, ?string $phone = null, string $currency = 'XAF'): Payment
    {
        $payment = Payment::query()->create([
            'user_id' => $user->id,
            'payable_type' => $payable->getMorphClass(),
            'payable_id' => $payable->getKey(),
            'gateway' => 'monetbil',
            'amount' => $amount,
            'currency' => $currency,
            'phone_number' => $phone,
            'status' => 'pending',
        ]);

        $payment->setRelation('user', $user);

        $paymentUrl = $this->gateway->initiate($payment);

        return $payment->fresh()->setAttribute('_payment_url', $paymentUrl);
    }

    /**
     * Traite une notification (webhook) de la passerelle : vérifie la
     * signature, retrouve la transaction, met à jour son statut de façon
     * idempotente et déclenche l'événement métier si le paiement est validé.
     */
    public function handleWebhook(array $payload): Payment
    {
        if (! $this->gateway->verifyWebhookSignature($payload)) {
            throw new RuntimeException('Signature de paiement invalide.');
        }

        $paymentId = $payload['payment_ref'] ?? null;

        $payment = Payment::query()->findOrFail($paymentId);

        if ($payment->status === 'success') {
            return $payment;
        }

        $successful = $this->gateway->isSuccessful($payload);

        $payment->update([
            'status' => $successful ? 'success' : 'failed',
            'gateway_reference' => $this->gateway->transactionReference($payload) ?? $payment->gateway_reference,
            'payload' => $payload,
            'paid_at' => $successful ? now() : null,
        ]);

        if ($successful) {
            event(new PaymentConfirmed($payment->fresh()));
        }

        return $payment->fresh();
    }
}
