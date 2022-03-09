<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoricalRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historical_recipients', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number');
            $table->string('government_email');
            $table->integer('milestone')->nullable();
            $table->integer('milestone_year')->nullable();
        });

    }

    public function down()
    {
        Schema::dropIfExists('historical_recipients');
    }
}
