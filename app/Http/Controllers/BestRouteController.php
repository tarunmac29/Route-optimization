<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BestRoute;
use App\Services\TomTomService;

class BestRouteController extends Controller
{
    protected $tomTomService;

    public function __construct(TomTomService $tomTomService)
    {
        $this->tomTomService = $tomTomService;
    }

    public function findBestRoute(Request $request)
    {
        $startLocation = $request->input('start_location');
        $endLocation = $request->input('end_location');

        try {
            $routeData = $this->tomTomService->calculateRoute($startLocation, $endLocation);

            if (empty($routeData['routes'])) {
                return response()->json(['error' => 'Invalid route data'], 400);
            }

            $bestRoute = BestRoute::create([
                'start_location' => $startLocation,
                'end_location' => $endLocation,
                'distance' => $routeData['routes'][0]['distance'],
                'time' => $routeData['routes'][0]['time'],
            ]);

            return view('routes', [
                'bestRoute' => $bestRoute,
                'startCoordinates' => $routeData['startCoordinates'],
                'endCoordinates' => $routeData['endCoordinates'],
                'routes' => $routeData['routes']
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}