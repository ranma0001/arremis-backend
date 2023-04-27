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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id');
            $table->string('facility_name');
            $table->integer('facility_quantity');
            $table->integer('status');
            $table->string('image_string');
            $table->string('review_comment');
            $table->string('reviewed_by');
            $table->integer('is_verified');
            $table->integer('review_level');
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
        Schema::dropIfExists('facilities');
    }
}
