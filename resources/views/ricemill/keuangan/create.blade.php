@extends('layouts.ricemill')

@section('title', 'Catat Transaksi Keuangan')
@section('page-title', 'Transaksi Baru')
@section('breadcrumb', 'Keuangan / Tambah')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header-clean">
                <h5>Formulir Pencatatan Kas</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('ricemill.keuangan.store') }}" method="POST">
                    @csrf

                    @if($errors->any())
                    <div class="alert alert-danger mb-3" style="background:#fef2f2;border:1px solid #fca5a5;border-radius:10px;padding:12px 16px;color:#991b1b;font-size:.875rem;">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Tipe Transaksi <span style="color:#c0392b;">*</span></label>
                            <select class="form-select-custom" name="tipe" id="tipe" required onchange="updateKategori()">
                                <option value="pemasukan" {{ old('tipe') == 'pemasukan' ? 'selected' : '' }}>💰 Pemasukan (+)</option>
                                <option value="pengeluaran" {{ old('tipe') == 'pengeluaran' ? 'selected' : '' }}>💸 Pengeluaran (-)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Kategori <span style="color:#c0392b;">*</span></label>
                            <select class="form-select-custom" name="kategori" id="kategori" required>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat }}" {{ old('kategori') == $kat ? 'selected' : '' }}>
                                        {{ $kat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Jumlah (Rp) <span style="color:#c0392b;">*</span></label>
                            <input type="number"
                                   class="form-control-custom"
                                   name="jumlah"
                                   id="jumlah"
                                   placeholder="0"
                                   min="1"
                                   value="{{ old('jumlah') }}"
                                   required
                                   oninput="updatePreview()">
                            <div id="jumlah-preview" style="font-size:.78rem;color:#6b7280;margin-top:4px;"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal <span style="color:#c0392b;">*</span></label>
                            <input type="date"
                                   class="form-control-custom"
                                   name="tanggal"
                                   value="{{ old('tanggal', date('Y-m-d')) }}"
                                   required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Keterangan Singkat <span style="color:#c0392b;">*</span></label>
                        <input type="text"
                               class="form-control-custom"
                               name="keterangan"
                               placeholder="Contoh: Pembayaran Jasa Giling Bpk. Ahmad"
                               value="{{ old('keterangan') }}"
                               maxlength="255"
                               required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Catatan Tambahan</label>
                        <textarea class="form-control-custom" name="catatan" rows="3" placeholder="Opsional...">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('ricemill.keuangan.index') }}" class="btn-outline-custom">
                            <span class="iconify" data-icon="heroicons:x-mark"></span> Batal
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <span class="iconify" data-icon="heroicons:check"></span> Simpan Transaksi
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
    // Preview jumlah dalam format Rupiah
    function updatePreview() {
        const val = parseInt(document.getElementById('jumlah').value || 0);
        const preview = document.getElementById('jumlah-preview');
        if (val > 0) {
            preview.textContent = 'Rp ' + val.toLocaleString('id-ID');
        } else {
            preview.textContent = '';
        }
    }

    // Update label tipe di select
    function updateKategori() {
        const tipe = document.getElementById('tipe').value;
        const label = document.querySelector('label[for="tipe"]');
        // Optional: bisa highlight berdasarkan tipe
    }
</script>
@endpush
