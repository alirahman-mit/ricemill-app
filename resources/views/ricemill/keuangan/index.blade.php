@extends('layouts.ricemill')

@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Keuangan')
@section('breadcrumb', 'Dashboard / Keuangan')

@section('topbar-actions')
<div class="d-flex gap-2">
    <form action="{{ route('ricemill.keuangan.index') }}" method="GET" class="d-flex">
        <select name="bulan" class="form-select form-select-sm border-0 shadow-sm rounded-3" style="min-width: 150px; background: #fff; cursor: pointer;" onchange="this.form.submit()">
            <option value="all">Semua Bulan</option>
            @foreach($bulanOptions as $bulan)
                <option value="{{ $bulan }}" {{ $selectedBulan == $bulan ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}
                </option>
            @endforeach
        </select>
    </form>
    <a href="{{ route('ricemill.keuangan.create') }}" class="btn-primary-custom" style="display: flex; align-items: center; gap: 4px;">
        <span class="iconify" data-icon="heroicons:plus-circle"></span> Catat Transaksi
    </a>
</div>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(22, 163, 74, 0.1); color: #16a34a;">
                <span class="iconify" data-icon="heroicons:arrow-down-circle"></span>
            </div>
            <div class="stat-value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pemasukan</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(220, 38, 38, 0.1); color: #dc2626;">
                <span class="iconify" data-icon="heroicons:arrow-up-circle"></span>
            </div>
            <div class="stat-value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pengeluaran</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(13, 148, 136, 0.1); color: #0d9488;">
                <span class="iconify" data-icon="heroicons:wallet"></span>
            </div>
            <div class="stat-value">Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}</div>
            <div class="stat-label">Saldo Kas</div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header-clean d-flex align-items-center gap-2">
                <span class="iconify text-success" data-icon="heroicons:arrow-down-circle"></span>
                <h5 class="mb-0">Pemasukan Per Bulan</h5>
            </div>
            <div class="card-body p-3">
                <div class="d-flex flex-column gap-2">
                    @forelse($pemasukanPerBulan as $bulan => $total)
                        <div class="d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted small">{{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}</span>
                            <span class="fw-bold text-success">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <div class="text-muted w-100 text-center py-3 small">Belum ada data pemasukan bulanan.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header-clean d-flex align-items-center gap-2">
                <span class="iconify text-danger" data-icon="heroicons:arrow-up-circle"></span>
                <h5 class="mb-0">Pengeluaran Per Bulan</h5>
            </div>
            <div class="card-body p-3">
                <div class="d-flex flex-column gap-2">
                    @forelse($pengeluaranPerBulan as $bulan => $total)
                        <div class="d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted small">{{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}</span>
                            <span class="fw-bold text-danger">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <div class="text-muted w-100 text-center py-3 small">Belum ada data pengeluaran bulanan.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header-clean d-flex align-items-center gap-2">
                <span class="iconify text-primary" data-icon="heroicons:scale"></span>
                <h5 class="mb-0">Overall Per Bulan</h5>
            </div>
            <div class="card-body p-3">
                <div class="d-flex flex-column gap-2">
                    @forelse($overallPerBulan as $bulan => $total)
                        <div class="d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted small">{{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y') }}</span>
                            <span class="fw-bold {{ $total >= 0 ? 'text-success' : 'text-danger' }}">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <div class="text-muted w-100 text-center py-3 small">Belum ada data.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Kategori Pemasukan -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header-clean d-flex align-items-center gap-2">
                <span class="iconify text-success" data-icon="heroicons:chart-pie"></span>
                <h5 class="mb-0">Distribusi Pemasukan</h5>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        @forelse($kategoriPemasukan as $item)
                        <tr>
                            <td>{{ $item->kategori }}</td>
                            <td class="text-end fw-bold text-success">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center text-muted small py-3">Belum ada data</td></tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Kategori Pengeluaran -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header-clean d-flex align-items-center gap-2">
                <span class="iconify text-danger" data-icon="heroicons:chart-pie"></span>
                <h5 class="mb-0">Distribusi Pengeluaran</h5>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        @forelse($kategoriPengeluaran as $item)
                        <tr>
                            <td>{{ $item->kategori }}</td>
                            <td class="text-end fw-bold text-danger">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-center text-muted small py-3">Belum ada data</td></tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header-clean">
        <h5>Mutasi Kas Rice Mill</h5>
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
                        <span class="iconify" data-icon="heroicons:presentation-chart-bar" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></span>
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
