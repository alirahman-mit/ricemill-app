@extends('layouts.petani')

@section('title', 'Detail Lahan')
@section('page-title', $lahan->nama_lahan)
@section('breadcrumb', 'Lahan → Detail')

@section('topbar-actions')
    <a href="{{ route('petani.lahan.edit', $lahan) }}" class="btn-primary-custom">
        <i data-lucide="pencil" style="width:16px;height:16px;"></i> Edit
    </a>
@endsection

@section('content')
<div class="row g-3">
    <!-- Info Card -->
    <div class="col-lg-5">
        <div class="card">
            @if($lahan->foto)
                <img src="{{ Storage::url($lahan->foto) }}"
                     style="width:100%;height:220px;object-fit:cover;border-radius:14px 14px 0 0;">
            @else
                <div style="width:100%;height:160px;background:#e8f5ee;border-radius:14px 14px 0 0;
                            display:flex;align-items:center;justify-content:center;">
                    <i data-lucide="map" style="width:48px;height:48px;color:#1a5c38;"></i>
                </div>
            @endif

            <div style="padding:24px;">
                <h4 style="font-weight:600;margin-bottom:4px;">{{ $lahan->nama_lahan }}</h4>
                <p style="color:var(--text-muted);font-size:.88rem;margin-bottom:20px;">
                    <i data-lucide="map-pin" style="width:14px;height:14px;vertical-align:middle;"></i>
                    {{ $lahan->lokasi }}
                </p>

                <div class="row g-2">
                    <div class="col-6">
                        <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;">Luas</div>
                        <div style="font-weight:600;font-size:1.1rem;">{{ number_format($lahan->luas_lahan, 2) }} ha</div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;">Jenis Tanah</div>
                        <div>
                            <span class="badge-custom badge-success-custom">{{ $lahan->jenis_tanah_label }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;">Total Panen</div>
                        <div style="font-weight:600;font-size:1.1rem;">{{ $lahan->riwayatPanen->count() }}</div>
                    </div>
                    <div class="col-6">
                        <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;">Status</div>
                        <span class="badge-custom {{ $lahan->is_active ? 'badge-success-custom' : 'badge-warning-custom' }}">
                            {{ $lahan->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>

                @if($lahan->deskripsi)
                    <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
                        <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Deskripsi</div>
                        <p style="font-size:.88rem;color:var(--text-main);line-height:1.6;">{{ $lahan->deskripsi }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div style="margin-top:16px;">
            <a href="{{ route('petani.lahan.index') }}" class="btn-outline-custom">
                <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <!-- Riwayat Panen -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header-clean">
                <h5>Riwayat Panen di Lahan Ini</h5>
                <a href="{{ route('petani.panen.create') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
                    <i data-lucide="plus" style="width:14px;height:14px;"></i> Tambah Panen
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-clean mb-0">
                    <thead>
                        <tr>
                            <th>Tanaman</th>
                            <th>Hasil</th>
                            <th>Tanggal</th>
                            <th>Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lahan->riwayatPanen as $panen)
                        <tr>
                            <td style="font-weight:500;">{{ $panen->jenis_tanaman }}</td>
                            <td>
                                <strong>{{ number_format($panen->jumlah_hasil, 0, ',', '.') }}</strong>
                                <span style="color:var(--text-muted);font-size:.8rem;">{{ $panen->satuan }}</span>
                            </td>
                            <td style="color:var(--text-muted);font-size:.82rem;">
                                {{ $panen->tanggal_panen->format('d M Y') }}
                            </td>
                            <td style="font-weight:500;">
                                Rp {{ number_format($panen->total_pendapatan, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:var(--text-muted);padding:40px;">
                                Belum ada riwayat panen untuk lahan ini.
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
