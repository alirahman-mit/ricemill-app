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
                <form action="#" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Penerimaan</label>
                            <input type="date" class="form-control-custom" name="tanggal" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Petani / Pemasok</label>
                            <input type="text" class="form-control-custom" name="nama_petani" placeholder="Contoh: Bpk. Slamet" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Berat Gabah (Kg)</label>
                            <input type="number" class="form-control-custom" name="berat_gabah" placeholder="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Jenis Gabah</label>
                            <select class="form-select-custom" name="jenis_gabah" required>
                                <option value="">Pilih Jenis...</option>
                                <option value="IR64">IR64</option>
                                <option value="Ciherang">Ciherang</option>
                                <option value="Inpari">Inpari</option>
                                <option value="Ketan">Ketan</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Catatan / Keterangan</label>
                        <textarea class="form-control-custom" name="catatan" rows="3" placeholder="Opsional..."></textarea>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('ricemill.penerimaan-gabah.index') }}" class="btn-outline-custom">Batal</a>
                        <button type="submit" class="btn-primary-custom">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
