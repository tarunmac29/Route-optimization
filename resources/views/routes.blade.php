@extends('layouts.layout')

@section('title', 'Best Route')

@section('content')
    <h1 class="text-center my-4">Find Best Route</h1>
    
    <form action="{{ route('find-best-route') }}" method="POST" class="container">
        @csrf
        <div class="form-group mb-2">
            <label for="start_location">Start Location:</label>
            <input type="text" id="start_location" name="start_location" class="form-control" required>
        </div>
        
        <div class="form-group mb-2">
            <label for="end_location">End Location:</label>
            <input type="text" id="end_location" name="end_location" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary mt-3">Find Best Route</button>
    </form>

    <div id="map" class="mt-4 mb-4"></div>
@endsection

@section('scripts')
    <script>
        var map = L.map('map').setView([{{ $startCoordinates[0] ?? 0 }}, {{ $startCoordinates[1] ?? 0 }}], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        @if(isset($startCoordinates))
        var startMarker = L.marker([{{ $startCoordinates[0] }}, {{ $startCoordinates[1] }}]).addTo(map)
            .bindPopup('Start Location')
            .openPopup();
        @endif

        @if(isset($endCoordinates))
        var endMarker = L.marker([{{ $endCoordinates[0] }}, {{ $endCoordinates[1] }}]).addTo(map)
            .bindPopup('End Location')
            .openPopup();
        @endif

        @if(isset($routes))
            @foreach ($routes as $route)
                var routeCoordinates = [
                    @foreach ($route['coordinates'] as $point)
                        [{{ $point['latitude'] }}, {{ $point['longitude'] }}],
                    @endforeach
                ];

                var polyline = L.polyline(routeCoordinates, {color: 'blue'}).addTo(map);
                polyline.bindPopup('Distance: {{ $route['distance'] }} km, Time: {{ gmdate("H:i:s", $route['time']) }}');
            @endforeach
        @endif
    </script>
@endsection