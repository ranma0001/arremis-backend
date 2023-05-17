<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetworkDealersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_dealer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id');
            $table->string('company_name');
            $table->string('contact');
            $table->string('address');
            $table->string('review_comment');
            $table->string('reviewed_by');
            $table->integer('is_verified');
            $table->integer('review_level');
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
        Schema::dropIfExists('network_dealer');
    }
}
