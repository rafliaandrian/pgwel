<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // ✅ Fix 1: Import DB facade

class PointsModel extends Model
{
    protected $table = 'points';
    protected $guarded = ['id'];

    public function geojson_points()
{
    $points = self::select(DB::raw('id, ST_AsGeoJSON(geom) as geojson, name, description, image, created_at, updated_at'))->get();

    $geojson = [
        'type' => 'FeatureCollection',
        'features' => []
    ];

    foreach ($points as $point) {
        $feature = [
            'type' => 'Feature',
            'geometry' => json_decode($point->geojson),
            'properties' => [
                'id' => $point->id,
                'name' => $point->name,
                'description' => $point->description,
                'image' => $point->image,
                'created_at' => $point->created_at,
                'updated_at' => $point->updated_at
            ]
        ];

        array_push($geojson['features'], $feature);
    }

    return $geojson;
    }
}
