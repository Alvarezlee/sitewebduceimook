<?php

use App\Models\User;
use App\Services\Auth\TwoFactorAuthenticationService;
use Illuminate\Support\Facades\Crypt;
use Livewire\Volt\Volt;
use PragmaRX\Google2FA\Google2FA;

test('a user can enable and confirm two-factor authentication', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $component = Volt::test('profile.two-factor-authentication-form');
    $component->call('startEnabling');

    $user->refresh();
    expect($user->two_factor_secret)->not->toBeNull();

    $service = app(TwoFactorAuthenticationService::class);
    $secret = Crypt::decryptString($user->two_factor_secret);
    $validCode = (new Google2FA)->getCurrentOtp($secret);

    $component->set('code', $validCode)->call('confirm');

    $user->refresh();
    expect($user->two_factor_confirmed_at)->not->toBeNull();
    expect($service->isEnabled($user))->toBeTrue();
});

test('login requires a two-factor code when enabled', function () {
    $user = User::factory()->create();
    $service = app(TwoFactorAuthenticationService::class);
    $secret = $service->generateSecretKey();
    $service->enable($user, $secret);
    $service->confirm($user, (new Google2FA)->getCurrentOtp($secret));

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password')
        ->call('login');

    $component->assertRedirect(route('two-factor.challenge', absolute: false));
    $this->assertGuest();

    $challenge = Volt::test('pages.auth.two-factor-challenge')
        ->set('code', (new Google2FA)->getCurrentOtp($secret))
        ->call('challenge');

    $challenge->assertRedirect(route('dashboard', absolute: false));
    $this->assertAuthenticatedAs($user);
});

test('admin routes require two-factor to be enabled for privileged roles', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->get('/admin')
        ->assertRedirect(route('profile'));
});
