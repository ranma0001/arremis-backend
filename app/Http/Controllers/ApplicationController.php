<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{

    public function create_application(Request $request)
    {
        try {
            $application = Application::query()
                ->select(DB::raw('max(application_id) as application_id'))
                ->where('is_deleted', 0)
                ->first();

        } catch (Exception $e) {
            return response()->json([$e]);
        }
        $application_id = $application['application_id'] == null ? 0 : $application['application_id'];

        $application = Application::create([
            'pto_application_id' => 'H4tD06-' . $application_id,
            'applicant_id' => $request->applicant_id,
            'application_type' => $request->application_type,
        ]);

        return response()->json([
            'data' => $application,
        ],
        );

    }

    public function list_application_with_data(Request $request)
    {
        $query = Application::with(['equipment']);

        $ALLOWED_FILTERS = [];
        $SEARCH_FIELDS = [];
        $JSON_FIELDS = [];
        $BOOL_FIELDS = [];
        $response = $this->paginate_filter_sort_search($query, $ALLOWED_FILTERS, $JSON_FIELDS, $BOOL_FIELDS, $SEARCH_FIELDS);
        return response()->json([
            'applicant' => $response,

        ]);

    }
}
