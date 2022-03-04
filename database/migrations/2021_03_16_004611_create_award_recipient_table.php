<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwardRecipientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('award_recipient', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('award_id')->constrained();
            $table->foreignId('recipient_id')->constrained();
            $table->string('milestone')->nullable();
            $table->json('options')->nullable();
            $table->string('status')->nullable();

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('award_selections');
    }
}
