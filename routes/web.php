<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\PolygonController;
use App\Http\Controllers\PolylinesController;
use Illuminate\Support\Facades\Route;

Route::get('/home', [PageController::class, 'landingpage'])->name('home');

Route::get('/map', [PageController::class, 'peta'])
    ->middleware(['auth', 'verified'])
    ->name('peta'); // ✅ 'map' → 'peta'

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/table', [PageController::class, 'table'])->name('tabel');

// Store routes
Route::post('/points', [PointsController::class,   'store'])->name('points.store');
Route::get('/points/{id}/edit', [PointsController::class,   'edit'])->name('points.edit');
Route::get('/points/{id}', [PointsController::class,   'show'])->name('points.show');
Route::put('/points/{id}', [PointsController::class,   'update'])->name('points.update');
Route::delete('/delete-points/{id}', [PointsController::class,   'destroy'])->name('points.delete');
Route::get('/edit-points/{id}', function (string $id) {
    return redirect()->route('points.edit', $id);
});
Route::get('/edit-point/{id}', function (string $id) {
    return redirect()->route('points.edit', $id);
});
Route::post('/store-polylines', [PolylinesController::class, 'store'])->name('polylines.store');
Route::delete('/delete-polylines/{id}', [PolylinesController::class, 'destroy'])->name('polylines.delete');
Route::get('/polylines/{id}/edit', [PolylinesController::class, 'edit'])->name('polylines.edit');
Route::get('/polylines/{id}', [PolylinesController::class, 'show'])->name('polylines.show');
Route::put('/polylines/{id}', [PolylinesController::class, 'update'])->name('polylines.update');
Route::post('/store-polygon', [PolygonController::class,   'store'])->name('polygon.store');
Route::delete('/delete-polygons/{id}', [PolygonController::class,   'destroy'])->name('polygons.delete');
Route::get('/polygons/{id}/edit', [PolygonController::class,   'edit'])->name('polygons.edit');
Route::get('/polygons/{id}', [PolygonController::class,   'show'])->name('polygons.show');
Route::put('/polygons/{id}', [PolygonController::class,   'update'])->name('polygons.update');

// GeoJSON routes
Route::get('/geojson-points', [PointsController::class,   'geojson'])->name('geojson_points');
Route::get('/geojson-polylines', [PolylinesController::class, 'geojson'])->name('geojson_polylines');
Route::get('/geojson-polygons', [PolygonController::class,   'geojson'])->name('geojson_polygons');

require __DIR__.'/settings.php';
