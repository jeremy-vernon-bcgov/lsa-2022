<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipients', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            //Identifying Information
            $table->string('idir');
            $table->string('guid');
            $table->string('employee_number');
            $table->string('full_name');
            $table->boolean('is_bcgeu_member')->default(false);
            $table->string('milestones');


            //Milestone Information
            $table->integer('qualifying_year');
            $table->boolean('retiring_this_year')->default(false);
            $table->date('retirement_date')->nullable();
            $table->boolean('survey_participation')->default(true);

            //Work Contact Information
            $table->string('government_email');
            $table->string('government_phone_number')->nullable();

            //work address
            $table->unsignedBigInteger('office_address_id');
            $table->foreign('office_address_id')->references('id')->on('addresses');

            $table->foreignId('organization_id')->constrained();
            $table->string('branch_name');

            //Personal Contact Information
            $table->string('personal_email')->nullable();
            $table->string('personal_phone_number')->nullable();

            //Personal address
            $table->unsignedBigInteger('personal_address_id')->nullable();
            $table->foreign('personal_address_id')->references('id')->on('addresses');

            //Supervisor Information
            $table->string('supervisor_full_name');
            $table->string('supervisor_email');


            //Supervisor Address
            $table->unsignedBigInteger('supervisor_address_id');
            $table->foreign('supervisor_address_id')->references('id')->on('addresses');


            //Administrivia
            //None of these should be input directly by the user
            //All should have defeaults or permit null values.

            $table->text('admin_notes')->nullable();

            $table->softDeletes();




        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registrations');
    }
}
