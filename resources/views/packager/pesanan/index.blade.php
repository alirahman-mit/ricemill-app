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
    <!-- Kolom Kiri: Statistik Utama -->
    <div class="col-md-8">
        <div class="row h-100">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="stat-card h-100">
                    <div class="stat-icon" style="background: rgba(22, 163, 74, 0.1); color: #16a34a;">
                        <span class="iconify" data-icon="heroicons:inbox-stack"></span>
                    </div>
                    <div>
                        <div class="stat-value" style="font-size:1.1rem;">{{ number_format($total_stok_beras, 0, ',', '.') }} kg</div>
                        <div class="stat-label">Stok Beras (Ricemill)</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="stat-card h-100">
                    <div class="stat-icon" style="background: rgba(234, 179, 8, 0.12); color: #ca8a04;">
                        <span class="iconify" data-icon="heroicons:star"></span>
                    </div>
                    <div>
                        <div class="stat-value" style="font-size:1.1rem;">
                            @if($terlaris)
                                {{ \Illuminate\Support\Str::limit($terlaris->jenis_produk, 15) }}
                            @else
                                <span style="font-size:.85rem;color:#9ca3af;">Belum ada</span>
                            @endif
                        </div>
                        <div class="stat-label">Terlaris</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="stat-card h-100">
                    <div class="stat-icon" style="background: rgba(22, 163, 74, 0.1); color: #16a34a;">
                        <span class="iconify" data-icon="heroicons:shopping-bag"></span>
                    </div>
                    <div>
                        <div class="stat-value" style="font-size:1.1rem;">{{ $pesanan->total() }}</div>
                        <div class="stat-label">Total Pesanan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Kolom Kanan: Stok Kemasan Ready -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header-clean py-2 px-3">
                <h6 class="mb-0 text-success"><span class="iconify" data-icon="heroicons:check-badge"></span> Stok Kemasan Ready</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush" style="max-height: 120px; overflow-y: auto;">
                    @forelse($stok_kemasan_list as $nama => $jumlah)
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3 border-bottom" style="font-size: 0.85rem;">
                        {{ $nama }}
                        <span class="badge bg-success rounded-pill">{{ $jumlah }} pack</span>
                    </li>
                    @empty
                    <li class="list-group-item text-center py-3 text-muted" style="font-size: 0.85rem;">
                        Semua kemasan habis / kosong.
                    </li>
                    @endforelse
                </ul>
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
                        <form action="{{ route('packager.pesanan.update', $item) }}" method="POST" class="d-inline m-0 p-0">
                            @csrf @method('PUT')
                            <select name="status" onchange="this.form.submit()" class="form-select-custom p-1 px-2" style="font-size:0.8rem; width:auto; border-color: 
                                {{ $item->status == 'selesai' ? '#b2dcc4' : ($item->status == 'dibatalkan' ? '#f5b8b8' : '#fde047') }}; 
                                background-color: {{ $item->status == 'selesai' ? '#e8f5ee' : ($item->status == 'dibatalkan' ? '#fde8e8' : '#fef6e0') }};">
                                <option value="menunggu" {{ $item->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="diproses" {{ $item->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="dikirim" {{ $item->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="selesai" {{ $item->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ $item->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('packager.pesanan.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-outline-custom btn-sm text-danger" style="border-color:#f5b8b8; padding: 4px 8px;">
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
