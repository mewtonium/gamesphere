<?php

namespace Tests\Feature\Nexus\Customers\Addresses;

use App\Filament\Resources\CustomerResource\Pages\EditCustomer;
use App\Filament\Resources\CustomerResource\RelationManagers\AddressesRelationManager;
use App\Models\Customer;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->customer = Customer::factory()
        ->hasAddresses(20)
        ->create();

    $this->component = livewire(
        name: AddressesRelationManager::class,
        params: [
            'ownerRecord' => $this->customer,
            'pageClass' => EditCustomer::class,
        ],
    );
});

test('the addresses relation manager renders', function () {
    $this->component->assertSuccessful();
});

test('that addresses are listed', function () {
    $this->component->set('tableRecordsPerPage', 20);

    $this->component->assertCanSeeTableRecords($this->customer->addresses);
});

test('the next page of addresses is viewable', function () {
    $this->component->call('gotoPage', 3, 'addressesRelationManagerPage');

    $this->component
        ->assertCanSeeTableRecords($this->customer->addresses->slice(offset: 10, length: 5))
        ->assertCanNotSeeTableRecords($this->customer->addresses->slice(offset: 15, length: 5));
});

test('a single address can be deleted', function () {
    $this->component->callTableAction(DeleteAction::class, $address = $this->customer->addresses->first());

    $this->component
        ->assertHasNoTableActionErrors()
        ->assertCanNotSeeTableRecords([$address]);

    $this->assertModelMissing($address);
});

test('that multiple addresses can be deleted in bulk', function () {
    $this->component->callTableBulkAction(DeleteBulkAction::class, $deleted = $this->customer->addresses->take(3));

    foreach ($deleted as $address) {
        $this->assertModelMissing($address);
    }

    $this->component
        ->assertHasNoTableActionErrors()
        ->assertCanNotSeeTableRecords($deleted);

    $this->assertDatabaseCount('addresses', 17);
});
