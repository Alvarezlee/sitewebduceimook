<?php

namespace App\Services\Payment\Contracts;

use App\Models\Payment\Payment;

interface PaymentGatewayInterface
{
    /**
     * Initie un paiement auprès de la passerelle et retourne l'URL de
     * redirection vers laquelle envoyer l'utilisateur.
     */
    public function initiate(Payment $payment): string;

    /**
     * Vérifie l'authenticité d'une notification (webhook) reçue de la
     * passerelle avant de faire confiance à son contenu.
     */
    public function verifyWebhookSignature(array $payload): bool;

    /**
     * Détermine si la notification indique un paiement réussi.
     */
    public function isSuccessful(array $payload): bool;

    /**
     * Extrait la référence de transaction unique de la passerelle depuis
     * la notification (utilisée pour l'idempotence).
     */
    public function transactionReference(array $payload): ?string;
}
