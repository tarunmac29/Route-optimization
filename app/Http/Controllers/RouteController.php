<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TomTomService;

class RouteController extends Controller
{
    protected $tomTomService;

    public function __construct(TomTomService $tomTomService)
    {
        $this->tomTomService = $tomTomService;
    }

    public function showForm()
    {
        return view('route-form');
    }

    public function findRoutes(Request $request)
    {
        $startLocation = $request->input('start_location');
        $endLocation = $request->input('end_location');

        try {
            $routeData = $this->tomTomService->calculateRoute($startLocation, $endLocation);

            if (empty($routeData['routes'])) {
                return response()->json(['error' => 'Invalid route data'], 400);
            }

            return view('routes', [
                'startCoordinates' => $routeData['startCoordinates'],
                'endCoordinates' => $routeData['endCoordinates'],
                'routes' => $routeData['routes']
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}