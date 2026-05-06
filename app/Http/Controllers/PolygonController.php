<?php

namespace App\Http\Controllers;

use App\Models\PolygonModel;
use Illuminate\Http\Request;

class PolygonController extends Controller
{
    protected $polygons;

    public function __construct()
    {
        $this->polygons = new PolygonModel;
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
                'geometry_polygon' => 'required',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // ✅ 2048
            ],
            [
                'geometry_polygon.required' => 'Field geometry polygon harus diisi.',
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
            $name_image = time().'_polygon.'.strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }

        $data = [
            'geom' => $request->geometry_polygon,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // Simpan data ke database
        if (! $this->polygons->create($data)) { // ✅ if-check
            return redirect()->route('peta')->with('error', 'Gagal menyimpan data polygon.');
        }

        return redirect()->route('peta')->with('success', 'Data polygon berhasil disimpan.');
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
        // mencari nama file gambar yang akan dihapus
        $image = $this->polygons->find($id)->image;
        // hapus file gambar jika ada
        if ($image != null) {
            if (file_exists('./storage/images/'.$image)) {
                unlink('./storage/images/'.$image);
            }
        }
        // Hapus data dari database
        if (! $this->polygons->destroy($id)) {
            return redirect()->route('peta')->with('error', 'Gagal menghapus data polygon.');
        }

        // Kembali ke halaman peta
        return redirect()->route('peta')->with('success', 'Data polygon berhasil dihapus.');
    }

    public function geojson()
    {
        $polygons = $this->polygons->geojson_polygons();

        return response()->json($polygons);
    }
}
