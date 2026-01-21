@extends('layouts.stisla-dashboard')

@section('title', 'Detail Laporan - Laporan Sarana')

@section('header', 'Detail Laporan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-file-alt"></i> Detail Laporan</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>Lokasi</th>
                                <td>{{ $laporan->lokasi }}</td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td>
                                    <span class="badge badge-secondary">{{ $laporan->kategori?->ket_kategori ?? 'Umum' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td>
                                    {{ $laporan->created_at ? $laporan->created_at->format('d M Y, H:i') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($laporan->status === 'masuk')
                                    <span class="badge badge-warning">Aduan Masuk</span>
                                    @elseif($laporan->status === 'diproses')
                                    <span class="badge badge-info">Diproses</span>
                                    @else
                                    <span class="badge badge-success">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <strong>Nama Pelapor:</strong> {{ $laporan->nama }}<br>

                        @if(Session::has('siswa_nis') && Session::get('siswa_nis') === $laporan->nis)
                        <strong>Kelas:</strong> {{ $laporan->kelas }}<br>
                        <strong>NIS:</strong> {{ $laporan->nis }}
                        @endif
                        <!-- Jika bukan pemilik, hanya tampilkan nama -->
                    </div>
                </div>

                <hr>

                <h5>Keterangan Lengkap</h5>
                <p class="text-justify">{{ $laporan->ket }}</p>

                @if($laporan->lampiran)
                <h5>Lampiran Gambar</h5>
                <div class="text-center mt-3">
                    <img src="{{ asset('uploads/laporan/' . $laporan->lampiran) }}"
                        alt="Lampiran Laporan"
                        class="img-fluid border rounded"
                        style="max-height: 400px; object-fit: contain;">
                </div>
                @endif

                @if(Session::has('siswa_nis') && Session::get('siswa_nis') === $laporan->nis)
                <div class="mt-4">
                    <a href="{{ route('pelaporan.edit', $laporan->id_pelaporan) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Laporan
                    </a>
                    <button type="button" class="btn btn-danger delete-btn"
                        data-id="{{ $laporan->id_pelaporan }}">
                        <i class="fas fa-trash"></i> Hapus Laporan
                    </button>
                    <!-- <a href="{{ route('dashboard.siswa') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                    </a> -->
                </div>
                @else
                <!-- <div class="mt-4">
                    <a href="{{ route('dashboard.siswa') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                    </a>
                </div> -->
                @endif

                <div class="mt-4">
                    @if(session('admin_id'))
                    <!-- Admin -->
                    <a href="{{ route('dashboard.admin') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard Admin
                    </a>
                    @elseif(session('siswa_nis'))
                    <!-- Siswa -->
                    <a href="{{ route('dashboard.siswa') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard Siswa
                    </a>
                    @else
                    <!-- Tidak login → ke halaman login -->
                    <a href="{{ route('login.form', 'siswa') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Login
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteBtn = document.querySelector('.delete-btn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const url = "{{ route('pelaporan.destroy', ':id') }}".replace(':id', id);

                Swal.fire({
                    title: 'Yakin ingin menghapus laporan ini?',
                    text: "Data tidak bisa dikembalikan setelah dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#aaa',
                    confirmButtonText: 'Ya, Hapus Sekarang!',
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
        }
    });
</script>
@endpush
@endsection