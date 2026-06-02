<?php

namespace App\Http\Controllers;

use App\Models\pointsModel;
use App\Models\PolygonModel;
use App\Models\polylinesModel;

class PageController extends Controller
{
    public function __construct()
    {
        $this->points = new pointsModel;
        $this->polylines = new polylinesModel;
        $this->polygon = new PolygonModel;
        $this->users = new \App\Models\User;
    }

    public function landingpage()
    {
        $data = [
            'title' => 'PGWL',
            'points_count' => $this->points->count(),
        ];

        return view('home', $data);
    }

    public function peta()
    {
        $data = [
            'title' => 'Peta Interaktif',
        ];

        return view('map', $data);
    }

    public function table()
    {
        $data = [
            'title' => 'Tabel',
            'points' => $this->points->all(),
            'polylines' => $this->polylines->all(),
            'polygons' => $this->polygon->all(),
        ];

        return view('table', $data);
    }
}
