<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminController extends Controller
{

    //SUBMISSION
    public function submissionList()
    {

        $token = JWTAuth::parseToken();
        $payload = $token->getPayload();

        $query = "
        SELECT
            ACI.Region
            , ACI.company_name
            , CONCAT(applicant.applicant_firstname, ' ' , applicant.applicant_lastname) AS Applicant_Name
            , CONCAT(ACI.address_street, ' ',ACI.barangay,' ',ACI.municipality, ' ', ACI.province) AS Address
            , ACI.classification
            , application.application_date
            , CASE
                WHEN application.application_type = 1 THEN 'New Application'
                WHEN application.application_type = 2 THEN 'Renewal'
                ELSE 'Amendment'
            END application_type
            , CASE
                WHEN application.status = 0 THEN 'For-Compliance'
                WHEN application.status = 1 THEN 'Compliant'
            END `status`
        FROM
            application
        LEFT JOIN
            applicant
        ON
            application.`applicant_id` = applicant.`id`
        LEFT JOIN
            applicant_company_information ACI
        ON
            application.`company_id` = ACI.ID
        WHERE
			application.`application_status` = 2
            ";

        $results = DB::select(DB::raw($query));

        return response()->json($results);
    }

    //FOR REVIEW
    public function reviewList()
    {

        $token = JWTAuth::parseToken();
        $payload = $token->getPayload();

        $query = "
        SELECT
            ACI.Region
            , ACI.company_name
            , CONCAT(applicant.applicant_firstname, ' ' , applicant.applicant_lastname) AS Applicant_Name
            , CONCAT(ACI.address_street, ' ',ACI.barangay,' ',ACI.municipality, ' ', ACI.province) AS Address
            , ACI.classification
            , application.application_date
            , CASE
                WHEN application.application_type = 1 THEN 'New Application'
                WHEN application.application_type = 2 THEN 'Renewal'
                ELSE 'Amendment'
            END application_type
            , CASE
                WHEN application.status = 0 THEN 'For-Compliance'
                WHEN application.status = 1 THEN 'Compliant'
            END `status`
        FROM
            application
        LEFT JOIN
            applicant
        ON
            application.`applicant_id` = applicant.`id`
        LEFT JOIN
            applicant_company_information ACI
        ON
            application.`company_id` = ACI.ID
        WHERE
			application.`application_status` = 1
            ";

        $results = DB::select(DB::raw($query));

        return response()->json($results);
    }


    

}
