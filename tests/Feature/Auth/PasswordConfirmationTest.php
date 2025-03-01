<?php

use App\Models\Customer;

test('confirm password screen can be rendered', function () {
    $customer = Customer::factory()->create();

    $response = $this->actingAs($customer)->get('/confirm-password');

    $response->assertStatus(200);
});

test('password can be confirmed', function () {
    $customer = Customer::factory()->create();

    $response = $this->actingAs($customer)->post('/confirm-password', [
        'password' => 'password',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
});

test('password is not confirmed with invalid password', function () {
    $customer = Customer::factory()->create();

    $response = $this->actingAs($customer)->post('/confirm-password', [
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors();
});
