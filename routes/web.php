<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BestRouteController;
use App\Http\Controllers\RouteController;

Route::get('/routes', function () {
    return view('routes');
});

Route::post('/find-best-route', [BestRouteController::class, 'findBestRoute'])->name('find-best-route');

Route::get('/route-form', [RouteController::class, 'showForm'])->name('route-form');
Route::post('/find-routes', [RouteController::class, 'findRoutes'])->name('find-routes');

Route::get('/search-location', function () {
    return view('search');
})->name('search-location');

Route::get('/search', function () {

    return view('search');

})->name('search');

Route::get('/', function () {
    return view('welcome');
});



