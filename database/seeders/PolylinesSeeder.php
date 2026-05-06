<?php

namespace Database\Seeders;

use App\Models\PolylinesModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PolylinesSeeder extends Seeder
{
    public function run(): void
    {
        // Sample polyline data (LineString)
        $polylines = [
            [
                'name' => 'Jalan Utama',
                'description' => 'Jalan utama menuju pusat kota',
                'geom' => DB::raw("ST_GeomFromText('LINESTRING(-7.7956 110.3695, -7.7960 110.3700, -7.7965 110.3705)', 4326)"),
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sungai',
                'description' => 'Alur sungai di daerah tersebut',
                'geom' => DB::raw("ST_GeomFromText('LINESTRING(-7.7970 110.3710, -7.7975 110.3715, -7.7980 110.3720)', 4326)"),
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($polylines as $polyline) {
            PolylinesModel::create($polyline);
        }
    }
}
