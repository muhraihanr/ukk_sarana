@extends('layouts.stisla-dashboard')

@section('title', 'Data Kategori - Admin')

@section('header', 'Data Kategori')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header flex-column align-items-stretch">
                <h4>Daftar Kategori</h4>
                <div class="card-header-actions ml-auto d-flex flex-column align-items-end">
                    <!-- Input Pencarian Real-time -->
                    <input type="text" 
                           id="searchInput" 
                           class="form-control mb-2" 
                           placeholder="Cari kategori..." 
                           value="{{ request('cari') }}">
                    <a href="{{ route('admin.kategori.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tambah Kategori
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
                @elseif(session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                <!-- Container untuk hasil kategori -->
                <div id="kategoriContainer">
                    @include('admin.kategori.partials.kategori-table', ['kategoris' => $kategoris])
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
            const url = "{{ route('admin.kategori.destroy', ':id') }}".replace(':id', id);

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Kategori ini akan dihapus permanen!",
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
    const kategoriContainer = document.getElementById('kategoriContainer');

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
            window.location.href = "{{ route('admin.kategori.index') }}";
            return;
        }

        // Tampilkan loading
        kategoriContainer.innerHTML = '<div class="text-center py-4"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>';

        // Kirim AJAX GET request
        fetch("{{ route('admin.kategori.index') }}?cari=" + encodeURIComponent(query), {
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
            kategoriContainer.innerHTML = html;
            // Re-inisialisasi SweetAlert setelah konten dimuat ulang
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const url = "{{ route('admin.kategori.destroy', ':id') }}".replace(':id', id);

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Kategori ini akan dihapus permanen!",
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
            kategoriContainer.innerHTML = '<div class="text-center py-4 text-danger">Terjadi kesalahan saat mencari.</div>';
        });
    }, 300);

    if (searchInput) {
        searchInput.addEventListener('input', handleSearch);
    }
});
</script>
@endpush