<?php

use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $phone = '';
    public string $account_type = 'eleve';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:190', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:30', 'unique:'.User::class],
            'account_type' => ['required', 'in:eleve,enseignant'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'status' => 'active',
        ]);

        $user->assignRole($validated['account_type']);

        if ($validated['account_type'] === 'eleve') {
            StudentProfile::query()->create(['user_id' => $user->id]);
        } else {
            TeacherProfile::query()->create(['user_id' => $user->id]);
        }

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <h1 class="text-xl font-bold mb-6">Créer votre compte CEIMO</h1>

    <form wire:submit="register">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="first_name" :value="__('Prénom')" />
                <x-text-input wire:model="first_name" id="first_name" class="block mt-1 w-full" type="text" name="first_name" required autofocus autocomplete="given-name" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="last_name" :value="__('Nom')" />
                <x-text-input wire:model="last_name" id="last_name" class="block mt-1 w-full" type="text" name="last_name" required autocomplete="family-name" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Téléphone')" />
            <x-text-input wire:model="phone" id="phone" class="block mt-1 w-full" type="tel" name="phone" required autocomplete="tel" placeholder="6XXXXXXXX" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Account type -->
        <div class="mt-4">
            <x-input-label :value="__('Je suis')" />
            <div class="flex gap-4 mt-1">
                <label class="inline-flex items-center">
                    <input type="radio" wire:model="account_type" value="eleve" class="text-indigo-600 focus:ring-indigo-500">
                    <span class="ms-2">{{ __('Élève / Étudiant') }}</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" wire:model="account_type" value="enseignant" class="text-indigo-600 focus:ring-indigo-500">
                    <span class="ms-2">{{ __('Enseignant') }}</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('account_type')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}" wire:navigate>
                {{ __('Déjà inscrit ?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __("S'inscrire") }}
            </x-primary-button>
        </div>
    </form>
</div>
