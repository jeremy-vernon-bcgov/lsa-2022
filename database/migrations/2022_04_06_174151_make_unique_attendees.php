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
      Schema::table('attendees', function($table)
      {
          $table->unique(['attendable_id', 'attendable_type', 'ceremonies_id']);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('attendees', function($table)
      {
        $table->dropUnique('attendees_attendable_id_attendable_type_ceremonies_id_unique');
      });
    }
};
