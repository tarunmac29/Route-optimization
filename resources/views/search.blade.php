@extends('layouts.layout')

@section('title', 'Search Location')

@section('content')
    <h1 class="text-center my-4">Search Location</h1>
    
    <div class="container mt-4">
        <div class="form-group mb-2">
            <label for="search_location">Search Location:</label>
            <input type="text" id="search_location" class="form-control" placeholder="Enter a location">
        </div>
        <button id="search_button" class="btn btn-secondary">Search Location</button>
    </div>

    <div id="map" class="mt-4 mb-4"></div>
@endsection

@section('scripts')
    <script>
        var map = L.map('map').setView([0, 0], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        document.getElementById('search_button').addEventListener('click', function() {
            var location = document.getElementById('search_location').value;
            if (location) {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${location}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            var lat = data[0].lat;
                            var lon = data[0].lon;
                            var searchMarker = L.marker([lat, lon]).addTo(map)
                                .bindPopup(location)
                                .openPopup();
                            map.setView([lat, lon], 13);
                        } else {
                            alert('Location not found');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                alert('Please enter a location');
            }
        });
    </script>
@endsection