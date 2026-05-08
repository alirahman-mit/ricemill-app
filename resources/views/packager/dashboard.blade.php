@extends('layouts.packager')

@section('title', 'Dashboard Packager')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Selamat datang, ' . Auth::user()->name)

@section('topbar-actions')
    <a href="{{ route('packager.penerimaan-beras.create') }}" class="btn-primary-custom">
        <i data-lucide="plus" style="width:16px;height:16px;"></i>
        Terima Beras
    </a>
@endsection

@section('content')

<!-- STAT CARDS -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0e8ff;">
                <i data-lucide="inbox" style="color:#4f2d87;width:22px;height:22px;"></i>
            </div>
            <div class="stat-value">{{ $stats['total_penerimaan'] }}</div>
            <div class="stat-label">Penerimaan Beras</div>
            <div class="stat-trend neutral">
                <i data-lucide="calendar" style="width:13px;height:13px;"></i>
                Bulan ini
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f5ee;">
                <i data-lucide="package-2" style="color:#1a5c38;width:22px;height:22px;"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['total_kemasan'], 0, ',', '.') }}</div>
            <div class="stat-label">Total Kemasan Diproduksi</div>
            <div class="stat-trend up">
                <i data-lucide="trending-up" style="width:13px;height:13px;"></i>
                Efisiensi {{ $stats['efisiensi'] }}%
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff8e0;">
                <i data-lucide="shopping-cart" style="color:#a0720f;width:22px;height:22px;"></i>
            </div>
            <div class="stat-value">{{ $stats['total_pesanan'] }}</div>
            <div class="stat-label">Pesanan Masuk</div>
            <div class="stat-trend neutral">
                <i data-lucide="clock" style="width:13px;height:13px;"></i>
                {{ $stats['pesanan_pending'] }} belum diproses
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fde8ff;">
                <i data-lucide="bar-chart-2" style="color:#7b1fa2;width:22px;height:22px;"></i>
            </div>
            <div class="stat-value" style="font-size:1.25rem;">
                Rp {{ number_format($stats['omzet_bulan'] ?? 0, 0, ',', '.') }}
            </div>
            <div class="stat-label">Omzet Bulan Ini</div>
            <div class="stat-trend up">
                <i data-lucide="arrow-up" style="width:13px;height:13px;"></i>
                Dari {{ $stats['total_pesanan'] }} pesanan
            </div>
        </div>
    </div>
</div>

<!-- SECOND ROW: Penerimaan Beras + Ringkasan Stok -->
<div class="row g-3 mb-3">
    <!-- Penerimaan Beras Terbaru -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header-clean">
                <h5>Penerimaan Beras Terbaru</h5>
                <a href="{{ route('packager.penerimaan-beras.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
                    Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-clean mb-0">
                    <thead>
                        <tr>
                            <th>Asal Penggilingan</th>
                            <th>Jumlah Beras</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPenerimaan as $item)
                        <tr>
                            <td>
                                <span style="font-weight:500;">{{ $item->asal_penggilingan ?? '-' }}</span><br>
                                <span style="font-size:.75rem;color:var(--text-muted);">{{ $item->jenis_beras ?? '' }}</span>
                            </td>
                            <td>
                                <strong>{{ number_format($item->jumlah_beras ?? 0, 0, ',', '.') }}</strong>
                                <span style="color:var(--text-muted);font-size:.8rem;"> kg</span>
                            </td>
                            <td>
                                @php
                                    $statusBadge = match($item->status ?? '') {
                                        'diterima'  => 'badge-success-custom',
                                        'ditolak'   => 'badge-danger-custom',
                                        'sebagian'  => 'badge-warning-custom',
                                        default     => 'badge-info-custom',
                                    };
                                @endphp
                                <span class="badge-custom {{ $statusBadge }}">{{ ucfirst($item->status ?? 'menunggu') }}</span>
                            </td>
                            <td style="color:var(--text-muted);font-size:.82rem;">
                                {{ isset($item->tanggal) ? \Carbon\Carbon::parse($item->tanggal)->format('d M Y') : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:var(--text-muted);padding:28px;">
                                Belum ada data penerimaan beras.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Ringkasan Produksi Kemasan -->
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header-clean">
                <h5>Ringkasan Kemasan</h5>
                <a href="{{ route('packager.pengemasan.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
                    Detail
                </a>
            </div>
            <div style="padding:20px 24px;">

                <!-- Distribusi Jenis Kemasan -->
                <div class="mb-3">
                    <div style="font-size:.78rem;color:var(--text-muted);font-weight:500;text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px;">
                        Distribusi Jenis Kemasan
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px;background:#f8f6fd;border-radius:8px;border:1px solid var(--border);">
                            <span style="font-size:.85rem;">Kemasan 5 kg</span>
                            <strong style="color:#4f2d87;">{{ $stats['kemasan_5kg'] ?? 0 }}</strong>
                        </div>
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px;background:#f8f6fd;border-radius:8px;border:1px solid var(--border);">
                            <span style="font-size:.85rem;">Kemasan 10 kg</span>
                            <strong style="color:#4f2d87;">{{ $stats['kemasan_10kg'] ?? 0 }}</strong>
                        </div>
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px;background:#f8f6fd;border-radius:8px;border:1px solid var(--border);">
                            <span style="font-size:.85rem;">Kemasan 25 kg</span>
                            <strong style="color:#4f2d87;">{{ $stats['kemasan_25kg'] ?? 0 }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Kualitas -->
                <div style="background:#f8f6fd;border-radius:12px;padding:16px;border:1px solid var(--border);">
                    <div style="font-size:.78rem;color:var(--text-muted);font-weight:500;text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px;">
                        Kualitas Kemasan
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span style="font-size:.85rem;">Layak Jual</span>
                        <span class="badge-custom badge-success-custom">{{ $stats['layak_jual'] ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span style="font-size:.85rem;">Reject</span>
                        <span class="badge-custom badge-danger-custom">{{ $stats['reject'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- THIRD ROW: Pengemasan Terbaru + Pesanan Masuk -->
<div class="row g-3">
    <!-- Hasil Pengemasan Terbaru -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header-clean">
                <h5>Hasil Pengemasan Terbaru</h5>
                <a href="{{ route('packager.pengemasan.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
                    Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-clean mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jenis Beras</th>
                            <th>Jenis Kemasan</th>
                            <th>Jumlah</th>
                            <th>Kualitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPengemasan as $kemas)
                        <tr>
                            <td style="color:var(--text-muted);font-size:.82rem;">
                                {{ isset($kemas->tanggal) ? \Carbon\Carbon::parse($kemas->tanggal)->format('d M Y') : '-' }}
                            </td>
                            <td>{{ $kemas->jenis_beras ?? '-' }}</td>
                            <td>
                                <span class="badge-custom badge-purple-custom">{{ $kemas->jenis_kemasan ?? '-' }}</span>
                            </td>
                            <td><strong>{{ number_format($kemas->jumlah_kemasan ?? 0) }}</strong> pcs</td>
                            <td>
                                @php $layak = $kemas->kualitas === 'layak jual'; @endphp
                                <span class="badge-custom {{ $layak ? 'badge-success-custom' : 'badge-danger-custom' }}">
                                    {{ $kemas->kualitas ?? '-' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center;color:var(--text-muted);padding:28px;">
                                Belum ada data pengemasan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pesanan Masuk Terbaru -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header-clean">
                <h5>Pesanan Terbaru</h5>
                <a href="{{ route('packager.pesanan.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
                    Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-clean mb-0">
                    <thead>
                        <tr>
                            <th>Pelanggan</th>
                            <th>Produk</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPesanan as $pesan)
                        <tr>
                            <td>
                                <span style="font-weight:500;">{{ $pesan->nama_pelanggan ?? '-' }}</span><br>
                                <span style="font-size:.75rem;color:var(--text-muted);">Jml: {{ $pesan->jumlah ?? '-' }}</span>
                            </td>
                            <td>
                                <span style="font-size:.85rem;">{{ $pesan->jenis_produk ?? '-' }}</span>
                            </td>
                            <td>
                                @php
                                    $pesanBadge = match($pesan->status ?? '') {
                                        'selesai'       => 'badge-success-custom',
                                        'diproses'      => 'badge-info-custom',
                                        'dibatalkan'    => 'badge-danger-custom',
                                        default         => 'badge-warning-custom',
                                    };
                                @endphp
                                <span class="badge-custom {{ $pesanBadge }}">{{ ucfirst($pesan->status ?? 'pending') }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align:center;color:var(--text-muted);padding:28px;">
                                Belum ada pesanan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
