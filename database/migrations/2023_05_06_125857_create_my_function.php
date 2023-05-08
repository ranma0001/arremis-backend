<?php

use Illuminate\Database\Migrations\Migration;

class CreateMyFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE FUNCTION fn_business_organization_type (input INT)
        RETURNS VARCHAR(50)
        BEGIN
            DECLARE output VARCHAR(50);
            SELECT Description INTO output FROM
            (
                SELECT 1 AS ID, "Sole Proprietorship" AS Description
                UNION
                SELECT 2 AS ID, "Partnership" AS Description
                UNION
                SELECT 3 AS ID, "Corporation" AS Description
                UNION
                SELECT 4 AS ID, "Association" AS Description
                UNION
                SELECT 5 AS ID, "Cooperative" AS Description
            ) AS _main
            WHERE ID = input;
            RETURN output;
        END
    ');

        DB::unprepared('
        CREATE FUNCTION fn_application_type (input INT)
        RETURNS VARCHAR(50)
        BEGIN
            DECLARE output VARCHAR(50);
            SELECT Description INTO output FROM
            (
                SELECT 1 AS ID, "New" AS Description
                UNION
                SELECT 2 AS ID, "Renewal" AS Description
                UNION
                SELECT 3 AS ID, "Amendment" AS Description
            ) AS _main
            WHERE ID = input;
            RETURN output;
        END
    ');

    DB::unprepared('
        CREATE FUNCTION fn_facility_status(input INT)
        RETURNS VARCHAR(50)
        BEGIN
            DECLARE output VARCHAR(50);
            SELECT Description INTO output FROM
            (
                SELECT 1 AS ID, "Owned" AS Description
                UNION
                SELECT 2 AS ID, "Rented" AS Description
            ) AS _main
            WHERE ID = input;
            RETURN output;
        END
    ');


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS fn_application_type');
        DB::unprepared('DROP FUNCTION IF EXISTS fn_business_organization_type');
    }
}
