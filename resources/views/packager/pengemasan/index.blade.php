@extends('layouts.packager')

@section('title', 'Hasil Pengemasan')
@section('page-title', 'Hasil Pengemasan')
@section('breadcrumb', 'Dashboard / Pengemasan')

@section('topbar-actions')
<button class="btn-primary-custom">
    <i data-lucide="package-plus"></i> Catat Pengemasan
</button>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                <i data-lucide="package"></i>
            </div>
            <div class="stat-value">{{ number_format($pengemasan->count(), 0, ',', '.') }}</div>
            <div class="stat-label">Total Batch Kemasan</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header-clean">
        <h5>Riwayat Pengemasan Beras</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Batch ID</th>
                    <th>Ukuran Kemasan</th>
                    <th>Jumlah Pack</th>
                    <th>Total Berat (Kg)</th>
                    <th>Merek</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengemasan as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_kemas)->format('d M Y') }}</td>
                    <td class="fw-medium">#PKG-{{ $item->id }}</td>
                    <td>{{ $item->ukuran_kemasan }}</td>
                    <td>{{ number_format($item->jumlah_pack, 0, ',', '.') }} Pcs</td>
                    <td>{{ number_format($item->total_berat, 0, ',', '.') }} Kg</td>
                    <td>{{ $item->merek ?? 'Beras Kita' }}</td>
                    <td>
                        <button class="btn-outline-custom btn-sm"><i data-lucide="printer" style="width:14px;height:14px;"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i data-lucide="package-2" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></i>
                        <p>Belum ada data pengemasan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pengemasan->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $pengemasan->links() }}
    </div>
    @endif
</div>
@endsection
