<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApplicantAccountInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_applicant_company_information', function (Blueprint $table) {
            $table->bigInteger('applicant_account_id')->unique();
            $table->foreignId('applicant_id');
            $table->string('username');
            $table->bigInteger('password');
            $table->string('status');
            $table->string('owner_name');
            $table->string('profile_picture');
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
        Schema::dropIfExists('tbl_applicant_account_information');
    }
}
