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
            $table->string('manufacturer')->nullable();
            $table->string('fabricator')->nullable();
            $table->string('assembler')->nullable();
            $table->string('distributor')->nullable();
            $table->string('dealer')->nullable();
            $table->string('importer')->nullable();
            $table->string('exporter')->nullable();
            $table->string('cc_no')->nullable();
            $table->string('country_manufacturer')->nullable();
            $table->string('image_string')->nullable();
            $table->string('inspected')->nullable();
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
        Schema::dropIfExists('product_listing');
    }
}
