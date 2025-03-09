<?php

namespace App\Models;

use App\Enums\Country as CountryEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Sushi\Sushi;

class Country extends Model
{
    use Sushi;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'code';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Define the table data for Sushi.
     *
     * @return array<string, string>
     */
    public function getSchema()
    {
        return [
            'code' => 'string',
            'name' => 'string',
        ];
    }

    /**
     * Define the row data for Sushi.
     *
     * @return array<int, array<string, string>>
     */
    public function getRows()
    {
        return collect(CountryEnum::cases())
            ->map(fn (CountryEnum $country) => [
                'code' => $country->name,
                'name' => $country->value,
            ])
            ->all();
    }

    /**
     * Define actions to run after the Sushi has migrated the schema.
     *
     * @return void
     */
    protected function afterMigrate(Blueprint $table)
    {
        $table->primary('code');
    }

    /**
     * Determine if Sushi should cache the row data.
     *
     * @return bool
     */
    protected function sushiShouldCache()
    {
        return true;
    }

    /**
     * Define the file Sushi should reference to determine if row data needs recaching.
     *
     * @return string
     */
    protected function sushiCacheReferencePath()
    {
        return (new \ReflectionClass(CountryEnum::class))->getFileName();
    }
}
