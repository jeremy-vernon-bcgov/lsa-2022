<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CeremonySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ceremonies')->insert(['id' => 1, 'scheduled_datetime' => '2022-01-11 19:00:00']);
        DB::table('ceremonies')->insert(['id' => 2, 'scheduled_datetime' => '2022-01-12 19:00:00']);
        DB::table('ceremonies')->insert(['id' => 3, 'scheduled_datetime' => '2022-01-13 19:00:00']);
        DB::table('ceremonies')->insert(['id' => 4, 'scheduled_datetime' => '2022-01-18 19:00:00']);
        DB::table('ceremonies')->insert(['id' => 5, 'scheduled_datetime' => '2022-01-19 19:00:00']);
        DB::table('ceremonies')->insert(['id' => 6, 'scheduled_datetime' => '2022-01-20 19:00:00']);
        DB::table('ceremonies')->insert(['id' => 7, 'scheduled_datetime' => '2022-01-25 19:00:00']);
        DB::table('ceremonies')->insert(['id' => 8, 'scheduled_datetime' => '2022-01-26 19:00:00']);
        DB::table('ceremonies')->insert(['id' => 9, 'scheduled_datetime' => '2022-01-27 19:00:00']);
        DB::table('ceremonies')->insert(['id' => 10, 'scheduled_datetime' => '2022-02-01 19:00:00']);
        DB::table('ceremonies')->insert(['id' => 11, 'scheduled_datetime' => '2022-02-02 19:00:00']);
        DB::table('ceremonies')->insert(['id' => 12, 'scheduled_datetime' => '2022-02-03 19:00:00']);
        DB::table('ceremonies')->insert(['id' => 13, 'scheduled_datetime' => '2022-02-08 19:00:00']);
        DB::table('ceremonies')->insert(['id' => 14, 'scheduled_datetime' => '2022-02-09 19:00:00']);
        DB::table('ceremonies')->insert(['id' => 15, 'scheduled_datetime' => '2022-02-10 19:00:00']);


    }
}
