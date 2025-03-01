<?php

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

test('password can be updated', function () {
    $customer = Customer::factory()->create();

    $response = $this
        ->actingAs($customer)
        ->from('/settings/password')
        ->put('/settings/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings/password');

    expect(Hash::check('new-password', $customer->refresh()->password))->toBeTrue();
});

test('correct password must be provided to update password', function () {
    $customer = Customer::factory()->create();

    $response = $this
        ->actingAs($customer)
        ->from('/settings/password')
        ->put('/settings/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasErrors('current_password')
        ->assertRedirect('/settings/password');
});
