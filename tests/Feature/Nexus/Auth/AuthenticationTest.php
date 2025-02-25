<?php

use App\Models\User;
use Filament\Pages\Auth\Login;

use function Pest\Livewire\livewire;

test('the nexus login page can be rendered', function () {
    $this->get(route('filament.nexus.auth.login'))->assertOk();
});

test('that nexus users can authenticate', function () {
    /** @var \Illuminate\Contracts\Auth\Authenticatable */
    $user = User::factory()->create();

    livewire(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'password',
        ])
        ->call('authenticate')
        ->assertHasNoFormErrors()
        ->assertRedirectToRoute('filament.nexus.pages.dashboard');

    $this->assertAuthenticatedAs($user);
});

test('that nexus users cannot authenticate if login details are incorrect', function () {
    /** @var \Illuminate\Contracts\Auth\Authenticatable */
    $user = User::factory()->create();

    livewire(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'invalid_password',
        ])
        ->call('authenticate')
        ->assertHasFormErrors(['email'])
        ->assertNoRedirect();

    $this->assertGuest();
});

test('that nexus users can logout', function () {
    /** @var \Illuminate\Contracts\Auth\Authenticatable */
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('filament.nexus.auth.logout'))
        ->assertRedirectToRoute('filament.nexus.auth.login');

    $this->assertGuest();
});
