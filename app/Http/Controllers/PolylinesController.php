<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolylinesModel;  // ⭐ harus sama persis dengan nama class di Model

class PolylinesController extends Controller
{
    protected $polylines;

    public function __construct()
    {
        $this->polylines = new PolylinesModel();
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'geometry_polyline' => 'required',
                'name'              => 'required|string|max:255',
                'description'       => 'required|string',
            ],
            [
                'geometry_polyline.required' => 'Field geometry polyline harus diisi.',
                'name.required'              => 'Field name harus diisi.',
                'name.string'                => 'Field name harus berupa string.',
                'name.max'                   => 'Field name tidak boleh lebih dari 255 karakter.',
                'description.required'       => 'Field description harus diisi.',
                'description.string'         => 'Field description harus berupa string.',
            ]
        );

        $data = [
            'geom'        => $request->geometry_polyline,
            'name'        => $request->name,
            'description' => $request->description,
        ];

        if (!$this->polylines->create($data)) {
            return redirect()->route('map')->with('error', 'Gagal menyimpan Polyline.');
        }

        return redirect()->route('map')->with('success', 'Polylines berhasil disimpan.');
    }
}
