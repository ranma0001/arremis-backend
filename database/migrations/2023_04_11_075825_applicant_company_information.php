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
        Schema::create('applicant_company_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id');
            $table->string('company_name');
            $table->string('year_establish');
            $table->string('tel_no');
            $table->string('fax_no');
            $table->string('email');
            $table->string('business_organization_type');
            $table->string('region');
            $table->string('province');
            $table->string('municipality');
            $table->string('barangay');
            $table->string('address_street');
            $table->string('owner_name');
            $table->decimal('map_id');
            $table->double('latitude');
            $table->double('longitude');
            $table->string('marker_description');
            $table->string('application_type');
            $table->date('application_date');
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
        Schema::dropIfExists('applicant_company_information');
    }
}
