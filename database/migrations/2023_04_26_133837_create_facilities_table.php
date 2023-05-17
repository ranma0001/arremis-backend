<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id');
            $table->string('facility_name');
            $table->integer('facility_quantity');
            $table->integer('status')->nullable();
            $table->string('image_string')->nullable();
            $table->string('review_comment')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->integer('is_verified')->nullable();
            $table->integer('review_level')->nullable();
            $table->integer('is_deleted')->default('0');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('facility');
    }
}
