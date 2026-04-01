<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use Illuminate\Http\Request;

class PointsController extends Controller
{

    protected $points;

    public function __construct()
    {
        $this->points = new PointsModel();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'geometry_point' => 'required',
                'name'           => 'required|string|max:255',
                'description'    => 'required|string',
            ],
            [
                'geometry_point.required' => 'Field geometry point harus diisi.',
                'name.required'           => 'Field name harus diisi.',
                'name.string'             => 'Field name harus berupa string.',
                'name.max'                => 'Field name tidak boleh lebih dari 255 karakter.',
                'description.required'    => 'Field description harus diisi.',
                'description.string'      => 'Field description harus berupa string.',
            ]
        );

        $data = [
            'geom'        => $request->geometry_point,
            'name'        => $request->name,
            'description' => $request->description,
        ];

        if (!$this->points->create($data)) {
            return redirect()->route('map')->with('error', 'Gagal menyimpan Point.');
        }

        return redirect()->route('map')->with('success', 'Point berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
