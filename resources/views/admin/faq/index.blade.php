@extends('admin.layout.app')
@section('styles')

@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Faq</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('admin.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Faq</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="d-flex justify-content-end pe-3 pt-3">
                <a href="{{route('admin.faq.create')}}" class="btn btn-success">Tambah Data</a>
            </div>
            <div class="card-body">  
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Pertanyaan</th>
                                <th scope="col" class="col-md-9">Jawaban</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Apa itu CSMS ?</td>
                                <td>Contractor Safety Manajemen System (CSMS) PT PLN NP merupakan sistem yang dikelola untuk memastikan bahwa Pihak Ketiga yang bermitra dengan PT PLN NP telah memiliki system manajemen K3L dan telah memenuhi persyaratan K3L yang berlaku di PT PLN NP, serta mampu menerapkan persyaratan K3L dalam pekerjaan yang dilaksanakan.</td>
                                <td class="text-center">
                                    <a href="{{route('admin.faq.edit')}}" class="btn btn-success btn-sm">Edit</a>
                                    <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

