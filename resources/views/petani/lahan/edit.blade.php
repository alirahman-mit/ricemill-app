@extends('layouts.petani')

@section('title', 'Edit Lahan')
@section('page-title', 'Edit Profil Lahan')
@section('breadcrumb', 'Lahan → Edit → ' . $lahan->nama_lahan)

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">

<div class="card">
    <div class="card-header-clean">
        <h5>Edit Profil Lahan</h5>
        <a href="{{ route('petani.lahan.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
            <span class="iconify" data-icon="heroicons:arrow-left" style="width:14px;height:14px;"></span> Kembali
        </a>
    </div>

    <div style="padding:24px;">
        <form action="{{ route('petani.lahan.update', $lahan) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-custom">Nama Lahan <span style="color:#c0392b;">*</span></label>
                    <input type="text" name="nama_lahan" value="{{ old('nama_lahan', $lahan->nama_lahan) }}"
                           class="form-control-custom @error('nama_lahan') is-invalid @enderror"
                           placeholder="cth: Sawah Blok A">
                    @error('nama_lahan')
                        <div style="color:#c0392b;font-size:.8rem;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label-custom">Lokasi <span style="color:#c0392b;">*</span></label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $lahan->lokasi) }}"
                           class="form-control-custom @error('lokasi') is-invalid @enderror"
                           placeholder="cth: Desa Sukamaju, Kec. Cianjur">
                    @error('lokasi')
                        <div style="color:#c0392b;font-size:.8rem;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label-custom">Luas Lahan (Hektar) <span style="color:#c0392b;">*</span></label>
                    <input type="number" name="luas_lahan" value="{{ old('luas_lahan', $lahan->luas_lahan) }}"
                           step="0.01" min="0.01"
                           class="form-control-custom @error('luas_lahan') is-invalid @enderror"
                           placeholder="cth: 2.5">
                    @error('luas_lahan')
                        <div style="color:#c0392b;font-size:.8rem;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label-custom">Jenis Tanah <span style="color:#c0392b;">*</span></label>
                    <select name="jenis_tanah" class="form-select-custom @error('jenis_tanah') is-invalid @enderror">
                        <option value="">-- Pilih Jenis Tanah --</option>
                        @foreach(['tanah_liat' => 'Tanah Liat', 'tanah_pasir' => 'Tanah Pasir', 'tanah_humus' => 'Tanah Humus', 'tanah_gambut' => 'Tanah Gambut', 'lainnya' => 'Lainnya'] as $val => $label)
                            <option value="{{ $val }}" {{ old('jenis_tanah', $lahan->jenis_tanah) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('jenis_tanah')
                        <div style="color:#c0392b;font-size:.8rem;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label-custom">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                              class="form-control-custom"
                              placeholder="Deskripsi tambahan tentang lahan...">{{ old('deskripsi', $lahan->deskripsi) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label-custom">Foto Lahan</label>
                    @if($lahan->foto)
                        <div style="margin-bottom:12px;">
                            <img src="{{ Storage::url($lahan->foto) }}"
                                 style="width:120px;height:80px;border-radius:10px;object-fit:cover;">
                            <span style="font-size:.8rem;color:var(--text-muted);margin-left:8px;">Foto saat ini</span>
                        </div>
                    @endif
                    <div class="upload-zone" onclick="document.getElementById('foto').click()">
                        <span class="iconify" data-icon="heroicons:photo" style="width:32px;height:32px;color:var(--text-muted);margin-bottom:8px;"></span>
                        <div style="font-size:.88rem;color:var(--text-muted);">
                            Klik untuk ganti foto lahan<br>
                            <span style="font-size:.78rem;">JPG, PNG — maks. 2MB</span>
                        </div>
                        <div id="foto-name" style="margin-top:8px;font-size:.82rem;color:var(--primary);font-weight:500;"></div>
                    </div>
                    <input type="file" id="foto" name="foto" accept="image/*"
                           style="display:none;" onchange="showFileName(this, 'foto-name')">
                    @error('foto')
                        <div style="color:#c0392b;font-size:.8rem;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn-primary-custom">
                    <span class="iconify" data-icon="heroicons:check" style="width:16px;height:16px;"></span>
                    Simpan Perubahan
                </button>
                <a href="{{ route('petani.lahan.index') }}" class="btn-outline-custom">Batal</a>
            </div>
        </form>
    </div>
</div>

</div>
</div>
@endsection

@push('scripts')
<script>
function showFileName(input, targetId) {
    const target = document.getElementById(targetId);
    target.textContent = input.files[0] ? input.files[0].name : '';
}
</script>
@endpush
