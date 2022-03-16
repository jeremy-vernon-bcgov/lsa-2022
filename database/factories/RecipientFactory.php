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
            'first_name'                => $this->faker->firstName(),
            'last_name'                 => $this->faker->lastName(),
            'idir'                      => $this->faker->userName(),
            'employee_number'           => $this->faker->unique()->randomNumber(6, true),
            'guid'                      => $this->faker->uuid(),
            'organization_id'           => $this->faker->numberBetween(1, 78),
            'branch_name'               => $this->faker->words(3, true),
            'government_email'          => $this->faker->companyEmail(),
            'milestones'                => $this->faker->randomElement([25, 30, 35, 40 ,45 ,50 ,55]),
            'qualifying_year'           => $this->faker->randomElement([2019, 2020, 2021, 2022]),
            'is_bcgeu_member'          => $this->faker->randomElement([true, false]),
            'retiring_this_year'        => $this->faker->randomElement([true, false]),
            'retirement_date'           => $this->faker->date(),
            'government_phone_number'   => $this->faker->phoneNumber(),
            'personal_email'            => $this->faker->email(),
            'personal_phone_number'     => $this->faker->phoneNumber(),
            'supervisor_first_name'     => $this->faker->firstName(),
            'supervisor_last_name'      => $this->faker->lastName(),
            'supervisor_email'          => $this->faker->companyEmail(),
            'survey_participation'      => $this->faker->randomElement([true, false]),
            'is_declared'               => $this->faker->randomElement([true, false]),
            'ceremony_opt_out'          => $this->faker->randomElement([true, false]),
            'admin_notes'               => $this->faker->sentence(),



        ];
    }
}
