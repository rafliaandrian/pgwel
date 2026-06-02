@extends('layouts.template')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.css">
    <style>
        body {
            margin: 0;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Tabel Data</h3>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped" id="tabeldatapoints">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Foto</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($points as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->description }}</td>
                    <td><img src="{{ asset('storage/images/' . $p['image']) }}" alt="Foto" width="100"></td>
                    <td>{{ $p->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Tabel Data Polylines</h3>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped" id="tabeldatapolylines">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Foto</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($polylines as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->description }}</td>
                    <td><img src="{{ asset('storage/images/' . $p['image']) }}" alt="Foto" width="100"></td>
                    <td>{{ $p->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3>Tabel Data Polygons</h3>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped" id="tabeldatapolygons">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Foto</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($polygons as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->description }}</td>
                    <td><img src="{{ asset('storage/images/' . $p['image']) }}" alt="Foto" width="100"></td>
                    <td>{{ $p->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>
<script>
    $(document).ready(function() {
        $('#tabeldatapoints').DataTable();
        $('#tabeldatapolylines').DataTable();
        $('#tabeldatapolygons').DataTable();
    });
</script>
@endsection
