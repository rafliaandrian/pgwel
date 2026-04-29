<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PointsModel extends Model
{
    protected $table = 'points';
    protected $guarded = ['id'];

    public function geojson_points()
    {
        // ST_AsGeoJSON konversi geometry PostGIS → GeoJSON string
        $points = DB::select("
            SELECT id, name, description, image, created_at, updated_at,
                   ST_AsGeoJSON(geom) as geom
            FROM points
        ");

        $geojson = [
            'type'     => 'FeatureCollection',
            'features' => []
        ];

        foreach ($points as $point) {
            $geometry = json_decode($point->geom, true);

            $geojson['features'][] = [
                'type'     => 'Feature',
                'geometry' => $geometry,
                'properties' => [
                    'id'          => $point->id,
                    'name'        => $point->name,
                    'description' => $point->description,
                    'image'       => $point->image,
                    'created_at'  => $point->created_at,
                    'updated_at'  => $point->updated_at,
                ],
            ];
        }

        return $geojson;
    }
}
