@extends('layout.app')
@section('content')

<div class="py-3 d-flex align-items-center gap-2">
    <a href="{{ route('user.worker-sum.index') }}" class="text-muted text-decoration-none">
        <i data-feather="arrow-left" style="width:18px;height:18px;"></i>
    </a>
    <h4 class="mb-0">Edit Perjanjian Kerjasama Eksternal</h4>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-xl-9">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <i data-feather="file-text" style="width:18px;height:18px;color:#4361ee;"></i>
                    <h6 class="mb-0 fw-semibold">Edit Data Perjanjian Kerjasama Eksternal</h6>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('user.agreement-external.update', ['agreement' => $agreement->id]) }}" method="POST" id="form-agreement"
                    onsubmit="confirmSave('form-agreement', 'Data perjanjian kerjasama eksternal akan disimpan')">
                    @csrf
                    @method('PATCH')

                    <div class="form-group mb-3">
                        <label for="instansi" class="form-label"><span class="text-danger">*</span> Instansi</label>
                        <input class="form-control @error('instansi') is-invalid @enderror" type="text" id="instansi"
                            required placeholder="Masukan instansi" name="instansi" value="{{ old('instansi', $agreement->instansi) }}">
                        @error('instansi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="name" class="form-label"><span class="text-danger">*</span> Nama</label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="name"
                            required placeholder="Masukan nama" name="name" value="{{ old('name', $agreement->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="regional_unit" class="form-label"><span class="text-danger">*</span> Satuan Wilayah</label>
                        <input class="form-control @error('regional_unit') is-invalid @enderror" type="text" id="regional_unit"
                            required placeholder="Masukan satuan wilayah" name="regional_unit" value="{{ old('regional_unit', $agreement->regional_unit) }}">
                        @error('regional_unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="pkt_number" class="form-label"><span class="text-danger">*</span> Nomor PKT</label>
                        <input class="form-control @error('pkt_number') is-invalid @enderror" type="text" id="pkt_number"
                            required placeholder="Masukan nomor PKT" name="pkt_number" value="{{ old('pkt_number', $agreement->pkt_number) }}">
                        @error('pkt_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="pkt_title" class="form-label"><span class="text-danger">*</span> Judul PKT</label>
                        <input class="form-control @error('pkt_title') is-invalid @enderror" type="text" id="pkt_title"
                            required placeholder="Masukan judul PKT" name="pkt_title" value="{{ old('pkt_title', $agreement->pkt_title) }}">
                        @error('pkt_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="expired_date" class="form-label"><span class="text-danger">*</span> Tanggal Masa Berlaku</label>
                        <input class="form-control @error('expired_date') is-invalid @enderror" type="date" id="expired_date"
                            required name="expired_date" value="{{ old('expired_date', $agreement->expired_date) }}">
                        @error('expired_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="note" class="form-label">Keterangan</label>
                        <input class="form-control @error('note') is-invalid @enderror" type="text" id="note"
                            placeholder="Masukan keterangan" name="note" value="{{ old('note', $agreement->note) }}">
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('user.worker-sum.index') }}" class="btn btn-danger">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
