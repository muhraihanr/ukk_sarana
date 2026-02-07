@extends('layouts.stisla-dashboard')

@section('title', 'Dashboard Admin - Laporan Sarana')

@section('header', 'Dashboard Admin')

@section('content')
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
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
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-info">
                <i class="fas fa-cogs"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Diproses</h4>
                </div>
                <div class="card-body">
                    {{ $totalDiproses }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Selesai</h4>
                </div>
                <div class="card-body">
                    {{ $totalSelesai }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
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
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laporans as $index => $laporan)
                            <tr>
                                <td>{{ $loop->iteration + ($laporans->currentPage() - 1) * $laporans->perPage() }}</td>
                                <td>{{ Str::limit($laporan->lokasi, 30) }}</td>
                                <td>{{ $laporan->kategori?->ket_kategori ?? 'Umum' }}</td>
                                <td>{{ $laporan->nama }} ({{ $laporan->kelas }})</td>
                                <td>{{ $laporan->created_at ? $laporan->created_at->format('d M Y') : '-' }}</td>
                                <td>
                                    @if($laporan->status === 'masuk')
                                    <span class="badge badge-warning">Masuk</span>
                                    @elseif($laporan->status === 'diproses')
                                    <span class="badge badge-info">Diproses</span>
                                    @else
                                    <span class="badge badge-success">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <!-- Detail -->
                                        <a href="{{ route('pelaporan.show', $laporan->id_pelaporan) }}"
                                            class="btn btn-sm btn-info"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Ubah Status -->
                                        <div class="btn-group dropleft d-inline-block ml-1">
                                            <button type="button"
                                                class="btn btn-sm btn-secondary dropdown-toggle"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('admin.laporan.status', [$laporan->id_pelaporan, 'masuk']) }}">
                                                    <span class="badge badge-warning">Masuk</span>
                                                </a>
                                                <a class="dropdown-item" href="{{ route('admin.laporan.status', [$laporan->id_pelaporan, 'diproses']) }}">
                                                    <span class="badge badge-info">Diproses</span>
                                                </a>
                                                <a class="dropdown-item" href="{{ route('admin.laporan.status', [$laporan->id_pelaporan, 'selesai']) }}">
                                                    <span class="badge badge-success">Selesai</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                <!-- Pagination -->
                <div class="card-footer d-flex justify-content-end">
                    {{ $laporans->links('vendor.pagination.stisla') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inisialisasi dropdown
    document.addEventListener('DOMContentLoaded', function() {
        // Dropdown toggle
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const menu = this.nextElementSibling;
                if (menu && menu.classList.contains('dropdown-menu')) {
                    // Tutup semua dropdown lain
                    document.querySelectorAll('.dropdown-menu.show').forEach(m => {
                        if (m !== menu) m.classList.remove('show');
                    });
                    menu.classList.toggle('show');
                }
            });
        });

        // Tutup dropdown saat klik di luar
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.btn-group')) {
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });
    });
</script>
@endpush