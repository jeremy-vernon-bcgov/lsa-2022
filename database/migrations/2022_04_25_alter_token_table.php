<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::rename('personal_access_tokens', 'cache');
      Schema::table('cache', function (Blueprint $table) {
          $table->string('key')->unique();
          $table->text('value');
          $table->integer('expiration');
          $table->dropColumn('abilities');
          $table->dropColumn('last_used_at');
          $table->dropColumn('token');
          $table->dropColumn('name');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {}
};
