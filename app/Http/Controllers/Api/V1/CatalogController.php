<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceService;
use App\Models\Package;
use Illuminate\Http\JsonResponse;

class CatalogController extends Controller
{
    public function services(): JsonResponse
    {
        $services = MaintenanceService::where('is_active', true)->get();

        return response()->json([
            'data' => $services
        ]);
    }

    public function packages(): JsonResponse
    {
        $packages = Package::with('services')->where('is_active', true)->get();

        return response()->json([
            'data' => $packages
        ]);
    }
}
