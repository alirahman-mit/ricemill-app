@extends('layouts.ricemill')

@section('title', 'Penerimaan Gabah')
@section('page-title', 'Penerimaan Gabah')
@section('breadcrumb', 'Dashboard / Penerimaan Gabah')

@section('topbar-actions')
<a href="{{ route('ricemill.penerimaan-gabah.create') }}" class="btn-primary-custom">
    <span class="iconify" data-icon="heroicons:plus-circle"></span> Tambah Penerimaan
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header-clean">
        <h5>Daftar Penerimaan Gabah</h5>
        <div class="d-flex gap-2">
            <button class="btn-outline-custom btn-sm"><span class="iconify" data-icon="heroicons:funnel"></span> Filter</button>
            <button class="btn-outline-custom btn-sm"><span class="iconify" data-icon="heroicons:arrow-down-tray"></span> Export</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode Transaksi</th>
                    <th>Nama Petani</th>
                    <th>Berat Gabah (Kg)</th>
                    <th>Jenis Gabah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penerimaan as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td class="fw-medium">#TRX-{{ $item->id }}</td>
                    <td>{{ $item->nama_petani ?? 'Umum' }}</td>
                    <td>{{ number_format($item->jumlah_gabah, 0, ',', '.') }} Kg</td>
                    <td>{{ ucfirst($item->kualitas_gabah) }}</td>
                    <td>
                        <span class="badge-custom {{ $item->status == 'selesai' ? 'badge-success-custom' : ($item->status == 'diproses' ? 'badge-info-custom' : 'badge-warning-custom') }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('ricemill.penerimaan-gabah.edit', $item) }}" class="btn-outline-custom btn-sm" title="Edit">
                                <span class="iconify" data-icon="heroicons:pencil" style="width:14px;height:14px;"></span>
                            </a>
                            <form action="{{ route('ricemill.penerimaan-gabah.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-outline-custom btn-sm text-danger" style="border-color:#f5b8b8;">
                                    <span class="iconify" data-icon="heroicons:trash" style="width:14px;height:14px;"></span>
                                </button>
                            </form>
                            @if($item->status === 'selesai' && $item->setoran_id && $item->setoran->total_pendapatan)
                            <button type="button" class="btn-primary-custom btn-sm" data-bs-toggle="modal" data-bs-target="#notaModal-{{ $item->id }}" title="Lihat Nota">
                                <span class="iconify" data-icon="heroicons:document-text" style="width:14px;height:14px;"></span>
                            </button>

                            <!-- Modal Nota -->
                            <div class="modal fade" id="notaModal-{{ $item->id }}" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="border:none;border-radius:12px;">
                                  <div class="modal-header" style="background:#f8f9fa;border-bottom:1px solid #eee;border-radius:12px 12px 0 0;">
                                    <h5 class="modal-title fw-bold text-dark"><span class="iconify me-2 text-primary" data-icon="heroicons:document-check"></span>Nota Pembayaran Setoran</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body p-4 text-start">
                                    <div class="text-center mb-4">
                                        <h4 class="mb-1 fw-bold text-primary">TRX-{{ $item->setoran->id }}</h4>
                                        <p class="text-muted small mb-0">{{ $item->setoran->tanggal_setoran->format('d F Y') }}</p>
                                    </div>
                                    <table class="table table-borderless table-sm mb-0">
                                        <tr>
                                            <td class="text-muted">Nama Petani</td>
                                            <td class="text-end fw-medium">{{ $item->nama_petani }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Berat Gabah Kotor</td>
                                            <td class="text-end fw-medium">{{ number_format($item->setoran->jumlah_setoran, 0) }} kg</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Hasil Bersih</td>
                                            <td class="text-end fw-medium">{{ number_format($item->setoran->hasil_bersih, 0) }} kg</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Biaya Penggilingan</td>
                                            <td class="text-end text-danger fw-medium">- Rp {{ number_format($item->setoran->biaya_penggilingan, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr style="border-top:1px dashed #ccc;">
                                            <td class="pt-3 fw-bold">Total Dibayarkan</td>
                                            <td class="text-end pt-3 fw-bold text-success" style="font-size:1.2rem;">Rp {{ number_format($item->setoran->total_pendapatan, 0, ',', '.') }}</td>
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
                    <td colspan="7" class="text-center py-5 text-muted">
                        <span class="iconify" data-icon="heroicons:inbox-stack" style="width:40px;height:40px;opacity:0.3;" class="mb-2"></span>
                        <p>Belum ada data penerimaan gabah.</p>
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
