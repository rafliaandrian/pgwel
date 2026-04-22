<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController; // ✅ Tambahkan ini

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/points', [ApiController::class, 'geojson_points'])->name('geojson_points');
Route::get('/polylines', [ApiController::class, 'geojson_polylines'])->name('geojson_polylines');
Route::get('/polygons', [ApiController::class, 'geojson_polygons'])->name('geojson_polygons');
