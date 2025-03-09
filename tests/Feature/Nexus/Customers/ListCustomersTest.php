<?php

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\CustomerResource\Pages\ListCustomers;
use App\Models\Customer;
use App\Models\User;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->customers = Customer::factory()
        ->count(20)
        ->sequence(
            ['active' => true],
            ['active' => false],
        )
        ->create();

    $this->component = livewire(ListCustomers::class);
});

test('the page renders', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'nexus')->get(CustomerResource::getUrl('index'));

    $response->assertSuccessful();
});

test('that a list of customers is displayed', function () {
    $this->component->assertCanSeeTableRecords($this->customers->take(10));
});

test('that the next page of customers is displayed', function () {
    $this->component->call('gotoPage', 2);

    $this->component
        ->assertCanSeeTableRecords($this->customers->slice(offset: 10, length: 10))
        ->assertCanNotSeeTableRecords($this->customers->slice(offset: 0, length: 10));
});

test('searching customers by searchable columns', function () {
    $customer = $this->customers->first();

    // Email
    $this->component
        ->searchTable($customer->email)
        ->assertCanSeeTableRecords($this->customers->where('email', $customer->email))
        ->assertCanNotSeeTableRecords($this->customers->where('email', '<>', $customer->email));

    // First name
    $this->component
        ->searchTable($customer->first_name)
        ->assertCanSeeTableRecords($this->customers->where('first_name', $customer->first_name))
        ->assertCanNotSeeTableRecords($this->customers->where('first_name', '<>', $customer->first_name));

    // Last name
    $this->component
        ->searchTable($customer->first_name)
        ->assertCanSeeTableRecords($this->customers->where('last_name', $customer->last_name))
        ->assertCanNotSeeTableRecords($this->customers->where('last_name', '<>', $customer->last_name));
});

test('sorting customers by sortable columns', function () {
    // Email
    $this->component
        ->sortTable('email')
        ->assertCanSeeTableRecords($this->customers->sortBy('email')->take(10), inOrder: true)
        ->sortTable('email', 'desc')
        ->assertCanSeeTableRecords($this->customers->sortByDesc('email')->take(10), inOrder: true);

    // First name
    $this->component
        ->sortTable('first_name')
        ->assertCanSeeTableRecords($this->customers->sortBy('first_name')->take(10), inOrder: true)
        ->sortTable('first_name', 'desc')
        ->assertCanSeeTableRecords($this->customers->sortByDesc('first_name')->take(10), inOrder: true);

    // Last name
    $this->component
        ->sortTable('last_name')
        ->assertCanSeeTableRecords($this->customers->sortBy('last_name')->take(10), inOrder: true)
        ->sortTable('last_name', 'desc')
        ->assertCanSeeTableRecords($this->customers->sortByDesc('last_name')->take(10), inOrder: true);
});

test('customers can be filtered by active status and the filter then removed', function () {
    $this->component
        ->filterTable('is_active')
        ->assertCanSeeTableRecords($this->customers->where('active', true)->take(10))
        ->assertCanNotSeeTableRecords($this->customers->where('active', false));

    $this->component
        ->removeTableFilter('is_active')
        ->assertCanSeeTableRecords($this->customers->take(10));
});

test('a single customer can be deleted', function () {
    $this->component->callTableAction(DeleteAction::class, $customer = $this->customers->first());

    $this->component
        ->assertHasNoTableActionErrors()
        ->assertCanNotSeeTableRecords([$customer]);

    $this->assertModelMissing($customer);
});

test('customers can be deleted in bulk', function () {
    $this->component->callTableBulkAction(DeleteBulkAction::class, $deleted = $this->customers->take(3));

    foreach ($deleted as $customer) {
        $this->assertModelMissing($customer);
    }

    $this->component
        ->assertHasNoTableActionErrors()
        ->assertCanNotSeeTableRecords($deleted);

    $this->assertDatabaseCount('customers', 17);
});
