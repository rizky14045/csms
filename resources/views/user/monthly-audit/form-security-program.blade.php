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
        <h4 class="fs-18 fw-semibold m-0">Audit Bulanan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Detail Audit Bulanan</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-3">Detail Audit Bulanan</h5>
                <a href="{{route('user.monthly-audit.index')}}" class="btn btn-danger"> Back</a>
            </div><!-- end card header -->

            <div class="card-body">
                <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.form-formulir.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-home-account"></i></span>
                            <span class="d-none d-sm-block">Form Formulir</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.worker-sum.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-home-account"></i></span>
                            <span class="d-none d-sm-block">Jumlah Pekerja</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.security-form.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-account-outline"></i></span>
                            <span class="d-none d-sm-block">Satuan Pengamanan</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.aght.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                            <span class="d-none d-sm-block">Data AGHT</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="{{route('user.monthly-audit.form-attribute.index',['monthlyId' => $monthlyId])}}" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                            <span class="d-none d-sm-block">Atribut Peralatan</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.form-foreign-worker.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Tenaga Kerja Asing</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Program Keamanan</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.form-vulnerability-internal.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Kerawanan Internal</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.form-vulnerability-external.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Kerawanan Eksternal</span>    
                        </a>
                    </li>
                </ul>
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active" id="navtabs2-atribut" role="tabpanel">
                        <div class="pb-3 d-flex justify-content-between">
                            <span class="title">Program Keamanan</span>
                            <a href="{{route('user.monthly-audit.security-program.visual',['monthlyId' => $monthlyId])}}" class="btn btn-success btn-sm">Visual</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Program</th>
                                        <th scope="col">Deskripsi</th>
                                        <th scope="col">Tahun</th>
                                        <th scope="col">Jumlah Program</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($programs as $program)    
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$program->securityProgram->program_name}}</td>
                                            <td>{{$program->securityProgram->description}}</td>
                                            <td>{{$program->securityProgram->year}}</td>
                                            <td>{{$program->programs->count()}}</td>
                                            <td class="text-center">
                                                <a href="{{route('user.monthly-audit.realization-program.index',['monthlyId' => $monthlyId,'programId'=>$program->id])}}" class="btn btn-info btn-sm">Update Realisasi</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                            
                    </div><!-- end tab pane -->
                </div>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

