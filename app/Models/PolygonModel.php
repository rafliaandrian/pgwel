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
        $polygons = self::select(DB::raw('id, ST_AsGeoJSON(geom) as geojson, name, description, created_at, updated_at'))->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => []
        ];

        foreach ($polygons as $polygon) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($polygon->geojson),
                'properties' => [
                    'id' => $polygon->id,
                    'name' => $polygon->name,
                    'description' => $polygon->description,
                    'created_at' => $polygon->created_at,
                    'updated_at' => $polygon->updated_at
                ]
            ];

            array_push($geojson['features'], $feature);
        }

        return $geojson;
    }
}
