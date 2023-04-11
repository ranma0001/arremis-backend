<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApplicantCompanyInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_applicant_company_information', function (Blueprint $table) {
            $table->bigInteger('applicant_company_id')->unique();
            $table->foreignId('applicant_id');
            $table->string('company_name');
            $table->bigInteger('year_establish');
            $table->string('tel_no');
            $table->string('fax_no');
            $table->string('email');
            $table->string('business_organization');
            $table->string('province');
            $table->string('municipality');
            $table->string('barangay');

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
        Schema::dropIfExists('tbl_applicant_company_information');
    }
}
