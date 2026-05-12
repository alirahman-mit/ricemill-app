@extends('layouts.ricemill')

@section('title', 'Pengiriman Beras')
@section('page-title', 'Pengiriman Beras')
@section('breadcrumb', 'Dashboard / Pengiriman')

@section('topbar-actions')
<button class="btn-primary-custom">
    <i data-lucide="truck"></i> Buat Pengiriman
</button>
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
                    <td class="fw-medium">{{ $item->no_surat_jalan ?? 'SJ-'.time().'-'.$item->id }}</td>
                    <td>{{ $item->tujuan ?? 'Packager Utama' }}</td>
                    <td>{{ number_format($item->jumlah_beras, 0, ',', '.') }} Kg</td>
                    <td>{{ $item->kendaraan ?? 'Truck AB 1234' }}</td>
                    <td>
                        @if($item->status == 'diterima')
                            <span class="badge-custom badge-success-custom">Diterima</span>
                        @elseif($item->status == 'dikirim')
                            <span class="badge-custom badge-info-custom">Dikirim</span>
                        @else
                            <span class="badge-custom badge-warning-custom">Menunggu</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn-outline-custom btn-sm" title="Tracking"><i data-lucide="map-pin" style="width:14px;height:14px;"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i data-lucide="truck" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></i>
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
