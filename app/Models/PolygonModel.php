<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PolygonModel extends Model
{
    protected $table = 'polygons';

    protected $guarded = ['id'];

    public function geojson_polygons()
    {
        $polygons = DB::select('
            SELECT id, name, description, image, created_at, updated_at,
                   ST_AsGeoJSON(geom) as geom
            FROM polygons
        ');

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polygons as $polygon) {
            $geometry = json_decode($polygon->geom, true);

            $geojson['features'][] = [
                'type' => 'Feature',
                'geometry' => $geometry,
                'properties' => [
                    'id' => $polygon->id,
                    'name' => $polygon->name,
                    'description' => $polygon->description,
                    'image' => $polygon->image,
                    'created_at' => $polygon->created_at,
                    'updated_at' => $polygon->updated_at,
                ],
            ];
        }

        return $geojson;
    }

    public function geojson_polygon($id)
    {
        $polygons = DB::select('
            SELECT id, name, description, image, created_at, updated_at,
                   ST_AsGeoJSON(geom) as geom
            FROM polygons
            WHERE id = ?
        ', [$id]);

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polygons as $polygon) {
            $geometry = json_decode($polygon->geom, true);

            $geojson['features'][] = [
                'type' => 'Feature',
                'geometry' => $geometry,
                'properties' => [
                    'id' => $polygon->id,
                    'name' => $polygon->name,
                    'description' => $polygon->description,
                    'image' => $polygon->image,
                    'created_at' => $polygon->created_at,
                    'updated_at' => $polygon->updated_at,
                ],
            ];
        }

        return $geojson;
    }
}
