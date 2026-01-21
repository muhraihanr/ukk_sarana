@extends('layouts.stisla-dashboard')

@section('title', 'Laporan Selesai - Admin')

@section('header', 'Laporan Selesai')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            @if(session('success'))
            <div id="autoHideAlert" class="alert alert-success alert-dismissible fade show mx-4" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            <div class="card-header">
                <h4>Daftar Laporan Selesai</h4>
            </div>

            <div class="card-body p-0">
                @if($laporans->isEmpty())
                <div class="text-center py-4 text-muted">Tidak ada laporan yang telah selesai.</div>
                @else
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lokasi</th>
                                <th>Kategori</th>
                                <th>Pelapor</th>
                                <th>Kelas</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laporans as $laporan)
                            <tr>
                                <td>{{ $loop->iteration + ($laporans->currentPage() - 1) * $laporans->perPage() }}</td>
                                <td>{{ Str::limit($laporan->lokasi, 30) }}</td>
                                <td>{{ $laporan->kategori?->ket_kategori ?? 'Umum' }}</td>
                                <td>{{ $laporan->nama }}</td>
                                <td>{{ $laporan->kelas }}</td>
                                <td>{{ $laporan->created_at ? $laporan->created_at->format('d M Y') : '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <!-- Detail -->
                                        <a href="{{ route('pelaporan.show', $laporan->id_pelaporan) }}"
                                            class="btn btn-sm btn-info"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Hapus -->
                                        <form action="{{ route('admin.laporan.destroy', $laporan->id_pelaporan) }}"
                                            method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Yakin hapus laporan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-danger ml-1"
                                                title="Hapus Laporan">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    {{ $laporans->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.getElementById('autoHideAlert');
        if (alert) {
            setTimeout(() => {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 150); // hapus dari DOM setelah animasi
            }, 3000); // 3 detik
        }
    });
</script>
@endpush