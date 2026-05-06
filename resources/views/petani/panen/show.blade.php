@extends('layouts.petani')

@section('title', 'Detail Panen')
@section('page-title', 'Detail Riwayat Panen')
@section('breadcrumb', 'Panen → Detail')

@section('topbar-actions')
    <a href="{{ route('petani.panen.edit', $panen) }}" class="btn-primary-custom">
        <i data-lucide="pencil" style="width:16px;height:16px;"></i> Edit
    </a>
@endsection

@section('content')
<div class="row g-3 justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header-clean">
                <h5>Informasi Panen</h5>
                <a href="{{ route('petani.panen.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
                    <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Kembali
                </a>
            </div>

            <div style="padding:24px;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px;">Lahan</div>
                        <div style="font-weight:600;">{{ $panen->profilLahan->nama_lahan ?? '-' }}</div>
                        <div style="font-size:.82rem;color:var(--text-muted);">{{ $panen->profilLahan->lokasi ?? '' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px;">Tanaman</div>
                        <span class="badge-custom badge-success-custom">{{ $panen->jenis_tanaman }}</span>
                    </div>
                    <div class="col-md-4">
                        <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px;">Tanggal Panen</div>
                        <div style="font-weight:500;">{{ $panen->tanggal_panen->format('d F Y') }}</div>
                    </div>
                    <div class="col-md-4">
                        <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px;">Hasil Panen</div>
                        <div style="font-weight:600;font-size:1.1rem;">
                            {{ number_format($panen->jumlah_hasil, 0, ',', '.') }}
                            <span style="font-size:.85rem;color:var(--text-muted);">{{ $panen->satuan }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px;">Harga/kg</div>
                        <div style="font-weight:500;">
                            @if($panen->harga_per_kg)
                                Rp {{ number_format($panen->harga_per_kg, 0, ',', '.') }}
                            @else
                                —
                            @endif
                        </div>
                    </div>

                    @if($panen->total_pendapatan)
                    <div class="col-12">
                        <div style="background:#e8f5ee;border-radius:12px;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;">
                            <span style="color:var(--text-muted);font-size:.88rem;">Total Pendapatan</span>
                            <span style="font-size:1.2rem;font-weight:700;color:#1a5c38;">
                                Rp {{ number_format($panen->total_pendapatan, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @endif

                    @if($panen->catatan)
                    <div class="col-12" style="margin-top:8px;">
                        <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Catatan</div>
                        <p style="font-size:.88rem;color:var(--text-main);line-height:1.6;">{{ $panen->catatan }}</p>
                    </div>
                    @endif

                    @if($panen->bukti_foto)
                    <div class="col-12" style="margin-top:8px;">
                        <div style="font-size:.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;">Bukti Foto</div>
                        <a href="{{ Storage::url($panen->bukti_foto) }}" target="_blank">
                            <img src="{{ Storage::url($panen->bukti_foto) }}"
                                 style="max-width:300px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.08);">
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
