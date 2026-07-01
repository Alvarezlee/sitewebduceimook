<?php

namespace App\Services\Payment\Gateways;

use App\Models\Payment\Payment;
use App\Services\Payment\Contracts\PaymentGatewayInterface;

/**
 * Passerelle Monetbil (Mobile Money), basée sur le SDK officiel Monetbil
 * (widget v2.1) : https://github.com/Monetbil/monetbil-php
 *
 * Construction de l'URL de paiement : {widget_url}/{service_key}?params...
 * Signature : md5(service_secret . implode('', ksort($params))) sur les
 * paramètres triés par clé, hors champ "sign" lui-même.
 */
class MonetbilGateway implements PaymentGatewayInterface
{
    public function __construct(
        private readonly string $serviceKey,
        private readonly string $serviceSecret,
        private readonly string $widgetUrl,
        private readonly string $currency,
        private readonly string $country,
    ) {}

    public function initiate(Payment $payment): string
    {
        $params = $this->buildParams($payment);
        $params['sign'] = $this->sign($params);

        return sprintf('%s/%s?%s', rtrim($this->widgetUrl, '/'), $this->serviceKey, http_build_query($params));
    }

    public function verifyWebhookSignature(array $payload): bool
    {
        if (! array_key_exists('sign', $payload)) {
            return false;
        }

        $sign = $payload['sign'];
        unset($payload['sign']);

        return hash_equals($this->sign($payload), (string) $sign);
    }

    public function isSuccessful(array $payload): bool
    {
        return ($payload['status'] ?? null) === 'success';
    }

    public function transactionReference(array $payload): ?string
    {
        return $payload['transaction_id'] ?? null;
    }

    /**
     * @return array<string, string>
     */
    private function buildParams(Payment $payment): array
    {
        return array_filter([
            'amount' => (string) (int) round((float) $payment->amount),
            'currency' => $payment->currency ?? $this->currency,
            'country' => $this->country,
            'item_ref' => $payment->payable_type.':'.$payment->payable_id,
            'payment_ref' => (string) $payment->id,
            'user' => (string) $payment->user_id,
            'phone' => $payment->phone_number,
            'email' => $payment->user?->email,
            'first_name' => $payment->user?->first_name,
            'last_name' => $payment->user?->last_name,
            'return_url' => config('services.monetbil.return_url'),
            'notify_url' => config('services.monetbil.notify_url'),
        ], fn ($value) => $value !== null && $value !== '');
    }

    /**
     * @param  array<string, mixed>  $params
     */
    private function sign(array $params): string
    {
        ksort($params);

        return md5($this->serviceSecret.implode('', $params));
    }
}
