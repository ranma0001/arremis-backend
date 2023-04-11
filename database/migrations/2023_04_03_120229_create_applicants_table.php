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
        Schema::create('tbl_applicants', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('applicant_id')->unique();
            $table->string('applicant_name');
            $table->string('designation');
            $table->foreignId('company_info_id')->nullable();
            $table->foreignId('applicant_account_id')->nullable();
            $table->bigInteger('map_id');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('marker_description');
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
        Schema::dropIfExists('applicants');
    }
}
