<?php

namespace App\Enums\Concerns;

use Illuminate\Support\Collection;

trait EnumUtils
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

    /**
     * Returns a count of the total number the cases for this enum.
     */
    public static function count(): int
    {
        return count(self::cases());
    }

    /**
     * Returns the cases for this enum as an array.
     */
    public static function toArray(): array
    {
        return array_combine(self::names(), self::values());
    }

    /**
     * Returns the cases for this enum as a collection.
     */
    public static function collect(): Collection
    {
        return collect(self::toArray());
    }
}
