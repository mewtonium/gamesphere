<?php

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\CustomerResource\Pages\EditCustomer;
use App\Models\Customer;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Hash;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->customer = Customer::factory()->create();

    $this->component = livewire(
        name: EditCustomer::class,
        params: ['record' => $this->customer->getRouteKey()],
    );
});

test('the page renders', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user, 'nexus')
        ->get(CustomerResource::getUrl('edit', ['record' => $this->customer]));

    $response
        ->assertSuccessful()
        ->assertSee('Edit Customer')
        ->assertSee('Change Password');
});

test('the correct customer data is retrieved', function () {
    $this->component->assertFormSet([
        'first_name' => $this->customer->first_name,
        'last_name' => $this->customer->last_name,
        'email' => $this->customer->email,
        'active' => $this->customer->active,
        'password' => '',
    ]);
});

test('a customer can be updated', function () {
    $this->component
        ->fillForm([
            'first_name' => $firstName = 'Test',
            'last_name' => $lastName = 'Customer',
            'email' => $email = 'test.customer@example.com',
            'active' => $active = false,
            'password' => $password = 'new_password',
            'password_confirmation' => $password,
        ])
        ->call('save');

    $this->component->assertHasNoFormErrors();

    $this->customer->refresh();

    expect($firstName)->toBe($this->customer->first_name);
    expect($lastName)->toBe($this->customer->last_name);
    expect($email)->toBe($this->customer->email);
    expect($active)->toBe($this->customer->active);
    expect(Hash::check('new_password', $this->customer->password))->toBeTrue();
});

test('a customer is not updated if validation fails', function () {
    $this->component
        ->fillForm([
            'first_name' => 'Test',
            'last_name' => 'Example',
            'active' => true,
            'email' => null,
        ])
        ->call('save');

    $this->component->assertHasFormErrors(['email' => 'required']);
    expect($this->customer->fresh()->email)->not->toBeNull();
});

test('the password is not changed if left empty', function () {
    $this->component
        ->fillForm([
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'email' => 'test.customer@example.com',
            'active' => true,
        ])
        ->call('save');

    expect(Hash::check('password', $this->customer->fresh()->password))->toBeTrue();
});

test('a customer can be deleted', function () {
    $this->component->callAction(DeleteAction::class);

    $this->assertModelMissing($this->customer);
});
