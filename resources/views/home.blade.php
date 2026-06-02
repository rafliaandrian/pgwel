@extends('layouts.template')
@section('styles')
    <style>
        body {
            background-color: #f5f3fc;
        }
        .hero-card {
            border-radius: 12px;
            border: 0.5px solid #c4b8ef;
            background-color: #f3f0fb;
            overflow: hidden;
        }
        .hero-card .card-header {
            background: linear-gradient(135deg, #7c6db5 0%, #9b8ed4 100%);
            border: none;
            padding: 1rem 1.5rem;
        }
        .hero-card .card-header h3 {
            color: #ede9fc;
            font-weight: 500;
            letter-spacing: 0.4px;
            margin: 0;
        }
        .hero-card .card-body p {
            font-size: 14px;
            color: #5e5e6e;
            line-height: 1.75;
        }
        .hero-card .card-body p strong {
            color: #6b5aab;
            font-weight: 500;
        }
        .hero-card hr {
            border-color: #d5caf0;
        }
        .welcome-text {
            color: #7c6db5 !important;
            font-size: 14px;
        }
        .stat-card {
            border-radius: 12px;
            border: 0.5px solid #c4b8ef;
            background-color: #f3f0fb;
            transition: transform 0.18s, border-color 0.18s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            border-color: #9b8ed4;
        }
        .stat-icon {
            font-size: 22px;
            color: #9b8ed4;
            margin-bottom: 8px;
        }
        .stat-label {
            font-size: 12px;
            color: #8a7bbf;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }
        .stat-number {
            font-size: 30px;
            font-weight: 500;
            color: #5a4a96;
            line-height: 1;
        }
    </style>
@endsection
@section('content')
<div class="container mt-4">
    {{-- Header --}}
    <div class="card hero-card shadow-sm mb-4">
        <div class="card-header">
            <h3 class="mb-0">
                <i class="ti ti-map-2 me-2"></i>WebGIS CRUD
            </h3>
        </div>
        <div class="card-body">
            <p>
                Aplikasi ini merupakan hasil dari tugas akhir mata kuliah
                <strong>Pemrograman Geospasial Web Lanjut (PGWL)</strong>
                yang disusun oleh mahasiswa D4 SIG 2023.
            </p>
            <p>
                Aplikasi ini bertujuan untuk memberikan pengalaman belajar
                yang interaktif dan menyenangkan dalam memahami konsep-konsep
                geospasial melalui penggunaan teknologi web.
            </p>
            <hr>
            <p class="mb-1 welcome-text">Selamat datang di halaman utama PGWL.</p>
            <p class="mb-1" style="font-size:14px; color:#5e5e6e;">
                Silakan jelajahi fitur-fitur yang tersedia dan nikmati pengalaman menggunakan aplikasi ini.
            </p>
            <p class="mb-0" style="font-size:14px; color:#5e5e6e;">
                Jika ada pertanyaan atau masukan, jangan ragu untuk menghubungi kami.
            </p>
        </div>
    </div>
    {{-- Statistik --}}
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="stat-icon"><i class="ti ti-map-pin"></i></div>
                    <p class="stat-label mb-1">Jumlah Point</p>
                    <p class="stat-number mb-0">
                        {{ $points_count ?? 0 }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="stat-icon"><i class="ti ti-line"></i></div>
                    <p class="stat-label mb-1">Jumlah Polyline</p>
                    <p class="stat-number mb-0">
                        {{ $polylines_count ?? 0 }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="stat-icon"><i class="ti ti-hexagon"></i></div>
                    <p class="stat-label mb-1">Jumlah Polygon</p>
                    <p class="stat-number mb-0">
                        {{ $polygons_count ?? 0 }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="stat-icon"><i class="ti ti-users"></i></div>
                    <p class="stat-label mb-1">Jumlah User</p>
                    <p class="stat-number mb-0">
                        {{ $users_count ?? 0 }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
