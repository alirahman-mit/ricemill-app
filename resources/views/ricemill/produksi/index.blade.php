@extends('layouts.ricemill')

@section('title', 'Riwayat Produksi')
@section('page-title', 'Riwayat Produksi')
@section('breadcrumb', 'Dashboard / Produksi')

@section('topbar-actions')
<a href="{{ route('ricemill.produksi.create') }}" class="btn-primary-custom">
    <span class="iconify" data-icon="heroicons:plus-circle"></span> Catat Hasil Produksi
</a>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(22, 163, 74, 0.1); color: #16a34a;">
                <span class="iconify" data-icon="heroicons:archive-box"></span>
            </div>
            <div class="stat-value">{{ number_format($produksi->sum('jumlah_beras'), 0, ',', '.') }} Kg</div>
            <div class="stat-label">Total Produksi Beras {{ request('bulan') || request('tahun') ? '(Filter)' : '' }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <span class="iconify" data-icon="heroicons:receipt-percent"></span>
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
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(168, 85, 247, 0.1); color: #a855f7;">
                <span class="iconify" data-icon="heroicons:clipboard-document-list"></span>
            </div>
            <div class="stat-value">{{ $produksi->total() }}</div>
            <div class="stat-label">Total Batch</div>
        </div>
    </div>
</div>

{{-- FILTER PERIODE --}}
<div class="card mb-4">
    <div style="padding:16px 24px;">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label-custom">Bulan</label>
                <select name="bulan" class="form-select-custom">
                    <option value="">Semua Bulan</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                        <option value="{{ $i+1 }}" {{ request('bulan') == $i+1 ? 'selected' : '' }}>{{ $bln }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Tahun</label>
                <select name="tahun" class="form-select-custom">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $thn)
                        <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn-primary-custom w-100" style="justify-content:center;">
                    <span class="iconify" data-icon="heroicons:magnifying-glass" style="width:16px;height:16px;"></span> Filter
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('ricemill.produksi.index') }}" class="btn-outline-custom w-100" style="justify-content:center;">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- CHART PERBANDINGAN 6 BULAN --}}
@if($perbandingan->count() > 0)
<div class="card mb-4">
    <div class="card-header-clean">
        <h5>📊 Perbandingan Produksi 6 Bulan Terakhir</h5>
    </div>
    <div class="card-body p-4">
        <canvas id="chartProduksi" height="80"></canvas>
    </div>
</div>
@endif

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
                    <th>Jenis Beras</th>
                    <th>Input Gabah</th>
                    <th>Output Beras</th>
                    <th>Rendemen</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produksi as $item)
                <tr class="{{ $item->notifikasi_rendemen_rendah ? 'table-danger-subtle' : '' }}">
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_proses)->format('d M Y') }}</td>
                    <td class="fw-medium">{{ $item->batch_id }}</td>
                    <td>
                        @if($item->jenis_beras)
                            @php
                                $berasEmoji = match($item->jenis_beras) {
                                    'premium'      => '⭐',
                                    'medium'       => '🌾',
                                    'setra_ramos'  => '🌿',
                                    'pandan_wangi' => '🍃',
                                    'biasa'        => '🫘',
                                    default        => '🍚'
                                };
                            @endphp
                            <span class="badge-custom badge-info-custom">{{ $berasEmoji }} {{ $item->jenis_beras_label }}</span>
                        @else
                            <span class="text-muted" style="font-size:.8rem;">—</span>
                        @endif
                    </td>
                    <td>{{ number_format($item->jumlah_gabah, 0, ',', '.') }} Kg</td>
                    <td class="fw-bold text-success">{{ number_format($item->jumlah_beras, 0, ',', '.') }} Kg</td>
                    <td>
                        <span class="{{ $item->notifikasi_rendemen_rendah ? 'text-danger fw-bold' : 'text-success' }}">
                            {{ $item->rendemen }}%
                        </span>
                        @if($item->notifikasi_rendemen_rendah)
                            <span class="iconify text-danger" data-icon="heroicons:exclamation-triangle"
                                  title="Rendemen di bawah standar!"></span>
                        @endif
                    </td>
                    <td>
                        @if($item->notifikasi_rendemen_rendah)
                            <span class="badge-custom badge-danger-custom">
                                <span class="iconify" data-icon="heroicons:exclamation-circle" style="width:12px;"></span>
                                Di Bawah Standar
                            </span>
                        @else
                            <span class="badge-custom badge-success-custom">Normal</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('ricemill.produksi.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-outline-custom btn-sm text-danger" style="border-color:#f5b8b8;">
                                <span class="iconify" data-icon="heroicons:trash" style="width:14px;height:14px;"></span>
                            </button>
                        </form>
                    </td>
                </tr>
                @if($item->notifikasi_rendemen_rendah)
                <tr>
                    <td colspan="8" style="padding:6px 16px 10px;background:#fef2f2;border-bottom:2px solid #fca5a5;">
                        <span class="iconify" data-icon="heroicons:exclamation-triangle" style="color:#dc2626;width:15px;"></span>
                        <strong style="color:#dc2626;font-size:.82rem;">Peringatan Rendemen Rendah</strong>
                        <span style="color:#7f1d1d;font-size:.82rem;">
                            — Rendemen {{ $item->rendemen }}% di bawah standar 60%. Periksa kondisi mesin dan kualitas gabah batch <strong>{{ $item->batch_id }}</strong>.
                        </span>
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <span class="iconify" data-icon="heroicons:arrow-trending-up" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></span>
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

@push('scripts')
@if($perbandingan->count() > 0)
<script>
    const namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];

    const labels = @json($perbandingan->map(fn($r) => namaBulan[$r->bulan - 1] . ' ' . $r->tahun));
    const dataBeras  = @json($perbandingan->pluck('total_beras'));
    const dataGabah  = @json($perbandingan->pluck('total_gabah'));

    const ctx = document.getElementById('chartProduksi').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Input Gabah (Kg)',
                    data: dataGabah,
                    backgroundColor: 'rgba(59, 130, 246, 0.55)',
                    borderColor: '#3b82f6',
                    borderWidth: 1.5,
                    borderRadius: 6,
                },
                {
                    label: 'Output Beras (Kg)',
                    data: dataBeras,
                    backgroundColor: 'rgba(22, 163, 74, 0.55)',
                    borderColor: '#16a34a',
                    borderWidth: 1.5,
                    borderRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.label + ': ' + ctx.parsed.y.toLocaleString('id-ID') + ' Kg'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => v.toLocaleString('id-ID') + ' Kg'
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endif
@endpush
