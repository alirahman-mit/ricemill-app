@extends('layouts.ricemill')

@section('title', 'Pengiriman Beras')
@section('page-title', 'Pengiriman Beras')
@section('breadcrumb', 'Dashboard / Pengiriman')

@section('topbar-actions')
<a href="{{ route('ricemill.pengiriman.create') }}" class="btn-primary-custom">
    <span class="iconify" data-icon="heroicons:truck"></span> Buat Pengiriman
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header-clean">
        <h5>Log Pengiriman ke Packager</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Tanggal Kirim</th>
                    <th>No. Surat Jalan</th>
                    <th>Tujuan</th>
                    <th>Jumlah (Kg)</th>
                    <th>Kendaraan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengiriman as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_kirim)->format('d M Y') }}</td>
                    <td class="fw-medium">#SJ-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $item->nama_packager }}</td>
                    <td>{{ number_format($item->jumlah_karung * $item->berat_per_karung, 0, ',', '.') }} Kg</td>
                    <td>{{ $item->jenis_beras }}</td>
                    <td>
                        @if($item->status == 'diterima')
                            <span class="badge-custom badge-success-custom">Diterima</span>
                        @elseif($item->status == 'dikirim')
                            <span class="badge-custom badge-info-custom">Dikirim</span>
                        @elseif($item->status == 'ditolak')
                            <span class="badge-custom badge-danger-custom">Ditolak</span>
                        @else
                            <span class="badge-custom badge-warning-custom">Menunggu</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('ricemill.pengiriman.edit', $item) }}" class="btn-outline-custom btn-sm"
                               title="Edit">
                                <span class="iconify" data-icon="heroicons:pencil" style="width:14px;height:14px;"></span>
                            </a>
                            <form action="{{ route('ricemill.pengiriman.destroy', $item) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus data pengiriman ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-outline-custom btn-sm"
                                        style="color:#c0392b;border-color:#f5b8b8;" title="Hapus">
                                    <span class="iconify" data-icon="heroicons:trash" style="width:14px;height:14px;"></span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <span class="iconify" data-icon="heroicons:truck" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></span>
                        <p>Belum ada data pengiriman.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pengiriman->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $pengiriman->links() }}
    </div>
    @endif
</div>
@endsection
