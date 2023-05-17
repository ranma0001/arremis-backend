<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application', function (Blueprint $table) {
            $table->increments('application_id');
            $table->string('pto_application_id')->nullable();
            $table->string('company_id')->nullable();
            $table->foreignId('applicant_id');
            $table->string('application_type')->nullable();
            $table->string('application_status')->nullable();
            $table->date('application_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('application_remarks')->nullable();
            $table->integer('document_required')->nullable()->default(0);
            $table->date('transaction_date_time')->nullable();
            $table->integer('document_on_site')->nullable()->default(0);
            $table->integer('is_deleted')->nullable()->default(0);
            $table->json('classification')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('application');
    }
}
