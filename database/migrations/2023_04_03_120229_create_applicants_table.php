<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('applicant_firstname');
            $table->string('applicant_middlename')->nullable();
            $table->string('applicant_lastname');
            $table->string('applicant_extensionname')->nullable();
            $table->string('designation')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('is_deleted')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicant');
    }
}
