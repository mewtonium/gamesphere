<?php

use App\Models\Customer;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    Notification::fake();

    $customer = Customer::factory()->create();

    $this->post('/forgot-password', ['email' => $customer->email]);

    Notification::assertSentTo($customer, ResetPassword::class);
});

test('reset password screen can be rendered', function () {
    Notification::fake();

    $customer = Customer::factory()->create();

    $this->post('/forgot-password', ['email' => $customer->email]);

    Notification::assertSentTo($customer, ResetPassword::class, function ($notification) {
        $response = $this->get('/reset-password/'.$notification->token);

        $response->assertStatus(200);

        return true;
    });
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $customer = Customer::factory()->create();

    $this->post('/forgot-password', ['email' => $customer->email]);

    Notification::assertSentTo($customer, ResetPassword::class, function ($notification) use ($customer) {
        $response = $this->post('/reset-password', [
            'token' => $notification->token,
            'email' => $customer->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'));

        return true;
    });
});
