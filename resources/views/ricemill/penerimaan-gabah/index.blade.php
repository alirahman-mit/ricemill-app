@extends('layouts.ricemill')

@section('title', 'Penerimaan Gabah')
@section('page-title', 'Penerimaan Gabah')
@section('breadcrumb', 'Dashboard / Penerimaan Gabah')

@section('topbar-actions')
<a href="{{ route('ricemill.penerimaan-gabah.create') }}" class="btn-primary-custom">
    <i data-lucide="plus-circle"></i> Tambah Penerimaan
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header-clean">
        <h5>Daftar Penerimaan Gabah</h5>
        <div class="d-flex gap-2">
            <button class="btn-outline-custom btn-sm"><i data-lucide="filter"></i> Filter</button>
            <button class="btn-outline-custom btn-sm"><i data-lucide="download"></i> Export</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode Transaksi</th>
                    <th>Nama Petani</th>
                    <th>Berat Gabah (Kg)</th>
                    <th>Jenis Gabah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penerimaan as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="fw-medium">#TRX-{{ $item->id }}</td>
                    <td>{{ $item->nama_petani ?? 'Umum' }}</td>
                    <td>{{ number_format($item->berat_gabah, 0, ',', '.') }} Kg</td>
                    <td>{{ $item->jenis_gabah }}</td>
                    <td>
                        <span class="badge-custom {{ $item->status == 'selesai' ? 'badge-success-custom' : 'badge-warning-custom' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn-outline-custom btn-sm" title="Detail">
                                <i data-lucide="eye" style="width:14px;height:14px;"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i data-lucide="inbox" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></i>
                        <p>Belum ada data penerimaan gabah.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($penerimaan->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $penerimaan->links() }}
    </div>
    @endif
</div>
@endsection
