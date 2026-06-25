@extends('layouts.petani')

@section('title', 'Edit Setoran')
@section('page-title', 'Edit Setoran Penggilingan')
@section('breadcrumb', 'Setoran → Edit')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card">
    <div class="card-header-clean">
        <h5>Edit Data Setoran</h5>
        <a href="{{ route('petani.setoran.index') }}" class="btn-outline-custom" style="font-size:.8rem;padding:6px 12px;">
            <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Kembali
        </a>
    </div>
    <div style="padding:24px;">
        <form action="{{ route('petani.setoran.update', $setoran) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-custom">Pilih Ricemill (Penggilingan) <span style="color:#c0392b;">*</span></label>
                    <select name="ricemill_id" class="form-select-custom @error('ricemill_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Ricemill --</option>
                        @foreach($ricemills as $rm)
                            <option value="{{ $rm->id }}" {{ (old('ricemill_id') ?? $setoran->ricemill_id) == $rm->id ? 'selected' : '' }}>
                                {{ $rm->name }} ({{ $rm->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('ricemill_id')
                        <div style="color:#c0392b;font-size:.8rem;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Pilih Asal Lahan <span class="text-muted">(Opsional)</span></label>
                    <select name="profil_lahan_id" class="form-select-custom @error('profil_lahan_id') is-invalid @enderror">
                        <option value="">-- Tidak Memilih Lahan --</option>
                        @foreach($lahans as $lahan)
                            <option value="{{ $lahan->id }}" {{ (old('profil_lahan_id') ?? $setoran->profil_lahan_id) == $lahan->id ? 'selected' : '' }}>
                                {{ $lahan->nama_lahan }} ({{ $lahan->lokasi }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted" style="font-size:0.75rem;">Lahan akan dikirim ke Ricemill tujuan sebagai data Asal Lahan.</small>
                    @error('profil_lahan_id')
                        <div style="color:#c0392b;font-size:.8rem;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label-custom">Tanggal Setoran <span style="color:#c0392b;">*</span></label>
                    <input type="date" name="tanggal_setoran"
                           value="{{ old('tanggal_setoran', $setoran->tanggal_setoran->format('Y-m-d')) }}"
                           class="form-control-custom">
                </div>

                <div class="col-md-6">
                    <label class="form-label-custom">Jenis Hasil Panen <span style="color:#c0392b;">*</span></label>
                    <input type="text" name="jenis_hasil_panen"
                           value="{{ old('jenis_hasil_panen', $setoran->jenis_hasil_panen) }}"
                           class="form-control-custom">
                </div>

                <div class="col-md-12">
                    <label class="form-label-custom">Jumlah Setoran (kg)</label>
                    <input type="number" name="jumlah_setoran" step="0.01"
                           value="{{ old('jumlah_setoran', $setoran->jumlah_setoran) }}"
                           class="form-control-custom">
                </div>

                <div class="col-md-6">
                    <label class="form-label-custom">Status</label>
                    <select name="status" class="form-select-custom">
                        <option value="pending"  {{ old('status',$setoran->status)=='pending'  ? 'selected':'' }}>Pending</option>
                        <option value="diproses" {{ old('status',$setoran->status)=='diproses' ? 'selected':'' }}>Diproses</option>
                        <option value="selesai"  {{ old('status',$setoran->status)=='selesai'  ? 'selected':'' }}>Selesai</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label-custom">Catatan</label>
                    <textarea name="catatan" rows="3" class="form-control-custom">{{ old('catatan', $setoran->catatan) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label-custom">Ganti Bukti Nota (opsional)</label>
                    @if($setoran->bukti_nota)
                        <div style="margin-bottom:10px;">
                            <img src="{{ Storage::url($setoran->bukti_nota) }}"
                                 style="height:80px;border-radius:10px;object-fit:cover;">
                            <div style="font-size:.78rem;color:var(--text-muted);margin-top:4px;">Nota saat ini</div>
                        </div>
                    @endif
                    <div class="upload-zone" onclick="document.getElementById('bukti_nota').click()">
                        <i data-lucide="file-text" style="width:28px;height:28px;color:var(--text-muted);"></i>
                        <div style="font-size:.85rem;color:var(--text-muted);margin-top:6px;">Klik untuk ganti nota</div>
                        <div id="nota-name" style="font-size:.82rem;color:var(--primary);font-weight:500;margin-top:4px;"></div>
                    </div>
                    <input type="file" id="bukti_nota" name="bukti_nota" accept="image/*"
                           style="display:none;" onchange="showFileName(this,'nota-name')">
                </div>
            </div>

            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn-primary-custom">
                    <span class="iconify" data-icon="heroicons:check" style="width:16px;height:16px;"></span> Update Setoran
                </button>
                <a href="{{ route('petani.setoran.index') }}" class="btn-outline-custom">Batal</a>
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
    document.getElementById(targetId).textContent = input.files[0]?.name || '';
}
</script>
@endpush