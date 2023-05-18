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
            $table->string('review_comment')->nullable();
            $table->integer('reviewed_by')->default('0');
            $table->integer('status')->default('0');
            $table->integer('review_level')->default('1');
            $table->integer('is_deleted')->default('0');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('facility');
    }
}
