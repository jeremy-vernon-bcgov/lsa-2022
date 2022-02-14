<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessibilityOptionAttendeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accessibility_option_attendee', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('accessibility_option_id');
            $table->foreignId('attendee_id');
            $table->longText('additional_details')->nullable();
            $table->text('admin_note')->nullable();

            $table->foreign('accessibility_option_id')->references('id')->on('accessibility_options');
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
        Schema::dropIfExists('accessibility_option_attendee');
    }
}
