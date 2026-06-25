@extends('layouts.ricemill')

@section('title', 'Tambah Penerimaan Gabah')
@section('page-title', 'Tambah Penerimaan Gabah')
@section('breadcrumb', 'Penerimaan Gabah / Tambah')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header-clean">
                <h5>Formulir Penerimaan Gabah Baru</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('ricemill.penerimaan-gabah.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Penerimaan</label>
                            <input type="date" class="form-control-custom" name="tanggal" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Petani / Pemasok</label>
                            <input list="petani-list" type="text" class="form-control-custom" name="nama_petani" placeholder="Ketik nama petani..." required>
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
                            <input type="text" class="form-control-custom" name="asal_lahan" placeholder="Contoh: Desa Karawang">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Berat Gabah (Kg)</label>
                            <input type="number" step="0.01" class="form-control-custom" name="jumlah_gabah" placeholder="0" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Kualitas Gabah</label>
                            <select class="form-select-custom" name="kualitas_gabah" required>
                                <option value="">Pilih Kualitas...</option>
                                <option value="kering">Kering</option>
                                <option value="basah">Basah</option>
                                <option value="grade_a">Grade A</option>
                                <option value="grade_b">Grade B</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Status</label>
                            <select class="form-select-custom" name="status" required>
                                <option value="menunggu">Menunggu</option>
                                <option value="diterima">Diterima</option>
                                <option value="diproses">Diproses</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom">Bukti Penerimaan (Foto/Nota)</label>
                        <input type="file" class="form-control-custom" name="bukti_foto" accept="image/*">
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Catatan / Keterangan</label>
                        <textarea class="form-control-custom" name="catatan" rows="3" placeholder="Opsional..."></textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('ricemill.penerimaan-gabah.index') }}" class="btn-outline-custom">
                            <span class="iconify" data-icon="heroicons:x-mark"></span> Batal
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <span class="iconify" data-icon="heroicons:check"></span> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
