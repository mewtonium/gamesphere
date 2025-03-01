<?php

use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\CustomerResource\Pages\ListCustomers;
use App\Models\Customer;
use App\Models\User;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;

use function Pest\Livewire\livewire;

test('the page renders', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'nexus')->get(CustomerResource::getUrl('index'));

    $response->assertSuccessful();
});

test('that a list of customers is displayed', function () {
    $customers = Customer::factory()->count(10)->create();

    $component = livewire(ListCustomers::class);

    $component
        ->assertCanSeeTableRecords($customers)
        ->assertCountTableRecords(10);
});

test('that the next page of customers is displayed', function () {
    $customers = Customer::factory()->count(20)->create();

    $component = livewire(ListCustomers::class)->call('gotoPage', 2);

    $component->assertCanSeeTableRecords($customers->slice(offset: 10, length: 10));
});

test('searching customers by searchable columns', function () {
    $customers = Customer::factory()->count(10)->create();
    $customer = $customers->first();

    // Email
    livewire(ListCustomers::class)
        ->searchTable($customer->email)
        ->assertCanSeeTableRecords($customers->where('email', $customer->email))
        ->assertCanNotSeeTableRecords($customers->where('email', '<>', $customer->email));

    // First name
    livewire(ListCustomers::class)
        ->searchTable($customer->first_name)
        ->assertCanSeeTableRecords($customers->where('first_name', $customer->first_name))
        ->assertCanNotSeeTableRecords($customers->where('first_name', '<>', $customer->first_name));

    // Last name
    livewire(ListCustomers::class)
        ->searchTable($customer->first_name)
        ->assertCanSeeTableRecords($customers->where('last_name', $customer->last_name))
        ->assertCanNotSeeTableRecords($customers->where('last_name', '<>', $customer->last_name));
});

test('sorting customers by sortable columns', function () {
    $customers = Customer::factory()->count(10)->create();

    // Email
    livewire(ListCustomers::class)
        ->sortTable('email')
        ->assertCanSeeTableRecords($customers->sortBy('email'), inOrder: true)
        ->sortTable('email', 'desc')
        ->assertCanSeeTableRecords($customers->sortByDesc('email'), inOrder: true);

    // First name
    livewire(ListCustomers::class)
        ->sortTable('first_name')
        ->assertCanSeeTableRecords($customers->sortBy('first_name'), inOrder: true)
        ->sortTable('first_name', 'desc')
        ->assertCanSeeTableRecords($customers->sortByDesc('first_name'), inOrder: true);

    // Last name
    livewire(ListCustomers::class)
        ->sortTable('last_name')
        ->assertCanSeeTableRecords($customers->sortBy('last_name'), inOrder: true)
        ->sortTable('last_name', 'desc')
        ->assertCanSeeTableRecords($customers->sortByDesc('last_name'), inOrder: true);
});

test('customers can be filtered by active status and the filter then removed', function () {
    $customers = Customer::factory()
        ->count(10)
        ->sequence(
            ['active' => true],
            ['active' => false],
        )
        ->create();

    $component = livewire(ListCustomers::class);

    $component
        ->filterTable('is_active')
        ->assertCanSeeTableRecords($customers->where('active', true))
        ->assertCanNotSeeTableRecords($customers->where('active', false));

    $component
        ->removeTableFilter('is_active')
        ->assertCanSeeTableRecords($customers);
});

test('a single customer can be deleted', function () {
    $customer = Customer::factory()->create();

    livewire(ListCustomers::class)
        ->callTableAction(DeleteAction::class, $customer);

    $this->assertModelMissing($customer);
});

test('customers can be deleted in bulk', function () {
    $customers = Customer::factory()->count(5)->create();

    livewire(ListCustomers::class)
        ->callTableBulkAction(DeleteBulkAction::class, $deleted = $customers->take(2));

    foreach ($deleted as $customer) {
        $this->assertModelMissing($customer);
    }

    $this->assertDatabaseCount('customers', 3);
});
