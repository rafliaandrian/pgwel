<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('polylines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE polylines ADD COLUMN geom geometry(LineString, 4326)');
    }

    public function down(): void
    {
        Schema::dropIfExists('polylines');
    }
};
