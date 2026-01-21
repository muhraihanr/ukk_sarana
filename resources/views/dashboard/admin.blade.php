@extends('layouts.stisla-dashboard')

@section('title', 'Dashboard Admin - Laporan Sarana')

@section('header', 'Dashboard Admin')

@section('content')
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="fas fa-inbox"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Pengaduan Masuk</h4>
                </div>
                <div class="card-body">
                    {{ $totalMasuk }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Pengaduan Selesai</h4>
                </div>
                <div class="card-body">
                    {{ $totalSelesai }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Laporan Terbaru</h4>
            </div>
            <div class="card-body p-0">
                @if($laporans->isEmpty())
                    <div class="text-center py-4 text-muted">Tidak ada laporan.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Lokasi</th>
                                    <th>Kategori</th>
                                    <th>Pelapor</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($laporans as $index => $laporan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ Str::limit($laporan->lokasi, 30) }}</td>
                                    <td>{{ $laporan->kategori?->ket_kategori ?? 'Umum' }}</td>
                                    <td>{{ $laporan->nama }} ({{ $laporan->kelas }})</td>
                                    <td>{{ $laporan->created_at ? $laporan->created_at->format('d M Y') : '-' }}</td>
                                    <td>
                                        <a href="{{ route('pelaporan.show', $laporan->id_pelaporan) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <!-- Nanti tambah tombol "Selesai" -->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection