<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Location extends Migration
{
    public function up()
    {
        $jsonFile = resource_path('db/location.json');
        $jsonData = file_get_contents($jsonFile);
        $data = json_decode($jsonData, true);

        Schema::create('locations', function ($table) {
            $table->increments('id');
            $table->integer('arr')->nullable();
            $table->string('reg_abbreviation')->nullable();
            $table->string('reg')->nullable();
            $table->string('region')->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('municipality')->nullable();
            $table->string('barangay')->nullable();
            $table->string('psgc_code')->nullable();
            $table->timestamps();
        });

        foreach ($data as $row) {
            DB::table('locations')->insert($row);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
