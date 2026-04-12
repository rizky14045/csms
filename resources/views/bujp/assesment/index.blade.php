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
        <h4 class="fs-18 fw-semibold m-0">Assesment</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Assesment</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
           
            <div class="card-body">
                <div class="d-flex justify-content-between w-100">
                    <div class="find-data col-md-6">
                        <form action="{{ route('bujp.assesment.index') }}">
                            
                            <input type="hidden" name="unit" value="{{ request('unit') }}">
                            <label class="form-label">Cari Data</label>
                            <div class="d-flex gap-3">
                                <div class="mb-3 col-md-3">
                                    <input type="date" 
                                        class="form-control" 
                                        name="date" 
                                        value="{{ request('date', '') }}">
                                </div>
                                <div class="button-search">
                                    <button type="submit" class="btn btn-primary">
                                        Cari
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="button-add col-md-12">
                        @can('create.assesment.bujp')
                        <div class="d-flex justify-content-end pe-3 pt-3 col-md-6">
                            <a href="{{ route('bujp.assesment.create', ['unit' => request('unit')]) }}" class="btn btn-primary">
                                Tambah Data
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">NPWP</th>
                                <th scope="col">Nama Perusahaan</th>
                                <th scope="col">Nomor Kontrak</th>
                                <th scope="col">Tahun</th>
                                <th scope="col">Triwulan</th>
                               <th scope="col">Tanggal Kirim</th>
                                @canany(['edit.assesment.bujp', 'send.assesment.bujp', 'delete.assesment.bujp', 'view.assesment.bujp'])
                                <th scope="col">Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assesments as $assesment)
                                
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$assesment->bujp_profile->npwp}}</td>
                                    <td>{{$assesment->vendor->name}}</td>
                                    <td>{{$assesment->contract}}</td>
                                    <td>{{$assesment->year}}</td>
                                    <td>{{$assesment->triwulan}}</td>
                                    <td>{{ \Carbon\Carbon::parse($assesment->send_date)->format('d-m-Y') }}</td>
                                    @canany(['edit.assesment.bujp', 'send.assesment.bujp', 'delete.assesment.bujp', 'view.assesment.bujp'])
                                    <td>
                                        
                                        @if ($assesment->send_status == 0)
                                            @can('edit.assesment.bujp')
                                            <a href="{{route('bujp.assesment.show',['assesment'=>$assesment->id, 'unit' => request()->query('unit')])}}" class="btn btn-info btn-sm">Show</a>
                                            @endcan
                                            @can('send.assesment.bujp')
                                            @if(count($assesment->get_invalid_items_question_by_bujp) == 0)
                                            <form action="{{route('bujp.assesment.send',['assesment'=>$assesment->id, 'unit' => request()->query('unit')])}}" method="post" class="d-inline" id="send-assesment-{{ $assesment->id }}" onsubmit="confirmSave('send-assesment-{{ $assesment->id }}', 'Kirim assesment?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm">Kirim</button>
                                            </form>
                                            @else
                                            <button type="button" style="background-color: gray" class="btn btn-secondary btn-sm" disabled>Kirim</button>
                                            @endif
                                            @endcan
                                            @can('delete.assesment.bujp')
                                            <form action="{{route('bujp.assesment.destroy',['assesment'=>$assesment->id, 'unit' => request()->query('unit')])}}" method="post" class="d-inline" id="delete-assesment-{{ $assesment->id }}" onsubmit="confirmSave('delete-assesment-{{ $assesment->id }}', 'Hapus assesment?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                            @endcan                                            
                                        @else
                                            @can('view.assesment.bujp')
                                            <a href="{{route('bujp.assesment.preview',['assesment'=>$assesment->id, 'unit' => request()->query('unit')])}}" class="btn btn-info btn-sm">Show</a>
                                            <a href="{{route('bujp.assesment.report',['assesment'=>$assesment->id, 'unit' => request()->query('unit')])}}" class="btn btn-success btn-sm">Report</a>
                                            @endcan
                                        @endif
                                        
                                    </td>
                                    @endcanany
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