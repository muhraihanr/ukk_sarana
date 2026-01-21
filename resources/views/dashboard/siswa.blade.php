@extends('layouts.stisla-dashboard')

@section('title', 'Dashboard Siswa - Laporan Sarana')

@section('header', 'Riwayat Laporan Anda')

@section('content')
<div class="row">
    <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Buat Laporan Baru</h4>
            </div>
            <div class="card-body">
                <a href="{{ route('pelaporan.create') }}" class="btn btn-success btn-lg btn-block">
                    <i class="fas fa-bullhorn"></i> Laporkan Sarana Rusak
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Cari Laporan</h4>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('dashboard.siswa') }}">
                    <div class="input-group mb-3">
                        <input type="text" name="cari" class="form-control" placeholder="Cari lokasi atau keterangan..." value="{{ request('cari') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="section-title">Riwayat Laporan Anda</div>
    </div>
</div>

<div class="row">
    @if($laporans->isEmpty())
    <div class="col-12 text-center py-5">
        <div class="text-muted">
            <i class="fas fa-inbox fa-3x mb-3"></i>
            <p>Belum ada laporan. Silakan laporkan sarana yang rusak!</p>
        </div>
    </div>
    @else
    @foreach($laporans as $laporan)
    <div class="col-12 col-sm-6 col-md-4">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('pelaporan.show', $laporan->id_pelaporan) }}" style="text-decoration: none; color: inherit;">
                    <h6 class="card-title">{{ Str::limit($laporan->lokasi, 30) }}</h6>
                    <p class="card-text">{{ Str::limit($laporan->ket, 80) }}</p>
                    <div class="media">
                        <img class="mr-3 rounded-circle" width="30" src="{{ asset('assets/img/avatar/avatar-1.png') }}" alt="Avatar">
                        <div class="media-body">
                            <div class="text-small font-weight-bold">{{ $laporan->nama }}</div>
                            <div class="text-muted">
                                {{ $laporan->created_at ? $laporan->created_at->format('d M Y, H:i') : '-' }}
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge badge-secondary">
                            {{ $laporan->kategori?->ket_kategori ?? 'Umum' }}
                        </span>
                        <!-- Badge Status Dinamis -->
                        @if($laporan->status === 'masuk')
                        <span class="badge badge-warning">Aduan Masuk</span>
                        @elseif($laporan->status === 'diproses')
                        <span class="badge badge-info">Diproses</span>
                        @else
                        <span class="badge badge-success">Selesai</span>
                        @endif
                    </div>  
                </a>
            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const url = "{{ route('pelaporan.destroy', ':id') }}".replace(':id', id);

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Laporan ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#aaa',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = url;

                        const csrf = document.createElement('input');
                        csrf.type = 'hidden';
                        csrf.name = '_token';
                        csrf.value = '{{ csrf_token() }}';

                        const method = document.createElement('input');
                        method.type = 'hidden';
                        method.name = '_method';
                        method.value = 'DELETE';

                        form.appendChild(csrf);
                        form.appendChild(method);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection