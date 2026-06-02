<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use Illuminate\Http\Request;

class PointsController extends Controller
{
    protected $points;

    public function __construct()
    {
        $this->points = new PointsModel;
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
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // ✅ 2048
            ],
            [
                'geometry_point.required' => 'Field geometry point harus diisi.',
                'name.required' => 'Field nama harus diisi.',
                'name.string' => 'Field nama harus berupa string.',
                'name.max' => 'Field nama tidak boleh lebih dari 255 karakter.',
                'description.required' => 'Field deskripsi harus diisi.',
                'description.string' => 'Field deskripsi harus berupa string.',
                'image.image' => 'File harus berupa file gambar.',
                'image.mimes' => 'File gambar harus berupa file dengan ekstensi jpeg, png, atau jpg.',
                'image.max' => 'Ukuran file gambar tidak boleh lebih dari 2MB.',
            ]
        );

        // Membuat direktori image apabila belum tersedia
        if (! is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        // Simpan file image ke direktori storage/images
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time().'_point.'.strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }

        $data = [
            'geom' => \DB::raw("ST_GeomFromGeoJSON('{$request->geometry_point}')"),
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // Simpan data ke database
        if (! $this->points->create($data)) { // ✅ pakai if-check bukan try-catch
            return redirect()->route('peta')->with('error', 'Gagal menyimpan data point.');
        }

        return redirect()->route('peta')->with('success', 'Data point berhasil disimpan.');
    }

    public function show(string $id)
    {
        $point = $this->points->find($id);
        if (! $point) {
            return response()->json(['error' => 'Point tidak ditemukan'], 404);
        }

        return response()->json([
            'id' => $point->id,
            'name' => $point->name,
            'description' => $point->description,
            'image' => $point->image,
            'geom' => \DB::select('SELECT ST_AsGeoJSON(geom) as geojson FROM points WHERE id = ?', [$id])[0]->geojson ?? null,
        ]);
    }

    public function edit(string $id)
    {
        $point = $this->points->find($id);
        if (! $point) {
            return redirect()->route('peta')->with('error', 'Point tidak ditemukan.');
        }

        $geojsonRaw = \DB::select('SELECT ST_AsGeoJSON(geom) AS geojson FROM points WHERE id = ?', [$id])[0]->geojson ?? null;
        $geojson = $geojsonRaw ? json_decode($geojsonRaw, true) : null;

        return view('map.edit.point', [
            'title' => 'Edit Point',
            'point' => $point,
            'geojson' => $geojson,
            'id' => $id,
        ]);
    }

    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate(
            [
                'geometry_point' => 'nullable|string',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'name.required' => 'Field nama harus diisi.',
                'name.string' => 'Field nama harus berupa string.',
                'name.max' => 'Field nama tidak boleh lebih dari 255 karakter.',
                'description.required' => 'Field deskripsi harus diisi.',
                'description.string' => 'Field deskripsi harus berupa string.',
                'image.image' => 'File harus berupa file gambar.',
                'image.mimes' => 'File gambar harus berupa file dengan ekstensi jpeg, png, atau jpg.',
                'image.max' => 'Ukuran file gambar tidak boleh lebih dari 2MB.',
            ]
        );

        $point = $this->points->find($id);
        if (! $point) {
            return redirect()->route('peta')->with('error', 'Point tidak ditemukan.');
        }

        // Membuat direktori image apabila belum tersedia
        if (! is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        // Simpan file image ke direktori storage/images jika ada
        if ($request->hasFile('image')) {
            if ($point->image && file_exists('./storage/images/'.$point->image)) {
                unlink('./storage/images/'.$point->image);
            }

            $image = $request->file('image');
            $name_image = time().'_point.'.strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = $point->image;
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        if ($request->filled('geometry_point')) {
            $data['geom'] = \DB::raw("ST_GeomFromGeoJSON('{$request->geometry_point}')");
        }

        if (! $point->update($data)) {
            return redirect()->route('peta')->with('error', 'Gagal mengupdate data point.');
        }

        return redirect()->route('peta')->with('success', 'Data point berhasil diupdate.');
    }

    public function destroy(string $id)
    {
        // mencari nama file gambar yang akan dihapus
        $image = $this->points->find($id)->image;
        // hapus file gambar jika ada
        if ($image != null) {
            if (file_exists('./storage/images/'.$image)) {
                unlink('./storage/images/'.$image);
            }
        }
        // Hapus data dari database
        if (! $this->points->destroy($id)) {
            return redirect()->route('peta')->with('error', 'Gagal menghapus data point.');
        }

        // Kembali ke halaman peta
        return redirect()->route('peta')->with('success', 'Data point berhasil dihapus.');
    }

    public function geojson()
    {
        $points = $this->points->geojson_points();

        return response()->json($points);
    }
}
