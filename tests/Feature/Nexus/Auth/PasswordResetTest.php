<?php

namespace Tests\Feature\Nexus\Auth;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Notifications\Auth\ResetPassword as ResetPasswordNotification;
use Filament\Pages\Auth\PasswordReset\RequestPasswordReset;
use Filament\Pages\Auth\PasswordReset\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

use function Pest\Livewire\livewire;

test('the nexus request reset password link page can be rendered', function () {
    $response = $this->get(route('filament.nexus.auth.password-reset.request'));

    $response->assertSuccessful();
});

test('the nexus reset password page can be rendered', function () {
    Notification::fake();

    $user = User::factory()->create();

    $component = livewire(RequestPasswordReset::class)
        ->fillForm([
            'email' => $user->email,
        ])
        ->call('request');

    $component->assertHasNoErrors();

    Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
        $response = $this->get(Filament::getResetPasswordUrl($notification->token, $user));

        $response->assertSuccessful();

        return true;
    });
});

test('a nexus password can be reset with a valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    livewire(RequestPasswordReset::class)
        ->fillForm([
            'email' => $user->email,
        ])
        ->call('request');

    Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
        $component = livewire(ResetPassword::class, ['email' => $user->email, 'token' => $notification->token])
            ->fillForm([
                'password' => 'new-password',
                'passwordConfirmation' => 'new-password',
            ])
            ->call('resetPassword');

        $component
            ->assertHasNoFormErrors()
            ->assertRedirect(route('filament.nexus.auth.login'));

        expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();

        return true;
    });
});
