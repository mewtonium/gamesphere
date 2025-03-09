<?php

namespace Tests\Feature\Nexus\Customers\Addresses;

use App\Filament\Resources\CustomerResource\Pages\EditCustomer;
use App\Filament\Resources\CustomerResource\RelationManagers\AddressesRelationManager;
use App\Models\Customer;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;

use function Pest\Livewire\livewire;

test('the addresses relation manager renders', function () {
    $customer = Customer::factory()
        ->hasAddresses(5)
        ->create();

    $component = livewire(
        name: AddressesRelationManager::class,
        params: [
            'ownerRecord' => $customer,
            'pageClass' => EditCustomer::class,
        ],
    );

    $component->assertSuccessful();
});

test('that addresses are listed', function () {
    $customer = Customer::factory()
        ->hasAddresses(5)
        ->create();

    $component = livewire(
        name: AddressesRelationManager::class,
        params: [
            'ownerRecord' => $customer,
            'pageClass' => EditCustomer::class,
        ],
    );

    $component->assertCanSeeTableRecords($customer->addresses);
});

test('the next page of addresses is viewable', function () {
    $customer = Customer::factory()
        ->hasAddresses(20)
        ->create();

    $component = livewire(
        name: AddressesRelationManager::class,
        params: [
            'ownerRecord' => $customer,
            'pageClass' => EditCustomer::class,
        ],
    );

    $component->call('gotoPage', 3, 'addressesRelationManagerPage');

    $component->assertCanSeeTableRecords($customer->addresses->slice(offset: 10, length: 5));
    $component->assertCanNotSeeTableRecords($customer->addresses->slice(offset: 15, length: 5));
});

test('a single address can be deleted', function () {
    $customer = Customer::factory()
        ->hasAddresses(5)
        ->create();

    $component = livewire(
        name: AddressesRelationManager::class,
        params: [
            'ownerRecord' => $customer,
            'pageClass' => EditCustomer::class,
        ],
    );

    $component->callTableAction(DeleteAction::class, $address = $customer->addresses->first());

    $component->assertHasNoTableActionErrors();
    $component->assertCanNotSeeTableRecords([$address]);
    $this->assertModelMissing($address);
});

test('that multiple addresses can be deleted in bulk', function () {
    $customer = Customer::factory()
        ->hasAddresses(5)
        ->create();

    $component = livewire(
        name: AddressesRelationManager::class,
        params: [
            'ownerRecord' => $customer,
            'pageClass' => EditCustomer::class,
        ],
    );

    $component->callTableBulkAction(DeleteBulkAction::class, $deleted = $customer->addresses->take(3));

    foreach ($deleted as $address) {
        $this->assertModelMissing($address);
    }

    $component->assertHasNoTableActionErrors();
    $component->assertCanNotSeeTableRecords($deleted);
    $this->assertDatabaseCount('addresses', 2);
});
