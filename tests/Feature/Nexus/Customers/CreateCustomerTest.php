<?php

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\CustomerResource\Pages\CreateCustomer;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Livewire\livewire;

test('the page renders', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(CustomerResource::getUrl('create'))
        ->assertOk()
        ->assertSee('Create Customer')
        ->assertSee('Set Password');
});

test('a new customer can be created', function () {
    livewire(CreateCustomer::class)
        ->fillForm([
            'first_name' => $firstName = 'Test',
            'last_name' => $lastName = 'Customer',
            'email' => $email = 'test.customer@example.com',
            'active' => $active = true,
            'password' => $password = 'password',
            'password_confirmation' => $password,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Customer::class, [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'active' => $active,
    ]);

    $customer = Customer::where('email', $email)->first();

    expect(Hash::check('password', $customer->password))->toBeTrue();
});

test('a customer is not created if validation fails', function () {
    livewire(CreateCustomer::class)
        ->fillForm([
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'active' => true,
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->call('create')
        ->assertHasFormErrors(['email' => 'required']);

    $this->assertDatabaseCount(Customer::class, 0);
});
