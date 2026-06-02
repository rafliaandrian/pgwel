<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; // ✅ Tambahkan ini

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/points', [ApiController::class, 'geojson_points'])->name('geojson_points');
Route::get('/points/{id}', [ApiController::class, 'geojson_point'])->name('geojson_point');
Route::get('/polylines', [ApiController::class, 'geojson_polylines'])->name('geojson_polylines');
Route::get('/polylines/{id}', [ApiController::class, 'geojson_polyline'])->name('geojson_polyline');
Route::get('/polygons', [ApiController::class, 'geojson_polygons'])->name('geojson_polygons');
Route::get('/polygons/{id}', [ApiController::class, 'geojson_polygon'])->name('geojson_polygon');
