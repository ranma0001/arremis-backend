<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApplicationHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_history', function (Blueprint $table) {
            $table->id();

            $table->string('action');
            $table->string('user_id')->nullable()->default(0);
            $table->string('application_id')->nullable()->default(0);
            $table->string('reviewer')->nullable()->default(0);
            $table->string('status')->nullable()->default(0);
            $table->string('action_date_time')->nullable();
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
        //
    }
}
