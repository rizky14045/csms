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
        <h4 class="fs-18 fw-semibold m-0">Maturity</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Maturity</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
           
            <div class="card-body">
                <div class="row align-items-end mb-4">
                    <div class="col-md-6">
                        <form action="" method="get">
                            <label class="form-label">Cari Data</label>
                            <div class="d-flex gap-3">

                                <input type="date"
                                    class="form-control"
                                    name="date"
                                    value="{{ request('date') }}"
                                    style="max-width:200px;">

                                <button type="submit" class="btn btn-primary">
                                    Cari
                                </button>

                            </div>
                        </form>
                    </div>

                    <div class="col-md-6 text-end">
                        @can('create.marturity.unit')
                            <a href="{{route('user.marturity.create')}}"
                            class="btn btn-primary">
                            Tambah Data
                            </a>
                        @endcan
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Tahun</th>
                                <th scope="col">Semester</th>
                                <th scope="col">Tanggal Kirim</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($marturities as $marturity)
                                
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $marturity->year }}</td>
                                    <td>{{$marturity->semester}}</td>
                                    <td>{{ $marturity->send_date ? \Carbon\Carbon::parse($marturity->send_date)->format('d-m-Y') : "-" }}</td>
                                    <td>
                                        @if ($marturity->send_status == false)
                                            @can('view.marturity.unit')
                                            <a href="{{route('user.marturity.show',['marturity'=>$marturity->id])}}" class="btn btn-info btn-sm">show</a>
                                            @endcan
                                            @can('send.marturity.unit')
                                            <form action="{{route('user.marturity.send',['marturity'=>$marturity->id])}}" method="post" class="d-inline" id="send-marturity-{{ $marturity->id }}" onsubmit="confirmSave('send-marturity-{{ $marturity->id }}', 'Data marturity akan dikirim')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm">Kirim</button>
                                            </form>
                                            @endcan
                                        @else
                                            @can('view.marturity.unit')
                                            <a href="{{route('user.marturity.preview',['marturity'=>$marturity->id])}}" class="btn btn-info btn-sm">show</a>
                                            @endcan
                                        @endif
                                      
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

