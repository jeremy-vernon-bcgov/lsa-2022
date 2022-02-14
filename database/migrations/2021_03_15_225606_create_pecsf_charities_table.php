<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePecsfCharitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pecsf_charities', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('pecsf_region_id');
            $table->string('name');
            $table->string('vendor_code', 10);

            $table->foreign('pecsf_region_id')->references('id')->on('pecsf_regions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pecsf_charities');
    }
}
