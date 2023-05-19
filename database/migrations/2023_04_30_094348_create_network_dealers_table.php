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
            $table->string('email_address');
            $table->string('review_comment')->nullable();
            $table->integer('reviewed_by')->default('0');
            $table->integer('status')->default('0');
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
        Schema::dropIfExists('network_dealer');
    }
}
