@extends('layouts.packager')

@section('title', 'Terima Beras Putih')
@section('page-title', 'Terima Beras Putih')
@section('breadcrumb', 'Penerimaan / Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header-clean">
                <h5>Konfirmasi Penerimaan Beras dari Rice Mill</h5>
            </div>
            <div class="card-body p-4">
                <form action="#" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Tanggal Terima</label>
                            <input type="date" class="form-control-custom" name="tanggal_terima" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Nomor Surat Jalan</label>
                            <input type="text" class="form-control-custom" name="no_surat_jalan" placeholder="SJ-XXXXX" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Jumlah Beras (Kg)</label>
                            <input type="number" class="form-control-custom" name="jumlah_beras" placeholder="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Kualitas Beras</label>
                            <select class="form-select-custom" name="kualitas" required>
                                <option value="Premium">Premium</option>
                                <option value="Medium A">Medium A</option>
                                <option value="Medium B">Medium B</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Asal Rice Mill</label>
                        <input type="text" class="form-control-custom" name="asal_ricemill" placeholder="Nama Unit Penggilingan" required>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('packager.penerimaan-beras.index') }}" class="btn-outline-custom">Batal</a>
                        <button type="submit" class="btn-primary-custom">Konfirmasi Penerimaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
