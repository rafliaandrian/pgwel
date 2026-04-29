<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // Tambah kolom geometry PostGIS setelah tabel dibuat
        DB::statement('ALTER TABLE points ADD COLUMN geom geometry(Point, 4326)');
    }

    public function down(): void
    {
        Schema::dropIfExists('points');
    }
};
