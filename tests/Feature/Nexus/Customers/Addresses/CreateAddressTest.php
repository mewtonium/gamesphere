<?php

use App\Enums\AddressType;
use App\Filament\Resources\CustomerResource\Pages\EditCustomer;
use App\Filament\Resources\CustomerResource\RelationManagers\AddressesRelationManager;
use App\Models\Address;
use App\Models\Customer;
use Filament\Tables\Actions\CreateAction;

use function Pest\Livewire\livewire;

test('the create address modal displays', function () {
    $customer = Customer::factory()->create();

    $component = livewire(
        name: AddressesRelationManager::class,
        params: [
            'ownerRecord' => $customer,
            'pageClass' => EditCustomer::class,
        ],
    );

    $component->mountTableAction(CreateAction::class);

    $component->assertSee('Create address');
});

test('a new address can be created', function () {
    $customer = Customer::factory()->create();

    $component = livewire(
        name: AddressesRelationManager::class,
        params: [
            'ownerRecord' => $customer,
            'pageClass' => EditCustomer::class,
        ],
    );

    $component->mountTableAction(CreateAction::class)
        ->setTableActionData([
            'company' => $company = 'Test Company',
            'line_1' => $line1 = 'Test Line 1',
            'line_2' => $line2 = 'Test Line 2',
            'line_3' => $line3 = 'Test Line 3',
            'city' => $city = 'Test City',
            'region' => $region = 'Test Region',
            'postal_code' => $postalCode = 'TE1 1ST',
            'country_code' => $countryCode = 'GB',
            'type' => $type = AddressType::BILLING->value,
        ])
        ->callMountedTableAction();

    $component->assertHasNoTableActionErrors();

    $this->assertDatabaseHas(Address::class, [
        'company' => $company,
        'line_1' => $line1,
        'line_2' => $line2,
        'line_3' => $line3,
        'city' => $city,
        'region' => $region,
        'postal_code' => $postalCode,
        'country_code' => $countryCode,
        'type' => $type,
    ]);
});

test('an address is not created if the form is invalid', function () {
    $customer = Customer::factory()->create();

    $component = livewire(
        name: AddressesRelationManager::class,
        params: [
            'ownerRecord' => $customer,
            'pageClass' => EditCustomer::class,
        ],
    );

    $component->mountTableAction(CreateAction::class)
        ->setTableActionData([
            'company' => 'Test Company',
            'line_1' => 'Test Line 1',
            'line_2' => 'Test Line 2',
            'line_3' => 'Test Line 3',
            'city' => 'Test City',
            'region' => 'Test Region',
            'postal_code' => null,
            'country_code' => 'GB',
            'type' => AddressType::BILLING->value,
        ])
        ->callMountedTableAction();

    $component->assertHasTableActionErrors(['postal_code' => 'required']);
    $this->assertDatabaseCount(Address::class, 0);
});
