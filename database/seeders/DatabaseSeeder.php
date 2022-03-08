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

            AwardSeeder::class,
            CommunitySeeder::class,
            PecsfRegionSeeder::class,
            PecsfCharitySeeder::class,
            CeremonySeeder::class,


            //UsersSeeder::class
            //RecipientSeeder::class,
            //AwardSelectionSeeder::class,
            //AttendeesSeeder::class,

        ]);
    }
}
