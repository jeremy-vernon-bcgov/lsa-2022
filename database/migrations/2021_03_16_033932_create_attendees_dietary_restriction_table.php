<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendeesDietaryRestrictionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendee_dietary_restriction', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('dietary_restriction_id');
            $table->foreignId('attendee_id');
            $table->longText('additional_details')->nullable();

            $table->foreign('dietary_restriction_id')->references('id')->on('dietary_restrictions');
            $table->foreign('attendee_id')->references('id')->on('attendees');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendee_dietary_restriction');
    }
}
