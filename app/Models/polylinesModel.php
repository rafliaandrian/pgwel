<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PolylinesModel extends Model
{
    protected $table = 'polylines';

    protected $guarded = ['id'];

    public function geojson_polylines()
    {
        $polylines = DB::select('
            SELECT id, name, description, image, created_at, updated_at,
                   ST_AsGeoJSON(geom) as geom
            FROM polylines
        ');

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polylines as $polyline) {
            $geometry = json_decode($polyline->geom, true);

            $geojson['features'][] = [
                'type' => 'Feature',
                'geometry' => $geometry,
                'properties' => [
                    'id' => $polyline->id,
                    'name' => $polyline->name,
                    'description' => $polyline->description,
                    'image' => $polyline->image,
                    'created_at' => $polyline->created_at,
                    'updated_at' => $polyline->updated_at,
                ],
            ];
        }

        return $geojson;
    }

    public function geojson_polyline($id)
    {
        $polylines = DB::select('
            SELECT id, name, description, image, created_at, updated_at,
                   ST_AsGeoJSON(geom) as geom
            FROM polylines
            WHERE id = ?
        ', [$id]);

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polylines as $polyline) {
            $geometry = json_decode($polyline->geom, true);

            $geojson['features'][] = [
                'type' => 'Feature',
                'geometry' => $geometry,
                'properties' => [
                    'id' => $polyline->id,
                    'name' => $polyline->name,
                    'description' => $polyline->description,
                    'image' => $polyline->image,
                    'created_at' => $polyline->created_at,
                    'updated_at' => $polyline->updated_at,
                ],
            ];
        }

        return $geojson;
    }
}
