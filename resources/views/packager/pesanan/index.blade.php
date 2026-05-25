@extends('layouts.packager')

@section('title', 'Pesanan Masuk')
@section('page-title', 'Pesanan Masuk')
@section('breadcrumb', 'Dashboard / Pesanan')

@section('topbar-actions')
<a href="{{ route('packager.pesanan.create') }}" class="btn-primary-custom">
    <span class="iconify" data-icon="heroicons:plus-circle"></span> Input Pesanan Manual
</a>
@endsection

@section('content')

{{-- STATISTIK RINGKAS --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(234, 179, 8, 0.12); color: #ca8a04;">
                <span class="iconify" data-icon="heroicons:star"></span>
            </div>
            <div>
                <div class="stat-value" style="font-size:1.1rem;">
                    @if($terlaris)
                        {{ $terlaris->jenis_produk }}
                    @else
                        <span style="font-size:.85rem;color:#9ca3af;">Belum ada data</span>
                    @endif
                </div>
                <div class="stat-label">🏆 Produk Terlaris</div>
                @if($terlaris)
                    <div style="font-size:.78rem;color:#6b7280;margin-top:2px;">
                        Total terjual: <strong>{{ number_format($terlaris->total_qty) }} pcs</strong>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(22, 163, 74, 0.1); color: #16a34a;">
                <span class="iconify" data-icon="heroicons:shopping-bag"></span>
            </div>
            <div>
                <div class="stat-value">{{ $pesanan->total() }}</div>
                <div class="stat-label">Total Pesanan</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <span class="iconify" data-icon="heroicons:banknotes"></span>
            </div>
            <div>
                <div class="stat-value" style="font-size:1.05rem;">
                    Rp {{ number_format($pesanan->sum('total_harga'), 0, ',', '.') }}
                </div>
                <div class="stat-label">Total Pendapatan (halaman ini)</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header-clean">
        <h5>Daftar Pesanan dari Pelanggan</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Tgl Pesanan</th>
                    <th>ID Pesanan</th>
                    <th>Nama Pelanggan</th>
                    <th>Detail Produk</th>
                    <th>Total Bayar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanan as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="fw-medium">#ORD-{{ $item->id }}</td>
                    <td>{{ $item->nama_pelanggan }}</td>
                    <td>{{ $item->jenis_produk }} ({{ $item->jumlah }} Pcs)</td>
                    <td class="fw-bold">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge-custom {{ $item->status == 'selesai' ? 'badge-success-custom' : ($item->status == 'dibatalkan' ? 'badge-danger-custom' : 'badge-warning-custom') }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('packager.pesanan.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-outline-custom btn-sm text-danger" style="border-color:#f5b8b8;">
                                <span class="iconify" data-icon="heroicons:trash" style="width:14px;height:14px;"></span>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <span class="iconify" data-icon="heroicons:shopping-cart" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></span>
                        <p>Belum ada pesanan masuk.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pesanan->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $pesanan->links() }}
    </div>
    @endif
</div>
@endsection
