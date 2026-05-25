@extends('layouts.packager')

@section('title', 'Hasil Pengemasan')
@section('page-title', 'Hasil Pengemasan')
@section('breadcrumb', 'Dashboard / Pengemasan')

@section('topbar-actions')
<a href="{{ route('packager.pengemasan.create') }}" class="btn-primary-custom">
    <span class="iconify" data-icon="heroicons:plus-circle"></span> Catat Pengemasan
</a>
@endsection

@section('content')

{{-- STAT CARDS --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(22, 163, 74, 0.1); color: #16a34a;">
                <span class="iconify" data-icon="heroicons:cube"></span>
            </div>
            <div class="stat-value">{{ number_format($pengemasan->total(), 0, ',', '.') }}</div>
            <div class="stat-label">Total Batch Kemasan</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                <span class="iconify" data-icon="heroicons:archive-box"></span>
            </div>
            <div class="stat-value">{{ number_format($pengemasan->sum('jumlah_kemasan'), 0, ',', '.') }}</div>
            <div class="stat-label">Total Kemasan (Pack)</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(234, 179, 8, 0.12); color: #ca8a04;">
                <span class="iconify" data-icon="heroicons:check-badge"></span>
            </div>
            @php
                $layakJual = \App\Models\HasilPengemasan::where('user_id', Auth::id())
                    ->where('kualitas', 'layak_jual')->sum('jumlah_kemasan');
            @endphp
            <div class="stat-value">{{ number_format($layakJual, 0, ',', '.') }}</div>
            <div class="stat-label">Pack Layak Jual</div>
        </div>
    </div>
</div>

{{-- CHARTS ROW --}}
<div class="row mb-4">
    {{-- Bar Chart: Produksi Per Hari --}}
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header-clean">
                <h5>📊 Jumlah Produksi Kemasan per Hari (14 Hari Terakhir)</h5>
            </div>
            <div class="card-body p-4">
                @if(collect($harian)->sum('total') > 0)
                    <canvas id="chartHarian" height="120"></canvas>
                @else
                    <div class="text-center py-5 text-muted">
                        <span class="iconify" data-icon="heroicons:chart-bar" style="width:48px;height:48px;opacity:.3;display:block;margin:0 auto 12px;"></span>
                        <p>Belum ada data pengemasan dalam 14 hari terakhir.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Pie Chart: Distribusi Jenis Kemasan --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header-clean">
                <h5>🥧 Distribusi Jenis Kemasan</h5>
            </div>
            <div class="card-body p-4">
                @if($chartKemasan->count() > 0)
                    <canvas id="chartKemasan" height="200"></canvas>
                    <div class="mt-3">
                        @foreach($chartKemasan as $k)
                        <div class="d-flex justify-content-between align-items-center mb-1" style="font-size:.82rem;">
                            <span class="fw-medium">{{ $k->jenis_kemasan }}</span>
                            <span class="badge-custom badge-info-custom">{{ number_format($k->total) }} pack</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <span class="iconify" data-icon="heroicons:chart-pie" style="width:48px;height:48px;opacity:.3;display:block;margin:0 auto 12px;"></span>
                        <p>Belum ada data.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- TABEL --}}
<div class="card">
    <div class="card-header-clean">
        <h5>Riwayat Pengemasan Beras</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Batch ID</th>
                    <th>Jenis Beras</th>
                    <th>Ukuran Kemasan</th>
                    <th>Jumlah Pack</th>
                    <th>Kualitas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengemasan as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="fw-medium">#PKG-{{ $item->id }}</td>
                    <td>
                        @php
                            $berasEmoji = match($item->jenis_beras) {
                                'premium'  => '⭐',
                                'medium'   => '🌾',
                                'biasa'    => '🫘',
                                default    => '🍚'
                            };
                        @endphp
                        <span class="badge-custom badge-info-custom">{{ $berasEmoji }} {{ ucfirst($item->jenis_beras) }}</span>
                    </td>
                    <td><span class="fw-medium">{{ $item->jenis_kemasan }}</span></td>
                    <td>{{ number_format($item->jumlah_kemasan, 0, ',', '.') }} Pack</td>
                    <td>
                        <span class="badge-custom {{ $item->kualitas == 'layak_jual' ? 'badge-success-custom' : 'badge-danger-custom' }}">
                            {{ str_replace('_', ' ', ucfirst($item->kualitas)) }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('packager.pengemasan.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
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
                        <span class="iconify" data-icon="heroicons:cube" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></span>
                        <p>Belum ada data pengemasan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pengemasan->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $pengemasan->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
@if(collect($harian)->sum('total') > 0)
<script>
    // Bar Chart — Produksi Per Hari
    const harianLabels = @json(collect($harian)->pluck('label'));
    const harianData   = @json(collect($harian)->pluck('total'));

    new Chart(document.getElementById('chartHarian'), {
        type: 'bar',
        data: {
            labels: harianLabels,
            datasets: [{
                label: 'Jumlah Kemasan (Pack)',
                data: harianData,
                backgroundColor: 'rgba(22, 163, 74, 0.55)',
                borderColor: '#16a34a',
                borderWidth: 1.5,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.parsed.y.toLocaleString('id-ID') + ' Pack'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: v => v.toLocaleString('id-ID') },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endif

@if($chartKemasan->count() > 0)
<script>
    // Pie Chart — Distribusi Jenis Kemasan
    const kemasanLabels = @json($chartKemasan->pluck('jenis_kemasan'));
    const kemasanData   = @json($chartKemasan->pluck('total'));
    const pieColors     = ['#16a34a','#3b82f6','#f59e0b','#a855f7','#ef4444','#0ea5e9'];

    new Chart(document.getElementById('chartKemasan'), {
        type: 'doughnut',
        data: {
            labels: kemasanLabels,
            datasets: [{
                data: kemasanData,
                backgroundColor: pieColors.slice(0, kemasanLabels.length),
                borderColor: '#fff',
                borderWidth: 3,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            cutout: '60%',
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 12 } },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${ctx.parsed.toLocaleString('id-ID')} pack`
                    }
                }
            }
        }
    });
</script>
@endif
@endpush
