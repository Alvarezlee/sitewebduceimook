<?php

use App\Models\User;
use App\Services\Auth\TwoFactorAuthenticationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $code = '';

    public bool $useRecoveryCode = false;

    public function mount(): void
    {
        if (! Session::has('login.id')) {
            $this->redirect(route('login', absolute: false), navigate: true);
        }
    }

    public function challenge(TwoFactorAuthenticationService $service): void
    {
        $this->validate(['code' => ['required', 'string']]);

        /** @var User $user */
        $user = User::query()->findOrFail(Session::get('login.id'));

        $verified = $this->useRecoveryCode
            ? $service->redeemRecoveryCode($user, $this->code)
            : $service->verify($user, $this->code);

        if (! $verified) {
            $this->addError('code', __('Le code fourni est invalide.'));

            return;
        }

        Auth::login($user, (bool) Session::get('login.remember', false));

        Session::forget(['login.id', 'login.remember']);
        Session::regenerate();

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Veuillez confirmer l\'accès à votre compte en saisissant le code d\'authentification fourni par votre application, ou l\'un de vos codes de secours.') }}
    </p>

    <form wire:submit="challenge">
        <x-input-label for="code" :value="$useRecoveryCode ? __('Code de secours') : __('Code d\'authentification')" />
        <x-text-input wire:model="code" id="code" type="text" class="block mt-1 w-full" autofocus autocomplete="one-time-code" />
        <x-input-error :messages="$errors->get('code')" class="mt-2" />

        <div class="flex items-center justify-between mt-4">
            <button type="button" wire:click="$set('useRecoveryCode', {{ $useRecoveryCode ? 'false' : 'true' }})" class="underline text-sm text-gray-600 dark:text-gray-400">
                {{ $useRecoveryCode ? __("Utiliser l'application d'authentification") : __('Utiliser un code de secours') }}
            </button>

            <x-primary-button>{{ __('Vérifier') }}</x-primary-button>
        </div>
    </form>
</div>
