<?php

use App\Models\Customer;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('customers can authenticate using the login screen', function () {
    $customer = Customer::factory()->create();

    $response = $this->post('/login', [
        'email' => $customer->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('customers can not authenticate with invalid password', function () {
    $customer = Customer::factory()->create();

    $this->post('/login', [
        'email' => $customer->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('customers can logout', function () {
    $customer = Customer::factory()->create();

    $response = $this->actingAs($customer)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
