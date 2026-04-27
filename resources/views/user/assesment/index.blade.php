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
                        <form action="{{ route('user.assesment.index') }}">
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
                                <th scope="col">Tanggal Buat</th>
                               <th scope="col">Tanggal Kirim BUJP</th>
                               <th scope="col">Tanggal Kirim Pusat</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assesments as $assesment)
                                
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$assesment->bujp_profile->npwp}}</td>
                                    <td>{{$assesment->vendor->name}} @if($assesment->send_status == 3) <br> <span style="padding: 2px; background-color:red;color:white; border-radius: 4px">Proses Revisi</span> @endif</td>
                                    <td>{{$assesment->contract}}</td>
                                    <td>{{$assesment->year}}</td>
                                    <td>{{$assesment->triwulan}}</td>
                                    <td>{{ \Carbon\Carbon::parse($assesment->created_at)->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($assesment->send_date)->format('d-m-Y') }}</td>
                                    <td>{{ $assesment->send_date_pusat ? \Carbon\Carbon::parse($assesment->send_date_pusat)->format('d-m-Y') : '-' }}</td>
                                    <td>
                                        @if ($assesment->send_status == 1)
                                            @can('send.assesment.bujp.unit')
                                            @if(count($assesment->get_invalid_items_question_by_unit) == 0)
                                            <form action="{{route('user.assesment.send',['assesment'=>$assesment->id])}}" method="post" class="d-inline" id="send-assesment-{{ $assesment->id }}" onsubmit="confirmSave('send-assesment-{{ $assesment->id }}', 'Kirim assesment?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm">Kirim</button>
                                            </form>
                                            @else
                                            <button type="button" style="background-color: gray" class="btn btn-secondary btn-sm" disabled>Kirim</button>
                                            @endif
                                            @endcan
                                            <a href="{{route('user.assesment.show',['assesment'=>$assesment->id])}}" class="btn btn-info btn-sm">Show</a>
                                        @elseif($assesment->send_status >= 2)
                                            <a href="{{route('user.assesment.preview',['assesment'=>$assesment->id])}}" class="btn btn-info btn-sm">Show</a>
                                            <a href="{{route('user.assesment.report',['assesment'=>$assesment->id])}}" class="btn btn-success btn-sm">Report</a>
                                        
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
@section('scripts')
<script>
    function sendItem(e){
            // console.log(form);
            Swal.fire({
                title: 'Kirim Data',
                text: "Data yang sudah dikirim sudah tidak bisa diedit , apakah anda ingin mengirim data?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya !'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(e).parent().submit();
                }
            })
        }
</script>
@endsection


