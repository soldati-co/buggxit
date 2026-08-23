<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaxiPointService;
use Illuminate\Http\Request;

class PepPointsApiController extends Controller
{
    public function index(Request $request, PaxiPointService $paxi)
    {
        $validated = $request->validate([
            'province' => 'required|string|max:100',
        ]);

        return response()->json([
            'data' => $paxi->forProvince($validated['province']),
        ]);
    }
}
