<?php

use App\Models\Customer;

test('profile page is displayed', function () {
    $customer = Customer::factory()->create();

    $response = $this
        ->actingAs($customer)
        ->get('/settings/profile');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $customer = Customer::factory()->create();

    $response = $this
        ->actingAs($customer)
        ->patch('/settings/profile', [
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings/profile');

    $customer->refresh();

    expect($customer->first_name)->toBe('Test');
    expect($customer->last_name)->toBe('Customer');
    expect($customer->email)->toBe('test@example.com');
    expect($customer->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $customer = Customer::factory()->create();

    $response = $this
        ->actingAs($customer)
        ->patch('/settings/profile', [
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'email' => $customer->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings/profile');

    expect($customer->refresh()->email_verified_at)->not->toBeNull();
});

test('customer can delete their account', function () {
    $customer = Customer::factory()->create();

    $response = $this
        ->actingAs($customer)
        ->delete('/settings/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    expect($customer->fresh())->toBeNull();
});

test('correct password must be provided to delete account', function () {
    $customer = Customer::factory()->create();

    $response = $this
        ->actingAs($customer)
        ->from('/settings/profile')
        ->delete('/settings/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrors('password')
        ->assertRedirect('/settings/profile');

    expect($customer->fresh())->not->toBeNull();
});
