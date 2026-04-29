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

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate(
            [
                'geometry_point' => 'required',
                'name'           => 'required|string|max:255',
                'description'    => 'required|string',
                'image'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // ✅ 2048
            ],
            [
                'geometry_point.required' => 'Field geometry point harus diisi.',
                'name.required'           => 'Field nama harus diisi.',
                'name.string'             => 'Field nama harus berupa string.',
                'name.max'                => 'Field nama tidak boleh lebih dari 255 karakter.',
                'description.required'    => 'Field deskripsi harus diisi.',
                'description.string'      => 'Field deskripsi harus berupa string.',
                'image.image'             => 'File harus berupa file gambar.',
                'image.mimes'             => 'File gambar harus berupa file dengan ekstensi jpeg, png, atau jpg.',
                'image.max'               => 'Ukuran file gambar tidak boleh lebih dari 2MB.',
            ]
        );

        // Membuat direktori image apabila belum tersedia
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        // Simpan file image ke direktori storage/images
        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }

        $data = [
            'geom'        => $request->geometry_point,
            'name'        => $request->name,
            'description' => $request->description,
            'image'       => $name_image,
        ];

        // Simpan data ke database
        if (!$this->points->create($data)) { // ✅ pakai if-check bukan try-catch
            return redirect()->route('peta')->with('error', 'Gagal menyimpan data point.');
        }

        return redirect()->route('peta')->with('success', 'Data point berhasil disimpan.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function geojson()
    {
        $points = $this->points->geojson_points();
        return response()->json($points);
    }
}
