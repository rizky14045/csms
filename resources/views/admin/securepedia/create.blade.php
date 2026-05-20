@extends('layout.app')
@section('styles')
<style>
    .accordion-button::after { filter: invert(100%); }
</style>
@stop
@section('content')

<div class="py-3 d-flex align-items-center gap-2">
    <a href="{{ route('admin.securepedia.index') }}" class="text-muted text-decoration-none">
        <i data-feather="arrow-left" style="width:18px;height:18px;"></i>
    </a>
    <h4 class="mb-0">Tambah Data Securepedia</h4>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-xl-9">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <i data-feather="book-open" style="width:18px;height:18px;color:#4361ee;"></i>
                    <h6 class="mb-0 fw-semibold">Tambah Data Securepedia</h6>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{route('admin.securepedia.store')}}" enctype="multipart/form-data" method="POST"
                    id="form-securepedia" onsubmit="confirmSave('form-securepedia', 'Data securepedia akan disimpan')">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="title" class="form-label"><span class="text-danger">*</span> Judul</label>
                        <input class="form-control @error('title') is-invalid @enderror" type="text" id="title"
                            required placeholder="Masukan judul" name="title" value="{{old('title')}}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="type" class="form-label"><span class="text-danger">*</span> Status Kepemilikan</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Pilih Status Kepemilikan</option>
                            <option value="Internal" {{ old('type') == 'Internal' ? 'selected' : '' }}>Internal</option>
                            <option value="External" {{ old('type') == 'External' ? 'selected' : '' }}>External</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="file" class="form-label"><span class="text-danger">*</span> File Pendukung</label>
                        <input class="form-control @error('file') is-invalid @enderror" type="file" id="file"
                            accept=".pdf,.doc,.docx" required name="file">
                        <div class="form-text text-muted">Format: PDF, DOC, DOCX</div>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{route('admin.securepedia.index')}}" class="btn btn-danger">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
