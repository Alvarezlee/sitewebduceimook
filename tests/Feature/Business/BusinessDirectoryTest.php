<?php

use App\Models\Business\Business;
use App\Models\User;

test('the business directory and show pages render', function () {
    $business = Business::factory()->create(['is_published' => true]);

    $this->get(route('businesses.index'))->assertOk()->assertSee($business->name);
    $this->get(route('businesses.show', $business))->assertOk()->assertSee($business->name);
});

test('an unpublished business is not publicly visible', function () {
    $business = Business::factory()->create(['is_published' => false]);

    $this->get(route('businesses.show', $business))->assertNotFound();
});

test('a visitor can send a contact message to a business', function () {
    $business = Business::factory()->create(['is_published' => true]);

    $this->post(route('businesses.contact', $business), [
        'name' => 'Client Test',
        'email' => 'client@example.com',
        'message' => 'Bonjour, je suis intéressé par vos services.',
    ])->assertRedirect();

    $this->assertDatabaseHas('business_contact_messages', [
        'business_id' => $business->id,
        'email' => 'client@example.com',
    ]);
});

test('an entreprise user can create and update their business showcase', function () {
    $user = User::factory()->create();
    $user->assignRole('entreprise');

    $this->actingAs($user)
        ->put(route('business.dashboard.update'), ['name' => 'AL-INFOTECH SARL'])
        ->assertRedirect(route('business.dashboard.edit'));

    $this->assertDatabaseHas('businesses', [
        'user_id' => $user->id,
        'name' => 'AL-INFOTECH SARL',
        'is_published' => false,
    ]);
});

test('a user cannot manage another entreprise business', function () {
    $owner = User::factory()->create();
    $owner->assignRole('entreprise');
    $business = Business::factory()->create(['user_id' => $owner->id]);

    $intruder = User::factory()->create();
    $intruder->assignRole('entreprise');

    $service = $business->services()->create(['name' => 'Test service']);

    $this->actingAs($intruder)
        ->delete(route('business.dashboard.services.destroy', $service))
        ->assertForbidden();
});
