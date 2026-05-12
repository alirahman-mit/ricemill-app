@extends('layouts.ricemill')

@section('title', 'Dashboard Rice Mill')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Selamat datang, ' . Auth::user()->name)

@section('topbar-actions')
    <a href="{{ route('ricemill.penerimaan-gabah.create') }}" class="btn-primary-custom">
        <i data-lucide="plus" style="width:16px;height:16px;"></i>
        Terima Gabah
    </a>
@endsection

@section('content')

<!-- STAT CARDS -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e0eeff;">
                <i data-lucide="inbox" style="color:#1a3a5c;width:22px;height:22px;"></i>
            </div>
            <div class="stat-value">{{ $stats['total_penerimaan'] }}</div>
            <div class="stat-label">Total Penerimaan Gabah</div>
            <div class="stat-trend neutral">
                <i data-lucide="calendar" style="width:13px;height:13px;"></i>
                Bulan ini
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff8e0;">
                <i data-lucide="settings-2" style="color:#a0720f;width:22px;height:22px;"></i>
            </div>
            <div class="stat-value">{{ $stats['total_operasional'] }}</div>
            <div class="stat-label">Sesi Penggilingan</div>
            <div class="stat-trend neutral">
                <i data-lucide="calendar" style="width:13px;height:13px;"></i>
                Bulan ini
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#e8f5ee;">
                <i data-lucide="trending-up" style="color:#1a5c38;width:22px;height:22px;"></i>
            </div>
            <div class="stat-value">{{ number_format($stats['total_produksi_kg'], 0, ',', '.') }}</div>
            <div class="stat-label">Total Produksi Beras (kg)</div>
            <div class="stat-trend up">
                <i data-lucide="arrow-up" style="width:13px;height:13px;"></i>
                Rendemen rata-rata {{ $stats['rendemen_rata'] }}%
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fde8ff;">
                <i data-lucide="truck" style="color:#7b1fa2;width:22px;height:22px;"></i>
            </div>
            <div class="stat-value">{{ $stats['total_pengiriman'] }}</div>
            <div class="stat-label">Pengiriman ke Packager</div>
            <div class="stat-trend neutral">
                <i data-lucide="clock" style="width:13px;height:13px;"></i>
                {{ $stats['pengiriman_menunggu'] }} menunggu konfirmasi
            </div>
        </div>
    </div>
</div>

<!-- SECOND ROW: Penerimaan Terbaru + Status Proses -->
<div class="row g-3 mb-3">
    <!-- Penerimaan Gabah Terbaru -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header-clean">
                <h5>Penerimaan Gabah Terbaru</h5>
                <a href="{{ route('ricemill.penerimaan-gabah.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
                    Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-clean mb-0">
                    <thead>
                        <tr>
                            <th>Petani / Lahan</th>
                            <th>Kualitas</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPenerimaan as $item)
                        <tr>
                            <td>
                                <span style="font-weight:500;">{{ $item->nama_petani ?? '-' }}</span><br>
                                <span style="font-size:.75rem;color:var(--text-muted);">{{ $item->asal_lahan ?? '' }}</span>
                            </td>
                            <td>
                                @php
                                    $kualBadge = match($item->kualitas_gabah ?? '') {
                                        'kering' => 'badge-success-custom',
                                        'basah'  => 'badge-warning-custom',
                                        default  => 'badge-info-custom',
                                    };
                                @endphp
                                <span class="badge-custom {{ $kualBadge }}">{{ ucfirst($item->kualitas_gabah ?? '-') }}</span>
                            </td>
                            <td><strong>{{ number_format($item->jumlah_gabah ?? 0, 0, ',', '.') }}</strong> <span style="color:var(--text-muted);font-size:.8rem;">kg</span></td>
                            <td>
                                @php
                                    $statusBadge = match($item->status ?? '') {
                                        'diterima'  => 'badge-success-custom',
                                        'diproses'  => 'badge-info-custom',
                                        'selesai'   => 'badge-purple-custom',
                                        default     => 'badge-warning-custom',
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
                            <td colspan="5" style="text-align:center;color:var(--text-muted);padding:28px;">
                                Belum ada data penerimaan gabah.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Ringkasan Proses Penggilingan -->
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header-clean">
                <h5>Ringkasan Operasional</h5>
                <a href="{{ route('ricemill.operasional.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
                    Detail
                </a>
            </div>
            <div style="padding:20px 24px;">
                <!-- Status Proses -->
                <div class="mb-3">
                    <div style="font-size:.78rem;color:var(--text-muted);font-weight:500;text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px;">
                        Status Proses Penggilingan
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="status-step">
                            <div class="status-dot dot-menunggu"></div>
                            <span style="flex:1;font-size:.85rem;">Menunggu Proses</span>
                            <strong>{{ $stats['proses_menunggu'] ?? 0 }}</strong>
                        </div>
                        <div class="status-step">
                            <div class="status-dot dot-diproses"></div>
                            <span style="flex:1;font-size:.85rem;">Sedang Diproses</span>
                            <strong>{{ $stats['proses_berjalan'] ?? 0 }}</strong>
                        </div>
                        <div class="status-step">
                            <div class="status-dot dot-selesai"></div>
                            <span style="flex:1;font-size:.85rem;">Selesai</span>
                            <strong>{{ $stats['proses_selesai'] ?? 0 }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Produksi Summary -->
                <div style="background:#f5f8fc;border-radius:12px;padding:16px;border:1px solid var(--border);">
                    <div style="font-size:.78rem;color:var(--text-muted);font-weight:500;text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px;">
                        Keuangan Bulan Ini
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span style="font-size:.85rem;">Total Pemasukan</span>
                        <span style="font-weight:600;color:#1a5c38;font-size:.88rem;">Rp {{ number_format($stats['pemasukan_bulan'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span style="font-size:.85rem;">Total Pengeluaran</span>
                        <span style="font-weight:600;color:#8b1a1a;font-size:.88rem;">Rp {{ number_format($stats['pengeluaran_bulan'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div style="border-top:1px solid var(--border);margin:10px 0 10px;"></div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span style="font-size:.88rem;font-weight:600;">Laba Bersih</span>
                        <span style="font-weight:700;color:#1a3a5c;font-size:.95rem;">
                            Rp {{ number_format(($stats['pemasukan_bulan'] ?? 0) - ($stats['pengeluaran_bulan'] ?? 0), 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- THIRD ROW: Riwayat Produksi + Pengiriman Terbaru -->
<div class="row g-3">
    <!-- Riwayat Produksi Terbaru -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header-clean">
                <h5>Riwayat Produksi Terbaru</h5>
                <a href="{{ route('ricemill.produksi.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
                    Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-clean mb-0">
                    <thead>
                        <tr>
                            <th>Batch</th>
                            <th>Gabah Masuk</th>
                            <th>Beras Hasil</th>
                            <th>Rendemen</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentProduksi as $prod)
                        <tr>
                            <td><span style="font-weight:500;">{{ $prod->batch_id ?? '#' . $prod->id }}</span></td>
                            <td>{{ number_format($prod->jumlah_gabah ?? 0, 0, ',', '.') }} kg</td>
                            <td><strong>{{ number_format($prod->jumlah_beras ?? 0, 0, ',', '.') }}</strong> kg</td>
                            <td>
                                @php $rendemen = $prod->jumlah_gabah > 0 ? round(($prod->jumlah_beras / $prod->jumlah_gabah) * 100, 1) : 0; @endphp
                                <span style="color:{{ $rendemen >= 60 ? '#1a5c38' : '#a0720f' }};font-weight:500;">{{ $rendemen }}%</span>
                                @if($rendemen < 60)
                                    <i data-lucide="alert-triangle" style="width:13px;height:13px;color:#a0720f;"></i>
                                @endif
                            </td>
                            <td style="color:var(--text-muted);font-size:.82rem;">
                                {{ isset($prod->tanggal_proses) ? \Carbon\Carbon::parse($prod->tanggal_proses)->format('d M Y') : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center;color:var(--text-muted);padding:28px;">
                                Belum ada data produksi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pengiriman Terbaru ke Packager -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header-clean">
                <h5>Pengiriman ke Packager</h5>
                <a href="{{ route('ricemill.pengiriman.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
                    Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-clean mb-0">
                    <thead>
                        <tr>
                            <th>Packager</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPengiriman as $kirim)
                        <tr>
                            <td>
                                <span style="font-weight:500;">{{ $kirim->nama_packager ?? '-' }}</span><br>
                                <span style="font-size:.75rem;color:var(--text-muted);">{{ $kirim->jenis_beras ?? '-' }}</span>
                            </td>
                            <td>
                                <strong>{{ number_format($kirim->jumlah_karung ?? 0) }}</strong>
                                <span style="color:var(--text-muted);font-size:.8rem;"> karung</span>
                            </td>
                            <td>
                                @php
                                    $kirimBadge = match($kirim->status ?? '') {
                                        'diterima'  => 'badge-success-custom',
                                        'ditolak'   => 'badge-danger-custom',
                                        'diproses'  => 'badge-info-custom',
                                        default     => 'badge-warning-custom',
                                    };
                                @endphp
                                <span class="badge-custom {{ $kirimBadge }}">{{ ucfirst($kirim->status ?? 'menunggu') }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align:center;color:var(--text-muted);padding:28px;">
                                Belum ada pengiriman.
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
