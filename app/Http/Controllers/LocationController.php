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

        $locations = Location::select('province')
            ->groupBy('province')
            ->where('region', $request->region)
            ->get();

        return response()->json([
            'province' => $locations,
        ], 201);

    }

    public function getMunicipality(Request $request)
    {

        $locations = Location::select('municipality')
            ->groupBy('municipality')
            ->groupBy('province')
            ->where('region', $request->region)
            ->where('province', $request->province)
            ->get();

        return response()->json([
            'municipality' => $locations,
        ], 201);

    }

    public function getBarangay(Request $request)
    {

        $locations = Location::select('barangay')
            ->groupBy('barangay')
            ->groupBy('municipality')
            ->groupBy('province')
            ->where('region', $request->region)
            ->where('province', $request->province)
            ->where('municipality', $request->municipality)
            ->get();

        return response()->json([
            'barangay' => $locations,
        ], 201);

    }

}
