@extends('layouts.ricemill')

@section('title', 'Operasional Penggilingan')
@section('page-title', 'Operasional Penggilingan')
@section('breadcrumb', 'Dashboard / Operasional')

@section('topbar-actions')
<button class="btn-primary-custom">
    <i data-lucide="play-circle"></i> Mulai Proses Baru
</button>
@endsection

@section('content')
<div class="card">
    <div class="card-header-clean">
        <h5>Antrean & Riwayat Penggilingan</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Tanggal Proses</th>
                    <th>Batch ID</th>
                    <th>Mesin</th>
                    <th>Kapasitas (Kg)</th>
                    <th>Operator</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($operasional as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_proses)->format('d M Y') }}</td>
                    <td class="fw-medium">#BTH-{{ $item->id }}</td>
                    <td>{{ $item->mesin ?? 'Mesin Utama' }}</td>
                    <td>{{ number_format($item->jumlah_gabah, 0, ',', '.') }} Kg</td>
                    <td>{{ $item->operator ?? 'Staff' }}</td>
                    <td>
                        @if($item->status == 'selesai')
                            <span class="badge-custom badge-success-custom">Selesai</span>
                        @elseif($item->status == 'diproses')
                            <span class="badge-custom badge-info-custom">Diproses</span>
                        @else
                            <span class="badge-custom badge-warning-custom">Menunggu</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <button class="btn-outline-custom btn-sm"><i data-lucide="settings" style="width:14px;height:14px;"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i data-lucide="settings-2" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></i>
                        <p>Belum ada data operasional.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($operasional->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $operasional->links() }}
    </div>
    @endif
</div>
@endsection
