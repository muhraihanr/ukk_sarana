@extends('layouts.stisla-dashboard')

@section('title', 'Semua Laporan - Admin')

@section('header', 'Semua Laporan')

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
                <h4>Daftar Laporan</h4>
                <div class="card-header-action d-flex align-items-center">
                    <!-- Input Pencarian Real-time -->
                    <input type="text"
                        id="searchInput"
                        class="form-control mr-2"
                        placeholder="Cari lokasi, kategori, pelapor, atau kelas..."
                        style="min-width: 300px;"
                        value="{{ request('cari') }}">

                    <!-- Filter Status -->
                    <select id="statusFilter" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="masuk" {{ request('status') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
            </div>

            <div class="card-body p-0">
                <!-- Container untuk hasil laporan -->
                <div id="laporanContainer">
                    @include('admin.laporan.partials.laporan-table', ['laporans' => $laporans, 'status' => $status ?? ''])
                </div>
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
                setTimeout(() => alert.remove(), 150);
            }, 3000);
        }

        // === MODERN SEARCH & FILTER ===
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const laporanContainer = document.getElementById('laporanContainer');
        const loadingSpinner = document.createElement('div');
        loadingSpinner.className = 'text-center py-4';
        loadingSpinner.innerHTML = `
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <p class="mt-2 text-muted">Memproses...</p>
    `;

        // Fungsi debounce modern
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

        // Fungsi untuk membangun URL query
        function buildUrl() {
            const search = searchInput.value.trim();
            const status = statusFilter.value;
            let url = "{{ route('admin.laporan.index') }}";
            const params = [];

            if (search) params.push(`cari=${encodeURIComponent(search)}`);
            if (status) params.push(`status=${encodeURIComponent(status)}`);

            if (params.length > 0) {
                url += '?' + params.join('&');
            }

            return url;
        }

        // Fungsi pencarian dengan efek smooth
        const handleSearchFilter = debounce(async function() {
            const search = searchInput.value.trim();
            const status = statusFilter.value;

            // Jika tidak ada filter, reload halaman dengan smooth scroll
            if (!search && !status) {
                window.location.href = "{{ route('admin.laporan.index') }}";
                return;
            }

            // Tampilkan loading dengan animasi
            laporanContainer.style.opacity = '0.7';
            laporanContainer.style.transition = 'opacity 0.3s ease';
            laporanContainer.innerHTML = '';
            laporanContainer.appendChild(loadingSpinner);

            try {
                const response = await fetch(buildUrl(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const html = await response.text();

                // Hapus loading
                laporanContainer.innerHTML = '';

                // Buat container sementara untuk efek fade-in
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;

                // Tambahkan kelas untuk animasi
                tempDiv.classList.add('fade-in');
                laporanContainer.appendChild(tempDiv);

                // Scroll ke atas dengan smooth
                window.scrollTo({
                    top: laporanContainer.offsetTop - 60,
                    behavior: 'smooth'
                });

                // Reset opacity setelah animasi
                setTimeout(() => {
                    laporanContainer.style.opacity = '1';
                    laporanContainer.style.transition = 'opacity 0.4s ease';
                }, 300);

            } catch (error) {
                console.error('Error:', error);
                laporanContainer.innerHTML = `
                <div class="text-center py-4">
                    <div class="text-danger mb-2">
                        <i class="fas fa-exclamation-circle fa-2x"></i>
                    </div>
                    <p class="text-danger font-weight-bold">Terjadi kesalahan saat mencari</p>
                    <button class="btn btn-outline-primary mt-2" onclick="location.reload()">
                        Muat Ulang
                    </button>
                </div>
            `;
                laporanContainer.style.opacity = '1';
            }
        }, 300);

        // Event listeners
        if (searchInput) {
            searchInput.addEventListener('input', handleSearchFilter);
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', handleSearchFilter);
        }

        // Tambahkan CSS animasi
        const style = document.createElement('style');
        style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }
        .spinner-border {
            width: 2rem;
            height: 2rem;
        }
        .card-footer {
            transition: all 0.3s ease;
        }
    `;
        document.head.appendChild(style);

        // === INISIALISASI DROPDOWN UNTUK BOOTSTRAP 4 ===
        function initDropdowns() {
            // Hapus event listener lama
            document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
                toggle.removeEventListener('click', handleDropdown);
                toggle.addEventListener('click', handleDropdown);
            });
        }

        function handleDropdown(e) {
            e.preventDefault();
            const parent = this.closest('.btn-group');
            if (parent) {
                // Sembunyikan semua dropdown lain
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
                // Tampilkan dropdown ini
                const menu = parent.querySelector('.dropdown-menu');
                if (menu) {
                    menu.classList.toggle('show');
                }
            }
        }

        // Inisialisasi awal
        initDropdowns();

        // Tambahkan event listener untuk klik di luar dropdown
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.btn-group')) {
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });

        // === MODIFIKASI FUNGSI HANDLE SEARCH FILTER ===
        // Tambahkan inisialisasi dropdown setelah konten dimuat
        const originalHandleSearchFilter = handleSearchFilter;
        handleSearchFilter = debounce(async function() {
            await originalHandleSearchFilter();
            // Inisialisasi ulang dropdown setelah konten baru dimuat
            setTimeout(initDropdowns, 100);
        }, 300);
    });
</script>
@endpush