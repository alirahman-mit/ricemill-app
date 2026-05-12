@extends('layouts.packager')

@section('title', 'Penerimaan Beras Putih')
@section('page-title', 'Penerimaan Beras Putih')
@section('breadcrumb', 'Dashboard / Penerimaan')

@section('topbar-actions')
<a href="{{ route('packager.penerimaan-beras.create') }}" class="btn-primary-custom">
    <i data-lucide="plus-circle"></i> Terima Beras
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header-clean">
        <h5>Daftar Penerimaan Beras dari Rice Mill</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Tanggal Terima</th>
                    <th>No. Surat Jalan</th>
                    <th>Pengirim</th>
                    <th>Jumlah (Kg)</th>
                    <th>Kualitas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penerimaan as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_terima)->format('d M Y') }}</td>
                    <td class="fw-medium">{{ $item->no_surat_jalan }}</td>
                    <td>{{ $item->asal_ricemill ?? 'Rice Mill Utama' }}</td>
                    <td>{{ number_format($item->jumlah_beras, 0, ',', '.') }} Kg</td>
                    <td><span class="badge-custom badge-info-custom">{{ $item->kualitas ?? 'Premium' }}</span></td>
                    <td>
                        <span class="badge-custom badge-success-custom">Diverifikasi</span>
                    </td>
                    <td>
                        <button class="btn-outline-custom btn-sm"><i data-lucide="eye" style="width:14px;height:14px;"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i data-lucide="inbox" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></i>
                        <p>Belum ada data penerimaan beras.</p>
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
