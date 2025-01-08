<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TomTomService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('TOMTOM_API_KEY');
    }

    public function geocode($location)
    {
        try {
            $response = $this->client->request('GET', 'https://api.tomtom.com/search/2/geocode/' . urlencode($location) . '.json', [
                'query' => [
                    'key' => $this->apiKey,
                ],
                'verify' => 'C:\cert\cacert.pem', // Path to the cacert.pem file
            ]);

            $data = json_decode($response->getBody(), true);

            if (!isset($data['results'][0]['position'])) {
                throw new \Exception('Invalid geocode data received from TomTom API');
            }

            $position = $data['results'][0]['position'];
            return $position['lat'] . ',' . $position['lon'];
        } catch (RequestException $e) {
            throw new \Exception('Error fetching geocode from TomTom API: ' . $e->getMessage());
        }
    }

    public function calculateRoute($startLocation, $endLocation)
    {
        try {
            $startCoordinates = $this->geocode($startLocation);
            $endCoordinates = $this->geocode($endLocation);

            $response = $this->client->request('GET', "https://api.tomtom.com/routing/1/calculateRoute/{$startCoordinates}:{$endCoordinates}/json", [
                'query' => [
                    'key' => $this->apiKey,
                ],
                'verify' => 'C:\cert\cacert.pem', // Path to the cacert.pem file
            ]);

            $data = json_decode($response->getBody(), true);

            if (!isset($data['routes'])) {
                throw new \Exception('Invalid route data received from TomTom API');
            }

            $routes = [];
            foreach ($data['routes'] as $route) {
                if (!isset($route['summary'])) {
                    continue;
                }

                $summary = $route['summary'];
                $routes[] = [
                    'distance' => $summary['lengthInMeters'] / 1000, // convert to kilometers
                    'time' => $summary['travelTimeInSeconds'],
                    'coordinates' => $route['legs'][0]['points']
                ];
            }

            return [
                'startCoordinates' => explode(',', $startCoordinates),
                'endCoordinates' => explode(',', $endCoordinates),
                'routes' => $routes
            ];
        } catch (RequestException $e) {
            $responseBody = json_decode($e->getResponse()->getBody(), true);
            if (isset($responseBody['detailedError']['message']) && $responseBody['detailedError']['message'] === 'NO_ROUTE_FOUND') {
                throw new \Exception('No route found between the provided locations.');
            }
            throw new \Exception('Error fetching route from TomTom API: ' . $e->getMessage());
        }
    }
}
?>
