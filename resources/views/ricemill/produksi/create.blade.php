@extends('layouts.ricemill')

@section('title', 'Catat Hasil Produksi')
@section('page-title', 'Produksi Baru')
@section('breadcrumb', 'Produksi / Tambah')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header-clean">
                <h5>Formulir Hasil Produksi (Output Beras)</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('ricemill.produksi.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label-custom">Pilih Operasional (Batch)</label>
                        @if($operasional->isEmpty())
                            <div class="alert-clean mb-2" style="background:#fef9c3;border-color:#fde047;color:#854d0e;">
                                <span class="iconify" data-icon="heroicons:exclamation-triangle" style="width:18px;color:#ca8a04;"></span>
                                <span>Tidak ada batch tersedia. Pastikan Anda sudah membuat <strong>Operasional Penggilingan</strong> terlebih dahulu, dan batch tersebut belum memiliki data produksi.</span>
                            </div>
                        @endif
                        <select class="form-select-custom" name="operasional_id" required>
                            <option value="">Pilih batch yang sedang diproses...</option>
                            @foreach($operasional as $item)
                                <option value="{{ $item->id }}"
                                        data-gabah="{{ $item->jumlah_gabah_masuk }}">
                                    {{ $item->batch_id }}
                                    — {{ $item->penerimaanGabah->nama_petani ?? 'Tanpa Petani' }}
                                    ({{ number_format($item->jumlah_gabah_masuk, 0, ',', '.') }} Kg)
                                    [{{ ucfirst($item->status) }}]
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hanya menampilkan batch yang belum memiliki data produksi.</small>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Produksi Selesai</label>
                            <input type="date" class="form-control-custom" name="tanggal_proses" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Jumlah Hasil Beras (Kg)</label>
                            <input type="number" step="0.01" id="jumlah_beras" class="form-control-custom" name="jumlah_beras" placeholder="0" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Jenis Beras yang Dihasilkan <span class="text-danger">*</span></label>
                            <select class="form-select-custom" name="jenis_beras" id="jenis_beras" required>
                                <option value="">-- Pilih Jenis Beras --</option>
                                <option value="premium">⭐ Premium</option>
                                <option value="medium">🌾 Medium</option>
                                <option value="setra_ramos">🌿 Setra Ramos</option>
                                <option value="pandan_wangi">🍃 Pandan Wangi</option>
                                <option value="biasa">🫘 Biasa</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Preview Rendemen</label>
                            <div id="rendemen-preview" style="
                                padding:11px 14px;
                                border-radius:10px;
                                background:#f0fdf4;
                                border:1px solid #b2dcc4;
                                font-size:.9rem;
                                color:#1a5c38;
                                font-weight:600;
                                min-height:42px;
                                display:flex;
                                align-items:center;
                                gap:8px;
                            ">
                                <span class="iconify" data-icon="heroicons:calculator" style="width:18px;"></span>
                                <span id="rendemen-value">Masukkan jumlah beras...</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Catatan Produksi</label>
                        <textarea class="form-control-custom" name="catatan" rows="3" placeholder="Opsional..."></textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('ricemill.produksi.index') }}" class="btn-outline-custom">
                            <span class="iconify" data-icon="heroicons:x-mark"></span> Batal
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <span class="iconify" data-icon="heroicons:check"></span> Simpan Hasil Produksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const selectOp   = document.querySelector('[name="operasional_id"]');
    const inputBeras = document.getElementById('jumlah_beras');
    const previewBox = document.getElementById('rendemen-preview');
    const previewVal = document.getElementById('rendemen-value');

    const submitBtn = document.querySelector('button[type="submit"]');

    function hitungRendemen() {
        const selectedOption = selectOp.options[selectOp.selectedIndex];
        const gabah  = parseFloat(selectedOption?.getAttribute('data-gabah') || 0);
        const beras  = parseFloat(inputBeras.value);

        if (!selectOp.value || !gabah || !beras || beras <= 0) {
            previewVal.textContent = 'Pilih batch & masukkan jumlah beras...';
            previewBox.style.background = '#f0fdf4';
            previewBox.style.borderColor = '#b2dcc4';
            previewBox.style.color = '#1a5c38';
            submitBtn.disabled = true;
            return;
        }

        const rendemen = ((beras / gabah) * 100).toFixed(1);
        const rendah   = parseFloat(rendemen) < 60;
        const tinggi   = parseFloat(rendemen) > 100;
        const tidakValid = rendah || tinggi;

        if (rendah) {
            previewVal.textContent = `Rendemen: ${rendemen}% ⚠️ DI BAWAH STANDAR (min. 60%)`;
        } else if (tinggi) {
            previewVal.textContent = `Rendemen: ${rendemen}% ⚠️ TIDAK MASUK AKAL (maks. 100%)`;
        } else {
            previewVal.textContent = `Rendemen: ${rendemen}% ✅ Normal`;
        }

        previewBox.style.background = tidakValid ? '#fef2f2' : '#f0fdf4';
        previewBox.style.borderColor = tidakValid ? '#fca5a5' : '#b2dcc4';
        previewBox.style.color       = tidakValid ? '#991b1b' : '#1a5c38';
        
        submitBtn.disabled = tidakValid;
    }

    selectOp.addEventListener('change', hitungRendemen);
    inputBeras.addEventListener('input', hitungRendemen);
    
    // Inisialisasi awal agar tombol disubmit disable jika kosong
    document.addEventListener('DOMContentLoaded', hitungRendemen);
</script>
@endpush
