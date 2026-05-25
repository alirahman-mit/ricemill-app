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
                        <select class="form-select-custom" name="operasional_id" required>
                            <option value="">Pilih batch yang sedang diproses...</option>
                            @foreach($operasional as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->batch_id }} - {{ $item->penerimaanGabah->nama_petani }} (Gabah: {{ $item->jumlah_gabah_masuk }} Kg)
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hanya menampilkan operasional yang belum selesai.</small>
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
    // Data gabah per operasional (ambil dari PHP ke JS)
    const gabahData = {
        @foreach($operasional as $item)
            {{ $item->id }}: {{ $item->jumlah_gabah_masuk }},
        @endforeach
    };

    const selectOp  = document.querySelector('[name="operasional_id"]');
    const inputBeras = document.getElementById('jumlah_beras');
    const previewBox = document.getElementById('rendemen-preview');
    const previewVal = document.getElementById('rendemen-value');

    function hitungRendemen() {
        const opId   = selectOp.value;
        const beras  = parseFloat(inputBeras.value);
        const gabah  = gabahData[opId];

        if (!opId || !gabah || !beras || beras <= 0) {
            previewVal.textContent = 'Pilih batch & masukkan jumlah beras...';
            previewBox.style.background = '#f0fdf4';
            previewBox.style.borderColor = '#b2dcc4';
            previewBox.style.color = '#1a5c38';
            return;
        }

        const rendemen = ((beras / gabah) * 100).toFixed(1);
        const rendah   = parseFloat(rendemen) < 60;

        previewVal.textContent = `Rendemen: ${rendemen}% ${rendah ? '⚠️ DI BAWAH STANDAR (min. 60%)' : '✅ Normal'}`;
        previewBox.style.background = rendah ? '#fef2f2' : '#f0fdf4';
        previewBox.style.borderColor = rendah ? '#fca5a5' : '#b2dcc4';
        previewBox.style.color       = rendah ? '#991b1b' : '#1a5c38';
    }

    selectOp.addEventListener('change', hitungRendemen);
    inputBeras.addEventListener('input',  hitungRendemen);
</script>
@endpush
