@extends('layout.app')
@section('styles')

@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Audit Bulanan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Audit Bulanan</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between w-100">
                    <div class="find-data col-md-6">
                        <label for="" class="form-label">Cari Data</label>
                        <form action="" method="GET">
                            <div class="d-flex gap-3">
                                <div class="mb-3 col-md-3">
                                    <input type="month" class="form-control d-inline" id="month" name="month" value="{{ $request['month'] ?? '' }}">
                                </div>
                                <div class="mb-3 col-md-3">
                                    <select name="unit_code" id="unit_code" class="form-control d-inline">
                                        <option value="">-- Pilih Unit --</option>
                                        @foreach($all_units as $unit)
                                            <option value="{{ $unit['unit_code'] }}" 
                                                {{ (isset($request['unit_code']) && $request['unit_code'] == $unit['unit_code']) ? 'selected' : '' }}>
                                                {{ $unit['name'] }} ({{ $unit['unit_code'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="button-search">
                                    <button type="submit" class="btn btn-primary d-inline">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="button-add col-md-12">
                        <div class="d-flex justify-content-end pe-3 pt-3 col-md-6">
                            <a href="{{route('user.monthly-audit.create')}}" class="btn btn-success">Tambah Data</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama Unit</th>
                                <th scope="col">Kode Unit</th>
                                <th scope="col">Bulan</th>
                                <th scope="col">Tanggal Buat</th>
                                <th scope="col">Tanggal Kirim</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($forms as $form)       
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$form->unit->name ?? ''}}</td>
                                    <td>{{$form->detailUnit->unit_code ?? ''}}</td>
                                    <td>{{ \Carbon\Carbon::parse($form->report_date)->format('m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($form->created_at)->format('d-m-Y') }}</td>
                                    <td>{{ $form->send_status == true ? \Carbon\Carbon::parse($form->send_date)->format('d-m-Y') : '-' }}</td>
                                    <td>
                                        <a href="{{route('admin.monthly-audit.show',['monthlyId'=>$form->id])}}" class="btn btn-info btn-sm">Show</a>          
                                        <a href="{{route('export.monthly.all',['monthlyId'=>$form->id])}}" class="btn btn-success btn-sm">Export Excel</a>          
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

