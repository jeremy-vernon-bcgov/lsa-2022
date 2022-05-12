<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AccommodationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*Accessibility*/
        // DB::table('accommodations')->insert(['short_name' => 'Mobility', 'full_name' => '', 'description' => 'Consideration for a mobility aid (e.g. cane, walker or wheelchair)', 'type' => 'accessibility', 'created_at' => date("Y-m-d H:i:s")]);
        // DB::table('accommodations')->insert(['short_name' => 'Parking', 'full_name' => 'Accessible Parking', 'description' => '', 'type' => 'accessibility', 'created_at' => date("Y-m-d H:i:s")]);
        // DB::table('accommodations')->insert(['short_name' => 'Seating', 'full_name' => 'Reserved Seating', 'description' => '', 'type' => 'accessibility', 'created_at' => date("Y-m-d H:i:s")]);
        // DB::table('accommodations')->insert(['short_name' => 'Buffet', 'full_name' => 'Assistance at the Buffet', 'description' => '', 'type' => 'accessibility', 'created_at' => date("Y-m-d H:i:s")]);
        // DB::table('accommodations')->insert(['short_name' => 'CART', 'full_name' => 'Communication Access Realtime Translation (CART) services', 'description' => '', 'type' => 'accessibility', 'created_at' => date("Y-m-d H:i:s")]);
        // DB::table('accommodations')->insert(['short_name' => 'ASL', 'full_name' => 'American Sign Language (ASL) interpreter', 'description' => '', 'type' => 'accessibility', 'created_at' => date("Y-m-d H:i:s")]);
        // DB::table('accommodations')->insert(['short_name' => 'Large-print', 'full_name' => 'Large-print program', 'description' => 'Large print Commemorative Program', 'type' => 'accessibility', 'created_at' => date("Y-m-d H:i:s")]);
        // DB::table('accommodations')->insert(['short_name' => 'Dog', 'full_name' => 'Access for guide/service dog', 'description' => 'accommodation for a guide or service dog', 'type' => 'accessibility', 'created_at' => date("Y-m-d H:i:s")]);
        DB::table('accommodations')->insert(['short_name' => 'Accessibility', 'full_name' => 'Accessibility', 'description' => 'If you have an accessibility need not included here, please choose this option and the LSA team will contact you for details.', 'type' => 'accessibility', 'created_at' => date("Y-m-d H:i:s")]);

        /*Dietary*/
        DB::table('accommodations')->insert(['short_name' => 'Dairy-free', 'full_name' => 'Dairy-free', 'description' => '', 'type' => 'dietary', 'created_at' => date("Y-m-d H:i:s")]);
        DB::table('accommodations')->insert(['short_name' => 'Gluten-free', 'full_name' => 'Gluten-free', 'description' => '', 'type' => 'dietary', 'created_at' => date("Y-m-d H:i:s")]);
        DB::table('accommodations')->insert(['short_name' => 'Sugar-free', 'full_name' => 'Sugar-free', 'description' => '', 'type' => 'dietary', 'created_at' => date("Y-m-d H:i:s")]);
        DB::table('accommodations')->insert(['short_name' => 'Shellfish-free', 'full_name' => 'Shellfish-free', 'description' => '', 'type' => 'dietary', 'created_at' => date("Y-m-d H:i:s")]);
        DB::table('accommodations')->insert(['short_name' => 'Vegetarian', 'full_name' => 'Vegetarian', 'description' => '', 'type' => 'dietary', 'created_at' => date("Y-m-d H:i:s")]);
        DB::table('accommodations')->insert(['short_name' => 'Vegan', 'full_name' => 'Vegan', 'description' => '', 'type' => 'dietary', 'created_at' => date("Y-m-d H:i:s")]);
        DB::table('accommodations')->insert(['short_name' => 'Peanut-free', 'full_name' => 'Peanut-free', 'description' => '', 'type' => 'dietary', 'created_at' => date("Y-m-d H:i:s")]);
        DB::table('accommodations')->insert(['short_name' => 'Nut-free', 'full_name' => 'Nut-free', 'description' => '', 'type' => 'dietary', 'created_at' => date("Y-m-d H:i:s")]);
        DB::table('accommodations')->insert(['short_name' => 'Other-Dietary', 'full_name' => 'Other, LSA team will contact you.', 'description' => '', 'type' => 'dietary', 'created_at' => date("Y-m-d H:i:s")]);

    }


}
