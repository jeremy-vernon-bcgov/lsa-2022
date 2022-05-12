<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      DB::statement("ALTER TABLE attendees MODIFY COLUMN status ENUM('assigned','invited','attending','declined','waitlisted', 'expired') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE attendees MODIFY COLUMN status ENUM('', 'assigned','invited','attending','declined','waitlisted')");
    }
};
