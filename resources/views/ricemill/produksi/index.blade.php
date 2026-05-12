@extends('layouts.ricemill')

@section('title', 'Riwayat Produksi')
@section('page-title', 'Riwayat Produksi')
@section('breadcrumb', 'Dashboard / Produksi')

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(22, 163, 74, 0.1); color: #16a34a;">
                <i data-lucide="package"></i>
            </div>
            <div class="stat-value">{{ number_format($produksi->sum('jumlah_beras'), 0, ',', '.') }} Kg</div>
            <div class="stat-label">Total Produksi Beras</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <i data-lucide="percent"></i>
            </div>
            @php
                $totalGabah = $produksi->sum('jumlah_gabah');
                $totalBeras = $produksi->sum('jumlah_beras');
                $rendemen = $totalGabah > 0 ? round(($totalBeras / $totalGabah) * 100, 1) : 0;
            @endphp
            <div class="stat-value">{{ $rendemen }}%</div>
            <div class="stat-label">Rata-rata Rendemen</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header-clean">
        <h5>Laporan Produksi Harian</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Batch</th>
                    <th>Input Gabah</th>
                    <th>Output Beras</th>
                    <th>Rendemen</th>
                    <th>Kualitas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produksi as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_proses)->format('d M Y') }}</td>
                    <td>#{{ $item->id }}</td>
                    <td>{{ number_format($item->jumlah_gabah, 0, ',', '.') }} Kg</td>
                    <td class="fw-bold text-success">{{ number_format($item->jumlah_beras, 0, ',', '.') }} Kg</td>
                    <td>
                        @php
                            $r = $item->jumlah_gabah > 0 ? round(($item->jumlah_beras / $item->jumlah_gabah) * 100, 1) : 0;
                        @endphp
                        {{ $r }}%
                    </td>
                    <td><span class="badge-custom badge-info-custom">{{ $item->kualitas ?? 'Premium' }}</span></td>
                    <td>
                        <button class="btn-outline-custom btn-sm"><i data-lucide="printer" style="width:14px;height:14px;"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i data-lucide="trending-up" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></i>
                        <p>Belum ada data riwayat produksi.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($produksi->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $produksi->links() }}
    </div>
    @endif
</div>
@endsection
