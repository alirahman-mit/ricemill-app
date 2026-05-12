@extends('layouts.ricemill')

@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Keuangan')
@section('breadcrumb', 'Dashboard / Keuangan')

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(22, 163, 74, 0.1); color: #16a34a;">
                <i data-lucide="arrow-down-circle"></i>
            </div>
            <div class="stat-value">Rp {{ number_format($keuangan->where('tipe', 'pemasukan')->sum('jumlah'), 0, ',', '.') }}</div>
            <div class="stat-label">Total Pemasukan</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(220, 38, 38, 0.1); color: #dc2626;">
                <i data-lucide="arrow-up-circle"></i>
            </div>
            <div class="stat-value">Rp {{ number_format($keuangan->where('tipe', 'pengeluaran')->sum('jumlah'), 0, ',', '.') }}</div>
            <div class="stat-label">Total Pengeluaran</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <i data-lucide="wallet"></i>
            </div>
            @php
                $saldo = $keuangan->where('tipe', 'pemasukan')->sum('jumlah') - $keuangan->where('tipe', 'pengeluaran')->sum('jumlah');
            @endphp
            <div class="stat-value">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
            <div class="stat-label">Saldo Kas</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header-clean">
        <h5>Mutasi Kas Rice Mill</h5>
        <button class="btn-primary-custom btn-sm"><i data-lucide="plus"></i> Catat Transaksi</button>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Kategori</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($keuangan as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="fw-medium">{{ $item->keterangan }}</td>
                    <td>{{ $item->kategori ?? 'Operasional' }}</td>
                    <td>
                        <span class="text-{{ $item->tipe == 'pemasukan' ? 'success' : 'danger' }} fw-bold">
                            {{ ucfirst($item->tipe) }}
                        </span>
                    </td>
                    <td class="fw-bold">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td><span class="badge-custom badge-success-custom">Valid</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i data-lucide="bar-chart-2" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></i>
                        <p>Belum ada data transaksi keuangan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($keuangan->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $keuangan->links() }}
    </div>
    @endif
</div>
@endsection
