<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolygonModel;

class PolygonController extends Controller
{
    protected $polygon;

    public function __construct()
    {
        $this->polygon = new PolygonModel();
    }

    public function index() {}
    public function create() {}

    public function store(Request $request)
    {
        $request->validate(
            [
                'geometry_polygon' => 'required',
                'name'             => 'required|string|max:255',
                'description'      => 'required|string',
            ],
            [
                'geometry_polygon.required' => 'Field geometry polygon harus diisi.',
                'name.required'             => 'Field name harus diisi.',
                'name.string'               => 'Field name harus berupa string.',
                'name.max'                  => 'Field name tidak boleh lebih dari 255 karakter.',
                'description.required'      => 'Field description harus diisi.',
                'description.string'        => 'Field description harus berupa string.',
            ]
        );

        $data = [
            'geom'        => $request->geometry_polygon,
            'name'        => $request->name,
            'description' => $request->description,
        ];

        if (!$this->polygon->insert($data)) {
            return redirect()->route('map')->with('error', 'Gagal menyimpan Polygon.');
        }

        return redirect()->route('map')->with('success', 'Polygon berhasil disimpan.');
    }

    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
