@extends('layouts.layout')

@section('title', 'Best Route')

@section('content')
    <h1 class="text-center my-4">Best Route</h1>

    <div id="map" class="mt-4 mb-4"></div>
@endsection

@section('scripts')
    <script>
        var startCoordinates = @json($startCoordinates ?? [0, 0]);
        var endCoordinates = @json($endCoordinates ?? [0, 0]);
        var routes = @json($routes ?? []);

        var map = L.map('map').setView([startCoordinates[0], startCoordinates[1]], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        if (startCoordinates) {
            var startMarker = L.marker([startCoordinates[0], startCoordinates[1]]).addTo(map)
                .bindPopup('Start Location')
                .openPopup();
        }

        if (endCoordinates) {
            var endMarker = L.marker([endCoordinates[0], endCoordinates[1]]).addTo(map)
                .bindPopup('End Location')
                .openPopup();
        }

        if (routes) {
            routes.forEach(function(route) {
                var routeCoordinates = route.coordinates.map(function(point) {
                    return [point.latitude, point.longitude];
                });

                var polyline = L.polyline(routeCoordinates, {color: 'blue'}).addTo(map);
                polyline.bindPopup('Distance: ' + route.distance + ' km, Time: ' + new Date(route.time * 1000).toISOString().substr(11, 8));
            });
        }
    </script>
@endsection