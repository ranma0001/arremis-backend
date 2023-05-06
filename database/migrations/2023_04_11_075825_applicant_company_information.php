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
            $table->string('tel_no')->nullable();
            $table->string('fax_no')->nullable();
            $table->string('company_email')->nullable();
            $table->string('business_organization_type')->nullable();
            $table->string('region')->nullable();
            $table->string('province')->nullable();
            $table->string('municipality')->nullable();
            $table->string('barangay')->nullable();
            $table->string('address_street')->nullable();
            $table->string('owner_name')->nullable();
            $table->decimal('map_id')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('marker_description')->nullable();
            $table->string('application_type')->nullable();
            $table->date('application_date')->nullable();
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
