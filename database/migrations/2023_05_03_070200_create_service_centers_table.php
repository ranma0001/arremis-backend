<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_center', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id');
            $table->string('center_name');
            $table->string('contact')->nullable();
            $table->string('email')->nullable();
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->string('address')->nullable();
            $table->string('review_comment')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->integer('is_verified')->default('0');
            $table->integer('review_level')->default('1');
            $table->integer('is_deleted')->default('0');
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
        Schema::dropIfExists('service_center');
    }
}
