@extends('layouts.ricemill')

@section('title', 'Edit Penerimaan Gabah')
@section('page-title', 'Edit Penerimaan Gabah')
@section('breadcrumb', 'Penerimaan Gabah / Edit')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header-clean">
                <h5>Edit Data Penerimaan #TRX-{{ $penerimaan->id }}</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('ricemill.penerimaan-gabah.update', $penerimaan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Penerimaan</label>
                            <input type="date" class="form-control-custom" name="tanggal" value="{{ $penerimaan->tanggal->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Petani / Pemasok</label>
                            <input list="petani-list" type="text" class="form-control-custom" name="nama_petani" value="{{ $penerimaan->nama_petani }}" required>
                            <datalist id="petani-list">
                                @foreach($petanis as $petani)
                                    <option value="{{ $petani->name }}">
                                @endforeach
                            </datalist>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Asal Lahan</label>
                            <input type="text" class="form-control-custom" name="asal_lahan" value="{{ $penerimaan->asal_lahan }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Berat Gabah (Kg)</label>
                            <input type="number" step="0.01" class="form-control-custom" name="jumlah_gabah" value="{{ $penerimaan->jumlah_gabah }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Kualitas Gabah</label>
                            <select class="form-select-custom" name="kualitas_gabah" required>
                                <option value="kering"   {{ $penerimaan->kualitas_gabah == 'kering' ? 'selected' : '' }}>Kering</option>
                                <option value="basah"    {{ $penerimaan->kualitas_gabah == 'basah' ? 'selected' : '' }}>Basah</option>
                                <option value="grade_a"  {{ $penerimaan->kualitas_gabah == 'grade_a' ? 'selected' : '' }}>Grade A</option>
                                <option value="grade_b"  {{ $penerimaan->kualitas_gabah == 'grade_b' ? 'selected' : '' }}>Grade B</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Status</label>
                            <select class="form-select-custom" name="status" required>
                                <option value="menunggu" {{ $penerimaan->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="diterima" {{ $penerimaan->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                <option value="diproses" {{ $penerimaan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="selesai"  {{ $penerimaan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Update Bukti (Biarkan kosong jika tidak diubah)</label>
                        <input type="file" class="form-control-custom" name="bukti_foto" accept="image/*">
                        @if($penerimaan->bukti_foto)
                            <div class="mt-2">
                                <small class="text-muted">Foto saat ini:</small><br>
                                <img src="{{ Storage::url($penerimaan->bukti_foto) }}" style="width:100px;border-radius:8px;">
                            </div>
                        @endif
                    </div>

                    @if($penerimaan->setoran_id)
                    <hr class="my-4">
                    <h6 class="mb-3 text-primary fw-bold"><span class="iconify me-1" data-icon="heroicons:banknotes"></span> Informasi Keuangan (Setoran Petani)</h6>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label-custom">Biaya Penggilingan (Rp)</label>
                            <input type="number" step="0.01" class="form-control-custom" name="biaya_penggilingan" value="{{ $penerimaan->setoran->biaya_penggilingan ?? 0 }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Hasil Bersih (Kg)</label>
                            <input type="number" step="0.01" class="form-control-custom" name="hasil_bersih" value="{{ $penerimaan->setoran->hasil_bersih ?? 0 }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Total Pendapatan Petani (Rp)</label>
                            <input type="number" step="0.01" class="form-control-custom" name="total_pendapatan" value="{{ $penerimaan->setoran->total_pendapatan ?? 0 }}">
                        </div>
                    </div>
                    <div class="alert alert-info py-2" role="alert">
                        <small>Data ini akan diperbarui ke Nota Setoran Petani.</small>
                    </div>
                    <hr class="mb-4">
                    @endif

                    <div class="mb-4">
                        <label class="form-label-custom">Catatan / Keterangan</label>
                        <textarea class="form-control-custom" name="catatan" rows="3">{{ $penerimaan->catatan }}</textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('ricemill.penerimaan-gabah.index') }}" class="btn-outline-custom">
                            <span class="iconify" data-icon="heroicons:x-mark"></span> Batal
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <span class="iconify" data-icon="heroicons:check"></span> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
