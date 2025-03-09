<?php

namespace App\Enums\Concerns;

trait InteractsWithEnums
{
    /**
     * Returns a list of case names for this enum.
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Returns a list of case values for this enum.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
