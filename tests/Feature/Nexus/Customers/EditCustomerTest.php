<?php

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\CustomerResource\Pages\EditCustomer;
use App\Models\Customer;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Hash;

use function Pest\Livewire\livewire;

test('the page renders', function () {
    $user = User::factory()->create();

    $customer = Customer::factory()->create();

    $this->actingAs($user)
        ->get(CustomerResource::getUrl('edit', ['record' => $customer]))
        ->assertOk()
        ->assertSee('Edit Customer')
        ->assertSee('Change Password');
});

test('the correct customer data is retrieved', function () {
    $customer = Customer::factory()->create();

    livewire(EditCustomer::class, ['record' => $customer->getRouteKey()])
        ->assertFormSet([
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'email' => $customer->email,
            'active' => $customer->active,
            'password' => '',
        ]);
});

test('a customer can be updated', function () {
    $customer = Customer::factory()->create();

    livewire(EditCustomer::class, ['record' => $customer->getRouteKey()])
        ->fillForm([
            'first_name' => $firstName = 'Test',
            'last_name' => $lastName = 'Customer',
            'email' => $email = 'test.customer@example.com',
            'active' => $active = false,
            'password' => $password = 'new_password',
            'password_confirmation' => $password,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $customer = $customer->fresh();

    expect($firstName)->toBe($customer->first_name);
    expect($lastName)->toBe($customer->last_name);
    expect($email)->toBe($customer->email);
    expect($active)->toBe($customer->active);

    expect(Hash::check('new_password', $customer->password))->toBeTrue();
});

test('a customer is not updated if validation fails', function () {
    $customer = Customer::factory()->create();

    livewire(EditCustomer::class, ['record' => $customer->getRouteKey()])
        ->fillForm([
            'first_name' => 'Test',
            'last_name' => 'Example',
            'active' => true,
            'email' => null,
        ])
        ->call('save')
        ->assertHasFormErrors(['email' => 'required']);

    expect($customer->fresh()->email)->not->toBeNull();
});

test('the password is not changed if left empty', function () {
    $customer = Customer::factory()->create();

    livewire(EditCustomer::class, ['record' => $customer->getRouteKey()])
        ->fillForm([
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'email' => 'test.customer@example.com',
            'active' => true,
        ])
        ->call('save');

    expect(Hash::check('password', $customer->fresh()->password))->toBeTrue();
});

test('a customer can be deleted', function () {
    $customer = Customer::factory()->create();

    livewire(EditCustomer::class, ['record' => $customer->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($customer);
});
