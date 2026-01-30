@if($laporans->isEmpty())
<div class="col-12 text-center py-5">
    <div class="text-muted">
        <i class="fas fa-inbox fa-3x mb-3"></i>
        <p>Tidak ada laporan ditemukan.</p>
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