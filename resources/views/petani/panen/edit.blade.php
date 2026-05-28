@extends('layouts.petani')

@section('title', 'Edit Panen')
@section('page-title', 'Edit Data Panen')
@section('breadcrumb', 'Panen → Edit')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card">
    <div class="card-header-clean">
        <h5>Edit Riwayat Panen</h5>
        <a href="{{ route('petani.panen.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
            <span class="iconify" data-icon="heroicons:arrow-left" style="width:14px;height:14px;"></span> Kembali
        </a>
    </div>
    <div style="padding:24px;">
        @if($errors->any())
        <div class="alert-clean alert-danger-clean mb-4">
            <span class="iconify" data-icon="heroicons:x-circle" style="width:18px;height:18px;flex-shrink:0;"></span>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
        @endif

        <form action="{{ route('petani.panen.update', $panen) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-custom">Pilih Lahan <span style="color:#c0392b;">*</span></label>
                    <select name="profil_lahan_id" class="form-select-custom">
                        <option value="">-- Pilih Lahan --</option>
                        @foreach($lahans as $lahan)
                            <option value="{{ $lahan->id }}"
                                {{ old('profil_lahan_id', $panen->profil_lahan_id)==$lahan->id ? 'selected':'' }}>
                                {{ $lahan->nama_lahan }} — {{ $lahan->lokasi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label-custom">Tanggal Panen <span style="color:#c0392b;">*</span></label>
                    <input type="date" name="tanggal_panen"
                           value="{{ old('tanggal_panen', $panen->tanggal_panen->format('Y-m-d')) }}"
                           class="form-control-custom">
                </div>

                <div class="col-md-6">
                    <label class="form-label-custom">Jenis Tanaman <span style="color:#c0392b;">*</span></label>
                    <input type="text" name="jenis_tanaman"
                           value="{{ old('jenis_tanaman', $panen->jenis_tanaman) }}"
                           class="form-control-custom"
                           placeholder="cth: Padi IR64">
                </div>

                <div class="col-md-3">
                    <label class="form-label-custom">Jumlah Hasil <span style="color:#c0392b;">*</span></label>
                    <input type="number" name="jumlah_hasil" step="0.01"
                           value="{{ old('jumlah_hasil', $panen->jumlah_hasil) }}"
                           class="form-control-custom" id="jumlah_hasil" oninput="hitungTotal()">
                </div>

                <div class="col-md-3">
                    <label class="form-label-custom">Satuan</label>
                    <select name="satuan" class="form-select-custom">
                        <option value="kg"      {{ old('satuan',$panen->satuan)=='kg'      ? 'selected':'' }}>kg</option>
                        <option value="ton"     {{ old('satuan',$panen->satuan)=='ton'     ? 'selected':'' }}>Ton</option>
                        <option value="kwintal" {{ old('satuan',$panen->satuan)=='kwintal' ? 'selected':'' }}>Kwintal</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label-custom">Harga per kg (Rp)</label>
                    <input type="number" name="harga_per_kg"
                           value="{{ old('harga_per_kg', $panen->harga_per_kg) }}"
                           class="form-control-custom" id="harga_per_kg" oninput="hitungTotal()"
                           placeholder="0">
                </div>

                <div class="col-md-6">
                    <label class="form-label-custom">Total Pendapatan</label>
                    <input type="number" name="total_pendapatan" id="total_pendapatan"
                           value="{{ old('total_pendapatan', $panen->total_pendapatan) }}"
                           class="form-control-custom" readonly
                           style="background:#f8faf8;color:var(--primary);font-weight:600;">
                </div>

                <div class="col-12">
                    <label class="form-label-custom">Catatan</label>
                    <textarea name="catatan" rows="3" class="form-control-custom"
                              placeholder="Opsional...">{{ old('catatan', $panen->catatan) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label-custom">Upload Foto Baru (opsional)</label>
                    @if($panen->bukti_foto)
                        <div style="margin-bottom:10px;">
                            <img src="{{ Storage::url($panen->bukti_foto) }}"
                                 style="height:80px;border-radius:10px;object-fit:cover;">
                            <div style="font-size:.78rem;color:var(--text-muted);margin-top:4px;">Foto saat ini</div>
                        </div>
                    @endif
                    <div class="upload-zone" onclick="document.getElementById('bukti_foto').click()">
                        <span class="iconify" data-icon="heroicons:camera" style="width:28px;height:28px;color:var(--text-muted);"></span>
                        <div style="font-size:.85rem;color:var(--text-muted);margin-top:6px;">
                            Klik untuk ganti foto
                        </div>
                        <div id="foto-name" style="font-size:.82rem;color:var(--primary);font-weight:500;margin-top:4px;"></div>
                    </div>
                    <input type="file" id="bukti_foto" name="bukti_foto" accept="image/*"
                           style="display:none;" onchange="showFileName(this,'foto-name')">
                </div>
            </div>

            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn-primary-custom">
                    <span class="iconify" data-icon="heroicons:check" style="width:16px;height:16px;"></span>
                    Simpan Perubahan
                </button>
                <a href="{{ route('petani.panen.index') }}" class="btn-outline-custom">Batal</a>
            </div>
        </form>
    </div>
</div>

</div>
</div>
@endsection

@push('scripts')
<script>
function hitungTotal() {
    const jumlah = parseFloat(document.getElementById('jumlah_hasil').value) || 0;
    const harga  = parseFloat(document.getElementById('harga_per_kg').value) || 0;
    document.getElementById('total_pendapatan').value = (jumlah * harga).toFixed(2);
}

function showFileName(input, targetId) {
    const target = document.getElementById(targetId);
    target.textContent = input.files[0] ? input.files[0].name : '';
}
</script>
@endpush