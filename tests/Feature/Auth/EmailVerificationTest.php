<?php

use App\Models\Customer;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

test('email verification screen can be rendered', function () {
    $customer = Customer::factory()->unverified()->create();

    $response = $this->actingAs($customer)->get('/verify-email');

    $response->assertStatus(200);
});

test('email can be verified', function () {
    $customer = Customer::factory()->unverified()->create();

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $customer->id, 'hash' => sha1($customer->email)]
    );

    $response = $this->actingAs($customer)->get($verificationUrl);

    Event::assertDispatched(Verified::class);
    expect($customer->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(route('dashboard', absolute: false).'?verified=1');
});

test('email is not verified with invalid hash', function () {
    $customer = Customer::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $customer->id, 'hash' => sha1('wrong-email')]
    );

    $this->actingAs($customer)->get($verificationUrl);

    expect($customer->fresh()->hasVerifiedEmail())->toBeFalse();
});
