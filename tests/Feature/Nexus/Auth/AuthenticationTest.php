<?php

use App\Models\User;
use Filament\Pages\Auth\Login;

use function Pest\Livewire\livewire;

test('the nexus login page can be rendered', function () {
    $response = $this->get(route('filament.nexus.auth.login'));

    $response->assertSuccessful();
});

test('that nexus users can authenticate', function () {
    $user = User::factory()->create();

    $component = livewire(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'password',
        ])
        ->call('authenticate');

    $component
        ->assertHasNoFormErrors()
        ->assertRedirectToRoute('filament.nexus.pages.dashboard');

    $this->assertAuthenticatedAs($user, 'nexus');
});

test('that nexus users cannot authenticate if login details are incorrect', function () {
    /** @var \Illuminate\Contracts\Auth\Authenticatable */
    $user = User::factory()->create();

    $component = livewire(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'invalid_password',
        ])
        ->call('authenticate');

    $component->assertHasFormErrors(['email'])->assertNoRedirect();
    $this->assertGuest('nexus');
});

test('that nexus users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'nexus')->post(route('filament.nexus.auth.logout'));

    $response->assertRedirectToRoute('filament.nexus.auth.login');
    $this->assertGuest('nexus');
});
