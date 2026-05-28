@extends('layouts.petani')

@section('title', 'Catat Panen')
@section('page-title', 'Catat Riwayat Panen')
@section('breadcrumb', 'Panen → Catat Baru')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card">
    <div class="card-header-clean">
        <h5>Form Pencatatan Panen</h5>
        <a href="{{ route('petani.panen.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
            <span class="iconify" data-icon="heroicons:arrow-left" style="width:14px;height:14px;"></span> Kembali
        </a>
    </div>
    <div style="padding:24px;">
        <form action="{{ route('petani.panen.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-custom">Pilih Lahan <span style="color:#c0392b;">*</span></label>
                    <select name="profil_lahan_id" class="form-select-custom @error('profil_lahan_id') is-invalid @enderror">
                        <option value="">-- Pilih Lahan --</option>
                        @foreach($lahans as $lahan)
                            <option value="{{ $lahan->id }}" {{ old('profil_lahan_id')==$lahan->id ? 'selected':'' }}>
                                {{ $lahan->nama_lahan }} — {{ $lahan->lokasi }}
                            </option>
                        @endforeach
                    </select>
                    @error('profil_lahan_id')
                        <div style="color:#c0392b;font-size:.8rem;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label-custom">Tanggal Panen <span style="color:#c0392b;">*</span></label>
                    <input type="date" name="tanggal_panen" value="{{ old('tanggal_panen', now()->format('Y-m-d')) }}"
                           class="form-control-custom @error('tanggal_panen') is-invalid @enderror">
                    @error('tanggal_panen')
                        <div style="color:#c0392b;font-size:.8rem;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label-custom">Jenis Tanaman <span style="color:#c0392b;">*</span></label>
                    <input type="text" name="jenis_tanaman" value="{{ old('jenis_tanaman') }}"
                           class="form-control-custom @error('jenis_tanaman') is-invalid @enderror"
                           placeholder="cth: Padi, Jagung, Kedelai">
                    @error('jenis_tanaman')
                        <div style="color:#c0392b;font-size:.8rem;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label-custom">Jumlah Hasil <span style="color:#c0392b;">*</span></label>
                    <input type="number" name="jumlah_hasil" value="{{ old('jumlah_hasil') }}"
                           step="0.01" min="0.01"
                           class="form-control-custom @error('jumlah_hasil') is-invalid @enderror"
                           placeholder="cth: 500" id="jumlah_hasil" oninput="hitungTotal()">
                    @error('jumlah_hasil')
                        <div style="color:#c0392b;font-size:.8rem;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label-custom">Satuan <span style="color:#c0392b;">*</span></label>
                    <select name="satuan" class="form-select-custom">
                        <option value="kg"      {{ old('satuan','kg')=='kg'      ? 'selected':'' }}>Kilogram (kg)</option>
                        <option value="ton"     {{ old('satuan')=='ton'     ? 'selected':'' }}>Ton</option>
                        <option value="kwintal" {{ old('satuan')=='kwintal' ? 'selected':'' }}>Kwintal</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label-custom">Harga per kg (Rp)</label>
                    <input type="number" name="harga_per_kg" value="{{ old('harga_per_kg') }}"
                           class="form-control-custom" placeholder="cth: 5000"
                           id="harga_per_kg" oninput="hitungTotal()">
                </div>

                <div class="col-md-6">
                    <label class="form-label-custom">Total Pendapatan (otomatis)</label>
                    <input type="number" name="total_pendapatan" id="total_pendapatan"
                           class="form-control-custom" readonly
                           style="background:#f8faf8;color:var(--primary);font-weight:600;"
                           placeholder="Terisi otomatis">
                </div>

                <div class="col-12">
                    <label class="form-label-custom">Catatan</label>
                    <textarea name="catatan" rows="3" class="form-control-custom"
                              placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label-custom">Upload Bukti Foto Panen</label>
                    <div class="upload-zone" onclick="document.getElementById('bukti_foto').click()">
                        <span class="iconify" data-icon="heroicons:camera" style="width:32px;height:32px;color:var(--text-muted);margin-bottom:8px;"></span>
                        <div style="font-size:.88rem;color:var(--text-muted);">
                            Klik untuk upload foto hasil panen<br>
                            <span style="font-size:.78rem;">JPG, PNG — maks. 2MB</span>
                        </div>
                        <div id="foto-name" style="margin-top:8px;font-size:.82rem;color:var(--primary);font-weight:500;"></div>
                    </div>
                    <input type="file" id="bukti_foto" name="bukti_foto" accept="image/*"
                           style="display:none;" onchange="showFileName(this,'foto-name')">
                    @error('bukti_foto')
                        <div style="color:#c0392b;font-size:.8rem;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn-primary-custom">
                    <span class="iconify" data-icon="heroicons:check" style="width:16px;height:16px;"></span> Simpan Panen
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
    const jumlah = parseFloat