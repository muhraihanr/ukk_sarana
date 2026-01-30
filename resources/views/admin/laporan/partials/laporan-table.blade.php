@if($laporans->isEmpty())
<div class="text-center py-4 text-muted">Tidak ada laporan.</div>
@else
<div class="table-responsive">
    <table class="table table-striped mb-0">
        <thead>
            <tr>
                <th>No</th>
                <th>Lokasi</th>
                <th>Kategori</th>
                <th>Pelapor</th>
                <th>Kelas</th>
                <th>Status</th>
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
                <td>
                    @if($laporan->status === 'masuk')
                    <span class="badge badge-warning">Masuk</span>
                    @elseif($laporan->status === 'diproses')
                    <span class="badge badge-info">Diproses</span>
                    @else
                    <span class="badge badge-success">Selesai</span>
                    @endif
                </td>
                <td>{{ $laporan->created_at ? $laporan->created_at->format('d M Y') : '-' }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <!-- Detail -->
                        <a href="{{ route('pelaporan.show', $laporan->id_pelaporan) }}"
                            class="btn btn-sm btn-info"
                            title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </a>

                        <!-- Status Dropdown -->
                        <div class="btn-group dropleft d-inline-block ml-1">
                            <button type="button"
                                class="btn btn-sm btn-secondary dropdown-toggle"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fas fa-cog"></i>
                            </button>
                            <div class="dropdown-menu" style="max-height: 200px; overflow-y: auto;">
                                <a class="dropdown-item" href="{{ route('admin.laporan.status', [$laporan->id_pelaporan, 'masuk']) }}">
                                    <span class="badge badge-warning">Masuk</span>
                                </a>
                                <a class="dropdown-item" href="{{ route('admin.laporan.status', [$laporan->id_pelaporan, 'diproses']) }}">
                                    <span class="badge badge-info">Diproses</span>
                                </a>
                                <a class="dropdown-item" href="{{ route('admin.laporan.status', [$laporan->id_pelaporan, 'selesai']) }}">
                                    <span class="badge badge-success">Selesai</span>
                                </a>
                                <!-- Tambahkan lebih banyak jika perlu -->
                            </div>
                        </div>

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
    {{ $laporans->appends(request()->query())->render('vendor.pagination.stisla') }}
</div>
@endif