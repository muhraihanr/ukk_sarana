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
                    <!-- Input Pencarian Real-time -->
                    <input type="text" 
                           id="searchInput" 
                           class="form-control mb-2" 
                           placeholder="Cari nama/NIS/kelas..." 
                           value="{{ request('cari') }}">
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

                <!-- Container untuk hasil siswa -->
                <div id="siswaContainer">
                    @include('admin.siswa.partials.siswa-table', ['siswas' => $siswas])
                </div>
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

    // === FITUR PENCARIAN REAL-TIME ===
    const searchInput = document.getElementById('searchInput');
    const siswaContainer = document.getElementById('siswaContainer');

    // Fungsi debounce
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Fungsi pencarian
    const handleSearch = debounce(function() {
        const query = searchInput.value.trim();
        
        // Jika input kosong, reload halaman
        if (query === '') {
            window.location.href = "{{ route('admin.siswa.index') }}";
            return;
        }

        // Tampilkan loading
        siswaContainer.innerHTML = '<div class="text-center py-4"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>';

        // Kirim AJAX GET request
        fetch("{{ route('admin.siswa.index') }}?cari=" + encodeURIComponent(query), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            siswaContainer.innerHTML = html;
            // Re-inisialisasi SweetAlert setelah konten dimuat ulang
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
        })
        .catch(error => {
            console.error('Error:', error);
            siswaContainer.innerHTML = '<div class="text-center py-4 text-danger">Terjadi kesalahan saat mencari.</div>';
        });
    }, 300);

    if (searchInput) {
        searchInput.addEventListener('input', handleSearch);
    }
});
</script>
@endpush