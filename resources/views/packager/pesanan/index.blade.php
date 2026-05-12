@extends('layouts.packager')

@section('title', 'Pesanan Masuk')
@section('page-title', 'Pesanan Masuk')
@section('breadcrumb', 'Dashboard / Pesanan')

@section('content')
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
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pesanan)->format('d M Y') }}</td>
                    <td class="fw-medium">#ORD-{{ $item->id }}</td>
                    <td>{{ $item->nama_pelanggan }}</td>
                    <td>{{ $item->produk }} ({{ $item->jumlah }} {{ $item->satuan ?? 'Kg' }})</td>
                    <td class="fw-bold">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td>
                        @if($item->status == 'selesai')
                            <span class="badge-custom badge-success-custom">Selesai</span>
                        @elseif($item->status == 'proses')
                            <span class="badge-custom badge-info-custom">Proses</span>
                        @else
                            <span class="badge-custom badge-warning-custom">Pending</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn-outline-custom btn-sm" title="Proses"><i data-lucide="check-circle" style="width:14px;height:14px;"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i data-lucide="shopping-cart" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></i>
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
