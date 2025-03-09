<?php

namespace Database\Factories;

use App\Enums\AddressType;
use App\Enums\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        [$line1, $line2, $line3] = array_pad(explode("\n", fake()->streetAddress()), length: 3, value: null);

        return [
            'company' => rand(min: 0, max: 10) > 5 ? fake()->company() : null,
            'line_1' => $line1,
            'line_2' => $line2,
            'line_3' => $line3,
            'city' => fake()->city(),
            'region' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country_code' => Country::collect()->keys()->random(),
            'type' => fake()->randomElement(AddressType::values()),
        ];
    }
}
