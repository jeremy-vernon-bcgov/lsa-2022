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
        Schema::table('ceremonies', function (Blueprint $table) {
          $table->string('location_name');
          $table->unsignedBigInteger('location_address_id')->nullable();
          $table->foreign('location_address_id')->references('id')->on('addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ceremonies', function (Blueprint $table) {
          $table->dropColumn('location_name');
          $table->dropColumn('location_address_id');
        });
    }
};
