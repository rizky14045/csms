@extends('layout.app')
@section('styles')
<style>
    .accordion-button::after {
        filter: invert(100%);
    }
</style>
@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Audit Bulanan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Audit Bulanan</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{route('user.monthly-audit.store')}}" class="my-4" method="POST">
                    @csrf
                    <div class="col-xl-12">
                        <div class="form-group mb-3">
                            <label class="form-label">Bulan dan Tahun</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <select class="form-select @error('report_month') is-invalid @enderror" id="report_month" name="report_month" required>
                                        <option value="">Pilih Bulan</option>
                                        @foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $monthNumber => $monthName)
                                            <option value="{{ $monthNumber }}" @selected(old('report_month') == $monthNumber)>{{ $monthName }}</option>
                                        @endforeach
                                    </select>
                                    @error('report_month')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select @error('report_year') is-invalid @enderror" id="report_year" name="report_year" required>
                                        <option value="">Pilih Tahun</option>
                                        @for ($year = now()->year; $year >= now()->year - 10; $year--)
                                            <option value="{{ $year }}" @selected(old('report_year') == $year)>{{ $year }}</option>
                                        @endfor
                                    </select>
                                    @error('report_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                  
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{route('user.monthly-audit.index')}}" class="btn btn-danger"> Back</a>
                                    <button class="btn btn-primary" type="submit"> Tambah</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

