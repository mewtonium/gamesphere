<?php

namespace App\Enums;

use App\Enums\Concerns\EnumUtils;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AddressType: string implements HasColor, HasLabel
{
    use EnumUtils;

    case BILLING = 'billing';
    case SHIPPING = 'shipping';

    /**
     * Maps labels to each address type for use in form dropdowns.
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::BILLING => 'Billing',
            self::SHIPPING => 'Shipping',
            default => null,
        };
    }

    /**
     * Maps colours to each address type for use in table view.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::BILLING => 'success',
            self::SHIPPING => 'warning',
            default => 'info',
        };
    }
}
