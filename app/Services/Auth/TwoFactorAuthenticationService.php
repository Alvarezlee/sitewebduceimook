<?php

namespace App\Services\Auth;

use App\Models\User;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthenticationService
{
    public function __construct(private readonly Google2FA $google2fa) {}

    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    public function qrCodeUrl(User $user, string $secret): string
    {
        return $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret,
        );
    }

    public function qrCodeSvg(User $user, string $secret): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(192),
            new SvgImageBackEnd,
        );

        return (new Writer($renderer))->writeString($this->qrCodeUrl($user, $secret));
    }

    public function enable(User $user, string $secret): void
    {
        $user->forceFill([
            'two_factor_secret' => Crypt::encryptString($secret),
            'two_factor_recovery_codes' => Crypt::encryptString(json_encode($this->generateRecoveryCodes())),
            'two_factor_confirmed_at' => null,
        ])->save();
    }

    public function confirm(User $user, string $code): bool
    {
        if (! $this->verify($user, $code)) {
            return false;
        }

        $user->forceFill(['two_factor_confirmed_at' => now()])->save();

        return true;
    }

    public function disable(User $user): void
    {
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();
    }

    public function verify(User $user, string $code): bool
    {
        if (! $user->two_factor_secret) {
            return false;
        }

        $secret = Crypt::decryptString($user->two_factor_secret);

        return $this->google2fa->verifyKey($secret, $code);
    }

    public function recoveryCodes(User $user): array
    {
        if (! $user->two_factor_recovery_codes) {
            return [];
        }

        return json_decode(Crypt::decryptString($user->two_factor_recovery_codes), true) ?? [];
    }

    public function redeemRecoveryCode(User $user, string $code): bool
    {
        $codes = $this->recoveryCodes($user);

        if (! in_array($code, $codes, true)) {
            return false;
        }

        $remaining = array_values(array_diff($codes, [$code]));

        $user->forceFill([
            'two_factor_recovery_codes' => Crypt::encryptString(json_encode($remaining)),
        ])->save();

        return true;
    }

    public function isEnabled(User $user): bool
    {
        return $user->two_factor_secret !== null && $user->two_factor_confirmed_at !== null;
    }

    private function generateRecoveryCodes(): array
    {
        return collect(range(1, 8))
            ->map(fn () => Str::upper(Str::random(4).'-'.Str::random(4)))
            ->all();
    }
}
