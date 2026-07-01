<?php

use App\Services\Auth\TwoFactorAuthenticationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public bool $enabling = false;

    public string $pendingSecret = '';

    public string $code = '';

    public array $recoveryCodes = [];

    public function mount(TwoFactorAuthenticationService $service): void
    {
        $this->recoveryCodes = $service->isEnabled(Auth::user()) ? $service->recoveryCodes(Auth::user()) : [];
    }

    public function startEnabling(TwoFactorAuthenticationService $service): void
    {
        $this->pendingSecret = $service->generateSecretKey();
        $service->enable(Auth::user(), $this->pendingSecret);
        $this->enabling = true;
    }

    public function confirm(TwoFactorAuthenticationService $service): void
    {
        $this->validate(['code' => ['required', 'string', 'size:6']]);

        if (! $service->confirm(Auth::user(), $this->code)) {
            $this->addError('code', __('Le code saisi est invalide.'));

            return;
        }

        $this->enabling = false;
        $this->code = '';
        $this->recoveryCodes = $service->recoveryCodes(Auth::user());
        $this->dispatch('two-factor-enabled');
    }

    public function disable(TwoFactorAuthenticationService $service): void
    {
        $service->disable(Auth::user());
        $this->recoveryCodes = [];
        $this->enabling = false;
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Double authentification (2FA)') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Ajoutez une couche de sécurité supplémentaire à votre compte à l'aide d'une application d'authentification (Google Authenticator, Authy...).") }}
        </p>
    </header>

    <div class="mt-6">
        @if (auth()->user()->two_factor_confirmed_at)
            <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                {{ __('La double authentification est activée.') }}
            </p>

            @if (count($recoveryCodes))
                <div class="mt-4 grid grid-cols-2 gap-2 font-mono text-sm bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                    @foreach ($recoveryCodes as $recoveryCode)
                        <span>{{ $recoveryCode }}</span>
                    @endforeach
                </div>
                <p class="mt-2 text-xs text-gray-500">{{ __('Conservez ces codes de secours en lieu sûr : ils permettent de vous connecter si vous perdez votre appareil.') }}</p>
            @endif

            <button wire:click="disable" wire:confirm="{{ __('Voulez-vous vraiment désactiver la double authentification ?') }}" class="mt-4 text-sm font-medium text-red-600 dark:text-red-400 hover:underline">
                {{ __('Désactiver la double authentification') }}
            </button>
        @elseif ($enabling)
            <div class="max-w-xs">
                {!! app(\App\Services\Auth\TwoFactorAuthenticationService::class)->qrCodeSvg(auth()->user(), $pendingSecret) !!}
            </div>
            <p class="mt-2 text-xs text-gray-500">{{ __('Scannez ce code QR avec votre application d\'authentification, puis saisissez le code à 6 chiffres généré.') }}</p>

            <form wire:submit="confirm" class="mt-4 max-w-xs">
                <x-input-label for="code" :value="__('Code de vérification')" />
                <x-text-input wire:model="code" id="code" type="text" inputmode="numeric" class="block mt-1 w-full" autocomplete="one-time-code" />
                <x-input-error :messages="$errors->get('code')" class="mt-2" />

                <x-primary-button class="mt-4">{{ __('Confirmer') }}</x-primary-button>
            </form>
        @else
            <x-secondary-button wire:click="startEnabling">
                {{ __('Activer la double authentification') }}
            </x-secondary-button>
        @endif
    </div>
</section>
