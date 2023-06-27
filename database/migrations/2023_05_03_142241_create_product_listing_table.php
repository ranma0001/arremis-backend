<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductListingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_listing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id');
            $table->string('item_name');
            $table->string('item_brand');
            $table->string('description')->nullable();
            $table->json('classification')->nullable();
            $table->string('cc_no')->nullable();
            $table->string('country_manufacturer')->nullable();
            $table->string('certificate_distributorship')->nullable();
            $table->string('certificate_country_manufacturer')->nullable();
            $table->integer('inspected')->default('0');
            $table->string('review_comment')->nullable();
            $table->integer('reviewed_by')->nullable();
            $table->integer('status')->default('0');
            $table->integer('review_level')->default('1');
            $table->string('file_location')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();
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
        Schema::dropIfExists('product_listing');
    }
}
