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
                <!-- Input Pencarian Real-time -->
                <input type="text"
                    id="searchInput"
                    class="form-control"
                    placeholder="Cari lokasi atau keterangan...">
                <small class="text-muted">Ketik untuk mencari laporan secara langsung</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="section-title">Riwayat Laporan Anda</div>
    </div>
</div>

<!-- Container untuk hasil laporan -->
<div class="row" id="laporanContainer">
    @include('dashboard.partials.laporan-cards', ['laporans' => $laporans])
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const laporanContainer = document.getElementById('laporanContainer');

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

            // Jika input kosong, reload halaman penuh
            if (query === '') {
                window.location.href = "{{ route('dashboard.siswa') }}";
                return;
            }

            // Tampilkan loading
            laporanContainer.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>';

            // Kirim AJAX GET request
            fetch("{{ route('dashboard.siswa') }}?cari=" + encodeURIComponent(query), {
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
                    laporanContainer.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    laporanContainer.innerHTML = '<div class="col-12 text-center py-5"><div class="text-danger">Terjadi kesalahan saat mencari.</div></div>';
                });
        }, 300);

        if (searchInput) {
            searchInput.addEventListener('input', handleSearch);
        }
    });
</script>
@endpush