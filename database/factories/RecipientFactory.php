<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipient>
 */
class RecipientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
          'first_name' => $this->faker->name(),
          'last_name' => $this->faker->name(),
          'idir' => $this->faker->unique(),
          'guid' => $this->faker->unique(),
          'personal_email' => $this->faker->unique()->safeEmail(),
          'organization_id' => 22,
        ];
    }
}
