@extends('user.layout.app')
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
        <h4 class="fs-18 fw-semibold m-0">Marturity</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Marturity</li>
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
                        <div class="d-flex gap-3">
                            <div class="mb-3 col-md-3">
                                <input type="date" class="form-control d-inline" id="exampleFormControlInput1">
                            </div>
                            <div class="button-search">
                                <button type="button" class="btn btn-primary d-inline">Cari</button>
                            </div>
                        </div>
                    </div>
                    <div class="button-add col-md-12">
                        <div class="d-flex justify-content-end pe-3 pt-3 col-md-6">
                            <a href="{{route('user.marturity.create')}}" class="btn btn-success">Tambah Data</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Triwulan</th>
                                <th scope="col">Tanggal Kirim</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($marturities as $marturity)
                                
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$marturity->date}}</td>
                                    <td>{{$marturity->triwulan}}</td>
                                    <td>{{$marturity->send_date}}</td>
                                    <td>
                                        @if ($marturity->send_status == false)
                                            <a href="{{route('user.marturity.show',['marturityId'=>$marturity->id])}}" class="btn btn-info btn-sm">show</a>
                                            <form action="{{route('user.marturity.send',['marturityId'=>$marturity->id])}}" method="post" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" class="btn btn-success btn-sm" onclick="sendItem(this)">Kirim</button>
                                            </form>
                                            <a href="{{route('user.marturity.edit',['marturityId'=>$marturity->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{route('user.marturity.destroy',['marturityId'=>$marturity->id])}}" method="post" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteItem(this)">Hapus</button>
                                            </form>
                                        @else
                                            <a href="{{route('user.marturity.preview',['marturityId'=>$marturity->id])}}" class="btn btn-info btn-sm">show</a>
                                        
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
<script>
   function deleteItem(e){
            // console.log(form);
            Swal.fire({
                title: 'Hapus Data',
                text: "Apakah kamu ingin menghapus data ?",
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

