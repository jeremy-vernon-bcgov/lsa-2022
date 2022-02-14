<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendees', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('type', ['vip','recipient','guest']);
            $table->foreignId('vip_id')->nullable();
            $table->foreignId('recipient_id')->nullable();
            $table->foreignId('guest_id')->nullable();
            $table->foreignId('ceremony_id')->nullable();
            $table->enum('status',['','assigned','invited','attending','declined','waitlisted']);
            $table->string('identifier')->nullable();


            $table->foreign('vip_id')->references('id')->on('vips');
            $table->foreign('recipient_id')->references('id')->on('recipients');
            $table->foreign('guest_id')->references('id')->on('guests');
            $table->foreign('ceremony_id')->references('id')->on('ceremonies');
            $table->text('annotations')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendees');
    }
}
