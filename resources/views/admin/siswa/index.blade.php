@extends('layouts.stisla-dashboard')

@section('title', 'Data Siswa - Admin')

@section('header', 'Data Siswa')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header flex-column align-items-stretch">
                <h4>Daftar Siswa</h4>
                <div class="card-header-actions ml-auto d-flex flex-column align-items-end">
                    <form method="GET" action="{{ route('admin.siswa.index') }}" class="w-100 mb-2">
                        <div class="input-group">
                            <input type="text" name="cari" class="form-control mr-2" placeholder="Cari nama/NIS/kelas..." value="{{ request('cari') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                @if(request('cari'))
                                <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary ml-1" title="Reset Pencarian">
                                    <i class="fas fa-times"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                    <a href="{{ route('admin.siswa.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tambah Siswa
                    </a>
                </div>
            </div>

            <div class="card-body p-0">
                @if(session('success'))
                <div id="autoHideAlert" class="alert alert-success alert-dismissible fade show m-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if($siswas->isEmpty())
                <div class="text-center py-4 text-muted">Tidak ada data siswa.</div>
                @else
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>NIS</th>
                                <th>Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswas as $siswa)
                            <tr>
                                <td>{{ $loop->iteration + ($siswas->currentPage() - 1) * $siswas->perPage() }}</td>
                                <td>{{ $siswa->nama }}</td>
                                <td>{{ $siswa->nis }}</td>
                                <td>{{ $siswa->kelas }}</td>
                                <td>
                                    <a href="{{ route('admin.siswa.edit', $siswa) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!-- Tombol Hapus dengan SweetAlert -->
                                    <button type="button" 
                                            class="btn btn-sm btn-danger delete-btn" 
                                            data-id="{{ $siswa->id }}"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    {{ $siswas->appends(request()->query())->links() }}
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
        // Auto-hide success alert
        const alert = document.getElementById('autoHideAlert');
        if (alert) {
            setTimeout(() => {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 150);
            }, 3000);
        }

        // SweetAlert untuk tombol hapus
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const url = "{{ route('admin.siswa.destroy', ':id') }}".replace(':id', id);

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data siswa ini akan dihapus permanen!",
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