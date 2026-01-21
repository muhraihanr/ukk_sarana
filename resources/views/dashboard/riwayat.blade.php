@extends('layouts.stisla-dashboard')

@section('title', 'Riwayat Laporan - Laporan Sarana')

@section('header', 'Riwayat Laporan Anda')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-history"></i> Riwayat Laporan</h4>
            </div>
            <div class="card-body p-0">
                @if($laporans->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>Belum ada laporan.</p>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Lokasi</th>
                                <th>Kategori</th>
                                <th>Keterangan</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laporans as $index => $laporan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ Str::limit($laporan->lokasi, 30) }}</td>
                                <td>
                                    {{ $laporan->kategori?->ket_kategori ?? 'Umum' }}
                                </td>
                                <td>{{ Str::limit($laporan->ket, 50) }}</td>
                                <td>
                                    {{ $laporan->created_at ? $laporan->created_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td>
                                    <a href="{{ route('pelaporan.show', $laporan->id_pelaporan) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('pelaporan.edit', $laporan->id_pelaporan) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger delete-btn"
                                        data-id="{{ $laporan->id_pelaporan }}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
                        // Buat form tersembunyi dan submit
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