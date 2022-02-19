<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
            OrganizationSeeder::class,
            AccessibilityOptionSeeder::class,
            AwardSeeder::class,
            CommunitySeeder::class,
            DietaryRestrictionSeeder::class,
            PecsfRegionSeeder::class,
            PecsfCharitySeeder::class,
            VipCategorySeeder::class,
            CeremonySeeder::class,

            //RecipientSeeder::class,
            AwardOptionSeeder::class,
            //AwardSelectionSeeder::class,
            //AttendeesSeeder::class,

        ]);
    }
}
