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
        $polylines = self::select(DB::raw('id, ST_AsGeoJSON(geom) as geojson, name, description, created_at, updated_at'))->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => []
        ];

        foreach ($polylines as $polyline) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($polyline->geojson),
                'properties' => [
                    'id' => $polyline->id,
                    'name' => $polyline->name,
                    'description' => $polyline->description,
                    'created_at' => $polyline->created_at,
                    'updated_at' => $polyline->updated_at
                ]
            ];

            array_push($geojson['features'], $feature);
        }

        return $geojson;
    }
}
