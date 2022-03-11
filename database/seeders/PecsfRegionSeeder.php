<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class PecsfRegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pecsf_regions')->insert(['id' => 1,'name' => "Alberni-Clayoquot"]);
        DB::table('pecsf_regions')->insert(['id' => 2,'name' => "Bulkley-Nechako"]);
        DB::table('pecsf_regions')->insert(['id' => 3,'name' => "Cariboo"]);
        DB::table('pecsf_regions')->insert(['id' => 4,'name' => "Central Coast"]);
        DB::table('pecsf_regions')->insert(['id' => 5,'name' => "Fraser Valley"]);
        DB::table('pecsf_regions')->insert(['id' => 6,'name' => "Central Kootenay"]);
        DB::table('pecsf_regions')->insert(['id' => 7,'name' => "Central Okanagan"]);
        DB::table('pecsf_regions')->insert(['id' => 8,'name' => "Columbia-Shuswap"]);
        DB::table('pecsf_regions')->insert(['id' => 9,'name' => "Comox Valley"]);
        DB::table('pecsf_regions')->insert(['id' => 10,'name' => "Cowichan Valley"]);
        DB::table('pecsf_regions')->insert(['id' => 11,'name' => "East Kootenay"]);
        DB::table('pecsf_regions')->insert(['id' => 12,'name' => "Northern Rockies"]);
        DB::table('pecsf_regions')->insert(['id' => 13,'name' => "Fraser-Fort George"]);
        DB::table('pecsf_regions')->insert(['id' => 14,'name' => "Kitimat-Stikine"]);
        DB::table('pecsf_regions')->insert(['id' => 15,'name' => "Kootenay Boundary"]);
        DB::table('pecsf_regions')->insert(['id' => 16,'name' => "Mount Waddington"]);
        DB::table('pecsf_regions')->insert(['id' => 17,'name' => "Nanaimo"]);
        DB::table('pecsf_regions')->insert(['id' => 18,'name' => "North Okanagan"]);
        DB::table('pecsf_regions')->insert(['id' => 19,'name' => "Okanagan-Similkameen"]);
        DB::table('pecsf_regions')->insert(['id' => 20,'name' => "Peace River"]);
        DB::table('pecsf_regions')->insert(['id' => 21,'name' => "qathet"]);
        DB::table('pecsf_regions')->insert(['id' => 22,'name' => "North Coast"]);
        DB::table('pecsf_regions')->insert(['id' => 23,'name' => "Squamish-Lillooet"]);
        DB::table('pecsf_regions')->insert(['id' => 24,'name' => "Stikine"]);
        DB::table('pecsf_regions')->insert(['id' => 25,'name' => "Sunshine Coast"]);
        DB::table('pecsf_regions')->insert(['id' => 26,'name' => "Thompson-Nicola"]);
        DB::table('pecsf_regions')->insert(['id' => 27,'name' => "Metro Vancouver"]);
        DB::table('pecsf_regions')->insert(['id' => 28,'name' => "Capital / Victoria"]);
        DB::table('pecsf_regions')->insert(['id' => 30,'name' => "Strathcona"]);

    }
}
