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
                        <a class="nav-link active" data-bs-toggle="tab" href="#navtabs2-home" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-home-account"></i></span>
                            <span class="d-none d-sm-block">Jumlah Pekerja</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.security-form.index',['monthlyId' => $monthlyId])}}" >
                            <span class="d-block d-sm-none"><i class="mdi mdi-account-outline"></i></span>
                            <span class="d-none d-sm-block">Satuan Pengamanan</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.aght.index',['monthlyId' => $monthlyId])}}" >
                            <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                            <span class="d-none d-sm-block">Data AGHT</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.form-attribute.index',['monthlyId' => $monthlyId])}}" >
                            <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                            <span class="d-none d-sm-block">Atribut Peralatan</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.form-foreign-worker.index',['monthlyId' => $monthlyId])}}" >
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Tenaga Kerja Asing</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.security-program.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Program Keamanan</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.form-vulnerability-internal.index',['monthlyId' => $monthlyId])}}" >
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Kerawanan Internal</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.form-vulnerability-external.index',['monthlyId' => $monthlyId])}}" >
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Kerawanan Eksternal</span>    
                        </a>
                    </li>
                </ul>

                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active" id="navtabs2-home" role="tabpanel">
                        <div class="security mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title fw-bold">Data Penanggung Jawab Keamanan</span>
                                <a href="{{route('user.monthly-audit.responsible-person.create',['monthlyId'=>$monthlyId])}}" class="btn btn-success btn-sm float-right">Tambah Data</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="text-nowrap align-middle" rowspan="3">No</th>
                                            <th scope="col" class="text-nowrap align-middle" rowspan="3">Nama</th>
                                            <th scope="col" class="text-nowrap align-middle" rowspan="3">Jabatan</th>
                                            <th scope="col" class="text-nowrap align-middle" rowspan="3">Unit Kerja</th>
                                            <th scope="col" class="text-nowrap align-middle" colspan="7">Pelatihan Unit Pengamanan</th>
                                            <th scope="col" class="text-nowrap align-middle" rowspan="3">Keterangan</th>
                                            <th scope="col" class="text-nowrap align-middle" rowspan="3">Action</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowrap" colspan="7">Kualifikasi</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowrap">Pelatihan SMP</th>
                                            <th scope="col" class="text-nowrap">Auditor SMP</th>
                                            <th scope="col" class="text-nowrap">Utama</th>
                                            <th scope="col" class="text-nowrap">Investigasi</th>
                                            <th scope="col" class="text-nowrap">Mansrisk</th>
                                            <th scope="col" class="text-nowrap">Stackholder Management</th>
                                            <th scope="col" class="text-nowrap">Pendidikan Terakhir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($persons as $person)      
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$person->name}}</td>
                                                <td>{{$person->position}}</td>
                                                <td>{{$person->work_unit}}</td>
                                                <td>{{$person->training_smp}}</td>
                                                <td>{{$person->auditor_smp}}</td>
                                                <td>{{$person->main}}</td>
                                                <td>{{$person->investigation}}</td>
                                                <td>{{$person->mansrisk}}</td>
                                                <td>{{$person->stackholder_management}}</td>
                                                <td>{{$person->last_education}}</td>
                                                <td>{{$person->note}}</td>
                                                <td>
                                                    <a href="{{route('user.monthly-audit.responsible-person.edit',['monthlyId'=>$monthlyId,'personId' =>$person->id])}}" class="btn btn-sm btn-info">edit</a>
                                                    <form action="{{route('user.monthly-audit.responsible-person.destroy',['monthlyId'=>$monthlyId,'personId' =>$person->id])}}" method="post" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="security-personil mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold">Data Personil Keamanan Eksternal</span>
                                <a href="{{route('user.monthly-audit.security-external.create',['monthlyId'=>$monthlyId])}}" class="btn btn-success btn-sm float-right">Tambah Data</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Instansi</th>
                                            <th scope="col">Satuan Wilayah</th>
                                            <th scope="col">Nomor Surat Perintah</th>
                                            <th scope="col">Keterangan</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($securities as $security)      
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$security->name}}</td>
                                                <td>{{$security->instansi}}</td>
                                                <td>{{$security->regional_unit}}</td>
                                                <td>{{$security->warrant_number}}</td>
                                                <td>{{$security->note}}</td>
                                                <td>
                                                    <a href="{{route('user.monthly-audit.security-external.edit',['monthlyId'=>$monthlyId,'securityId' =>$security->id])}}" class="btn btn-sm btn-info">edit</a>
                                                    <form action="{{route('user.monthly-audit.security-external.destroy',['monthlyId'=>$monthlyId,'securityId' =>$security->id])}}" method="post" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="cooperation mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title fw-bold">Data Perjanjian Kerjasama Eksternal</span>
                                <a href="{{route('user.monthly-audit.agreement-external.create',['monthlyId'=>$monthlyId])}}" class="btn btn-success btn-sm float-right">Tambah Data</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Instansi</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Satuan Wilayah</th>
                                            <th scope="col">Nomor PKT</th>
                                            <th scope="col">Judul PKT</th>
                                            <th scope="col">Masa Berlaku</th>
                                            <th scope="col">Keterangan</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($agreements as $agreement)      
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$agreement->instansi}}</td>
                                                <td>{{$agreement->name}}</td>
                                                <td>{{$agreement->regional_unit}}</td>
                                                <td>{{$agreement->pkt_number}}</td>
                                                <td>{{$agreement->pkt_title}}</td>
                                                <td>{{$agreement->expired_date}}</td>
                                                <td>{{$agreement->note}}</td>
                                                <td>
                                                    <a href="{{route('user.monthly-audit.agreement-external.edit',['monthlyId'=>$monthlyId,'agreementId' =>$agreement->id])}}" class="btn btn-sm btn-info">edit</a>
                                                    <form action="{{route('user.monthly-audit.agreement-external.destroy',['monthlyId'=>$monthlyId,'agreementId' =>$agreement->id])}}" method="post" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- end tab pane -->
                </div>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

