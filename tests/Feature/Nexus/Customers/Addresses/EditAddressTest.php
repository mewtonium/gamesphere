<?php

use App\Enums\AddressType;
use App\Filament\Resources\CustomerResource\Pages\EditCustomer;
use App\Filament\Resources\CustomerResource\RelationManagers\AddressesRelationManager;
use App\Models\Customer;
use Filament\Tables\Actions\EditAction;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->customer = Customer::factory()->hasAddresses(1)->create();

    $this->component = livewire(
        name: AddressesRelationManager::class,
        params: [
            'ownerRecord' => $this->customer,
            'pageClass' => EditCustomer::class,
        ],
    );
});

test('the edit address modal displays and shows the correct address details', function () {
    $this->component->mountTableAction(EditAction::class, $address = $this->customer->addresses->first());

    $this->component
        ->assertSee("Edit {$address->line_1}")
        ->assertSee($address->line_1)
        ->assertSee($address->line_2)
        ->assertSee($address->line_3)
        ->assertSee($address->city)
        ->assertSee($address->region)
        ->assertSee($address->postal_code)
        ->assertSee($address->country_code)
        ->assertSee($address->type);
});

test('an address can be updated', function () {
    $this->component
        ->mountTableAction(EditAction::class, $address = $this->customer->addresses->first())
        ->setTableActionData([
            'company' => $company = 'Test Company',
            'line_1' => $line1 = 'Test Line 1',
            'line_2' => $line2 = 'Test Line 2',
            'line_3' => $line3 = 'Test Line 3',
            'city' => $city = 'Test City',
            'region' => $region = 'Test Region',
            'postal_code' => $postalCode = 'TE1 1ST',
            'country_code' => $countryCode = 'GB',
            'type' => ($type = AddressType::BILLING)->value,
        ])
        ->callMountedTableAction();

    $address->refresh();

    $this->component->assertHasNoTableActionErrors();
    expect($address->company)->toBe($company);
    expect($address->line_1)->toBe($line1);
    expect($address->line_2)->toBe($line2);
    expect($address->line_3)->toBe($line3);
    expect($address->city)->toBe($city);
    expect($address->region)->toBe($region);
    expect($address->postal_code)->toBe($postalCode);
    expect($address->country_code)->toBe($countryCode);
    expect($address->type)->toBe($type);
});

test('an address is not updated if the form is invalid', function () {
    $this->component
        ->mountTableAction(EditAction::class, $address = $this->customer->addresses->first())
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

    $this->component->assertHasTableActionErrors(['postal_code' => 'required']);
    $this->assertNotNull($address->fresh()->postal_code);
});
