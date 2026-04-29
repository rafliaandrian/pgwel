<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\PolygonController;
use App\Http\Controllers\PolylinesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/map', [PageController::class, 'peta'])->name('peta'); // ✅ 'map' → 'peta'

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/table', [PageController::class, 'table'])->name('tabel');

// Store routes
Route::post('/points',           [PointsController::class,   'store'])->name('points.store');
Route::post('/store-polylines',  [PolylinesController::class, 'store'])->name('polylines.store');
Route::post('/store-polygon',    [PolygonController::class,   'store'])->name('polygon.store');

// GeoJSON routes
Route::get('/geojson-points',    [PointsController::class,   'geojson'])->name('geojson_points');
Route::get('/geojson-polylines', [PolylinesController::class, 'geojson'])->name('geojson_polylines');
Route::get('/geojson-polygons',  [PolygonController::class,   'geojson'])->name('geojson_polygons');

require __DIR__.'/settings.php';
