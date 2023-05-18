<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentaryRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('documentary_requirement', function (Blueprint $table) {
                $table->id();
                $table->foreignId('application_id');
                $table->string('document_name');
                $table->string('file_name')->nullable();
                $table->string('file_type')->nullable();
                $table->string('file_location')->nullable();
                $table->string('review_comment')->nullable();
                $table->integer('reviewed_by')->nullable();
                $table->integer('document_status')->default('1');
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
        Schema::dropIfExists('documentary_requirements');
    }
}
