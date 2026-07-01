<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

/**
 * Valide un jeton Google reCAPTCHA v3. Si aucune clé secrète n'est
 * configurée (RECAPTCHA_SECRET_KEY), la vérification est ignorée afin de
 * ne pas bloquer le développement local / les environnements de test.
 *
 * Ne pas marquer le champ "nullable" dans les FormRequest qui utilisent
 * cette règle : le widget <x-recaptcha> n'insère le champ caché que
 * lorsque reCAPTCHA est configuré, donc le champ est absent (et la règle
 * ignorée) quand la fonctionnalité est désactivée, et présent (donc
 * validé) dès qu'elle est activée.
 */
class RecaptchaRule implements ValidationRule
{
    public function __construct(private readonly float $minScore = 0.5) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.recaptcha.secret_key');

        if (blank($secret)) {
            return;
        }

        if (blank($value)) {
            $fail('La vérification anti-robot a échoué. Veuillez réessayer.');

            return;
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secret,
            'response' => $value,
        ]);

        $success = (bool) $response->json('success')
            && (float) $response->json('score', 0) >= $this->minScore;

        if (! $success) {
            $fail('La vérification anti-robot a échoué. Veuillez réessayer.');
        }
    }
}
