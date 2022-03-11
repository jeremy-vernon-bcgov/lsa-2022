<?php

namespace Database\Factories;

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
    public function definition()
    {
        return [
            'prefix' => $this->faker->randomElement(['', '', '', 'Office 11','Suite 221b']),
            'pobox' => $this->faker->randomElement(['','','' ,'PO BOX 3333']),
            'street_address' => $this->faker->streetAddress(),
            'postal_code' => $this->faker->randomElement(['V3V 6A6', 'V7Y 8T3', 'V1T 6U3', 'A3W 9R5']),
            'community' => $this->faker->city()
        ];
    }
}
