@extends('layouts.petani')

@section('title', 'Setoran Penggilingan')
@section('page-title', 'Setoran Penggilingan')
@section('breadcrumb', 'Setoran → Daftar')

@section('topbar-actions')
    <a href="{{ route('petani.setoran.create') }}" class="btn-primary-custom">
        <span class="iconify" data-icon="heroicons:plus" style="width:16px;height:16px;"></span> Tambah Setoran
    </a>
@endsection

@section('content')

<!-- TOTAL PENDAPATAN -->
<div class="card mb-4" style="background:linear-gradient(135deg,#1a5c38,#2d7a50);border:none;">
    <div style="padding:20px 24px;display:flex;align-items:center;justify-content:space-between;">
        <div>
            <div style="color:rgba(255,255,255,.65);font-size:.8rem;margin-bottom:4px;">Total Pendapatan dari Setoran</div>
            <div style="color:#fff;font-size:1.6rem;font-weight:600;">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </div>
        </div>
        <span class="iconify" data-icon="heroicons:archive-box" style="width:40px;height:40px;color:rgba(255,255,255,.3);"></span>
    </div>
</div>

<!-- TABLE -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jenis Hasil</th>
                    <th>Jumlah</th>
                    <th>Hasil Bersih</th>
                    <th>Pendapatan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($setorans as $setoran)
                <tr>
                    <td style="color:var(--text-muted);font-size:.85rem;">
                        {{ $setoran->tanggal_setoran->format('d M Y') }}
                    </td>
                    <td><span style="font-weight:500;">{{ $setoran->jenis_hasil_panen }}</span></td>
                    <td>{{ number_format($setoran->jumlah_setoran, 0) }} kg</td>
                    <td>
                        @if($setoran->hasil_bersih)
                            {{ number_format($setoran->hasil_bersih, 0) }} kg
                        @else
                            <span style="color:var(--text-muted);">—</span>
                        @endif
                    </td>
                    <td>
                        @if($setoran->total_pendapatan)
                            <span style="color:#1a5c38;font-weight:500;">
                                Rp {{ number_format($setoran->total_pendapatan, 0, ',', '.') }}
                            </span>
                        @else
                            <span style="color:var(--text-muted);">—</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $badge = match($setoran->status) {
                                'selesai'  => 'badge-success-custom',
                                'diproses' => 'badge-info-custom',
                                default    => 'badge-warning-custom',
                            };
                        @endphp
                        <span class="badge-custom {{ $badge }}">{{ ucfirst($setoran->status) }}</span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('petani.setoran.edit', $setoran) }}"
                               class="btn-outline-custom" style="padding:6px 10px;">
                                <span class="iconify" data-icon="heroicons:pencil" style="width:14px;height:14px;"></span>
                            </a>
                            <form action="{{ route('petani.setoran.destroy', $setoran) }}" method="POST"
                                  onsubmit="return confirm('Yakin hapus data setoran ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-outline-custom"
                                        style="padding:6px 10px;color:#c0392b;border-color:#f5b8b8;">
                                    <span class="iconify" data-icon="heroicons:trash" style="width:14px;height:14px;"></span>
                                </button>
                            </form>
                            @if($setoran->status === 'selesai' && $setoran->total_pendapatan)
                            <button type="button" class="btn-primary-custom" style="padding:6px 10px;" data-bs-toggle="modal" data-bs-target="#notaModal-{{ $setoran->id }}">
                                <span class="iconify" data-icon="heroicons:document-text" style="width:14px;height:14px;"></span> Nota
                            </button>

                            <!-- Modal Nota -->
                            <div class="modal fade" id="notaModal-{{ $setoran->id }}" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="border:none;border-radius:12px;">
                                  <div class="modal-header" style="background:#f8f9fa;border-bottom:1px solid #eee;border-radius:12px 12px 0 0;">
                                    <h5 class="modal-title fw-bold text-dark"><span class="iconify me-2 text-primary" data-icon="heroicons:document-check"></span>Nota Pembayaran Setoran</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body p-4">
                                    <div class="text-center mb-4">
                                        <h4 class="mb-1 fw-bold text-primary">TRX-{{ $setoran->id }}</h4>
                                        <p class="text-muted small mb-0">{{ $setoran->tanggal_setoran->format('d F Y') }}</p>
                                    </div>
                                    <table class="table table-borderless table-sm mb-0">
                                        <tr>
                                            <td class="text-muted">Ricemill Tujuan</td>
                                            <td class="text-end fw-medium">{{ $setoran->ricemill->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Berat Gabah Kotor</td>
                                            <td class="text-end fw-medium">{{ number_format($setoran->jumlah_setoran, 0) }} kg</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Hasil Bersih</td>
                                            <td class="text-end fw-medium">{{ number_format($setoran->hasil_bersih, 0) }} kg</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Biaya Penggilingan</td>
                                            <td class="text-end text-danger fw-medium">- Rp {{ number_format($setoran->biaya_penggilingan, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr style="border-top:1px dashed #ccc;">
                                            <td class="pt-3 fw-bold">Total Pendapatan Bersih</td>
                                            <td class="text-end pt-3 fw-bold text-success" style="font-size:1.2rem;">Rp {{ number_format($setoran->total_pendapatan, 0, ',', '.') }}</td>
                                        </tr>
                                    </table>
                                  </div>
                                  <div class="modal-footer bg-light" style="border-radius:0 0 12px 12px;">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">Cetak</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:48px;color:var(--text-muted);">
                        <span class="iconify" data-icon="heroicons:archive-box" style="width:40px;height:40px;margin-bottom:12px;display:block;margin-inline:auto;"></span>
                        Belum ada data setoran.<br>
                        <a href="{{ route('petani.setoran.create') }}" style="color:var(--primary);font-weight:500;">
                            Tambah setoran pertamamu →
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($setorans->hasPages())
    <div style="padding:16px 24px;border-top:1px solid var(--border);">
        {{ $setorans->links() }}
    </div>
    @endif
</div>
@endsection