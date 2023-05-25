<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getRegion()
    {

        $location = Location::select('region')->distinct()->get();
        return response()->json([
            'region' => $location,
        ], 201);

    }

    public function getProvince(Request $request)
    {

        $locations = Location::select('id', 'province')
            ->groupBy()
            ->where('region', $request->region)
            ->get();

        return response()->json([
            'region' => $locations,
        ], 201);

    }

    public function getMunicipality()
    {

        $location = Location::select('id', 'reg')->distinct()->get();
        return response()->json([
            'region' => $location,
        ], 201);

    }
}
