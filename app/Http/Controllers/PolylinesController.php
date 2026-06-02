<?php

namespace App\Http\Controllers;

use App\Models\PolylinesModel;
use Illuminate\Http\Request;

class PolylinesController extends Controller
{
    protected $polylines;

    public function __construct()
    {
        $this->polylines = new PolylinesModel;
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
                'geometry_polyline' => 'required',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // ✅ 2048
            ],
            [
                'geometry_polyline.required' => 'Field geometry polyline harus diisi.',
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

        // Simpan file image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time().'_polyline.'.strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }

        $data = [
            'geom' => \DB::raw("ST_GeomFromGeoJSON('{$request->geometry_polyline}')"),
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // Simpan data ke database
        if (! $this->polylines->create($data)) { // ✅ if-check
            return redirect()->route('peta')->with('error', 'Gagal menyimpan data polyline.');
        }

        return redirect()->route('peta')->with('success', 'Data polyline berhasil disimpan.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $polyline = $this->polylines->find($id);
        if (! $polyline) {
            return redirect()->route('peta')->with('error', 'Polyline tidak ditemukan.');
        }

        $geojsonRaw = \DB::select('SELECT ST_AsGeoJSON(geom) AS geojson FROM polylines WHERE id = ?', [$id])[0]->geojson ?? null;
        $geojson = $geojsonRaw ? json_decode($geojsonRaw, true) : null;

        return view('map.edit.polylines', [
            'title' => 'Edit Polyline',
            'polyline' => $polyline,
            'geojson' => $geojson,
            'id' => $id,
        ]);
    }

    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate(
            [
                'geometry_polyline' => 'nullable|string',
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

        $polyline = $this->polylines->find($id);
        if (! $polyline) {
            return redirect()->route('peta')->with('error', 'Polyline tidak ditemukan.');
        }

        // Membuat direktori image apabila belum tersedia
        if (! is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        // Simpan file image ke direktori storage/images jika ada
        if ($request->hasFile('image')) {
            if ($polyline->image && file_exists('./storage/images/'.$polyline->image)) {
                unlink('./storage/images/'.$polyline->image);
            }

            $image = $request->file('image');
            $name_image = time().'_polyline.'.strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = $polyline->image;
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        if ($request->filled('geometry_polyline')) {
            $data['geom'] = \DB::raw("ST_GeomFromGeoJSON('{$request->geometry_polyline}')");
        }

        if (! $polyline->update($data)) {
            return redirect()->route('peta')->with('error', 'Gagal mengupdate data polyline.');
        }

        return redirect()->route('peta')->with('success', 'Data polyline berhasil diupdate.');
    }

    public function destroy(string $id)
    {
        // mencari nama file gambar yang akan dihapus
        $image = $this->polylines->find($id)->image;
        // hapus file gambar jika ada
        if ($image != null) {
            if (file_exists('./storage/images/'.$image)) {
                unlink('./storage/images/'.$image);
            }
        }
        // Hapus data dari database
        if (! $this->polylines->destroy($id)) {
            return redirect()->route('peta')->with('error', 'Gagal menghapus data polyline.');
        }

        // Kembali ke halaman peta
        return redirect()->route('peta')->with('success', 'Data polyline berhasil dihapus.');
    }

    public function geojson()
    {
        $polylines = $this->polylines->geojson_polylines();

        return response()->json($polylines);
    }
}
