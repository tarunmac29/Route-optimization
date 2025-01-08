@extends('layouts.layout')

@section('title', 'Find Best Route')

@section('content')
    <h1 class="text-center my-4">Find Best Route</h1>
    
    <form action="{{ route('find-routes') }}" method="POST" class="container">
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
@endsection