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
                        <a class="nav-link active" data-bs-toggle="tab" href="#navtabs2-security" role="tab">
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
                        <a class="nav-link" href="{{route('user.monthly-audit.form-attribute.index',['monthlyId' => $monthlyId])}}">
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
                        <a class="nav-link" href="{{route('user.monthly-audit.security-program.index',['monthlyId' => $monthlyId])}}">
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
                    <div class="tab-pane active" id="navtabs2-security" role="tabpanel">
                        <div class="cooperation mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title">Data Personil Satuan Pengamanan</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" rowspan="2" class="align-middle">No</th>
                                            <th scope="col" rowspan="2" class="align-middle">Nama Anggota</th>
                                            <th scope="col" rowspan="2" class="align-middle">Unit Kerja</th>
                                            <th scope="col" rowspan="2" class="align-middle">NID</th>
                                            <th scope="col" rowspan="2" class="align-middle">No Registrasi KTA</th>
                                            <th scope="col" rowspan="2" class="align-middle">KTA Berlaku</th>
                                            <th scope="col" rowspan="2" class="align-middle">Jabatan</th>
                                            <th scope="col" rowspan="2" class="align-middle">Tempat, Tanggal Lahir</th>
                                            <th scope="col" rowspan="2" class="align-middle">Umur</th>
                                            <th scope="col" colspan="3">Kualifikasi</th>
                                            <th scope="col" rowspan="2" class="align-middle">Pendidikan Umum Terakhir</th>
                                            <th scope="col" rowspan="2" class="align-middle">Keterangan</th>
                                            <th scope="col" rowspan="2" class="align-middle">File Upload</th>
                                            <th scope="col" rowspan="2" class="align-middle">Action</th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Pratama</th>
                                            <th scope="col">Madya</th>
                                            <th scope="col">Utama</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($forms as $form)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$form->security->name ?? ''}}</td>
                                                <td>{{$form->security->unit_work ?? ''}}</td>
                                                <td>{{$form->security->nid ?? ''}}</td>
                                                <td>{{$form->security->registration_number ?? ''}}</td>
                                                <td>{{$form->security->expired_card_date ?? ''}}</td>
                                                <td>{{$form->security->position ?? ''}}</td>
                                                <td>{{$form->security->birth_place ?? ''}} ,{{$form->security->birth_date}}</td>
                                                <td>{{$form->security->birth_place ?? ''}} ,{{$form->security->birth_date}}</td>
                                                <td>{{$form->security->qualification == 'Pratama' ? 'v' :''}}</td>
                                                <td>{{$form->security->qualification == 'Madya' ? 'v' :''}}</td>
                                                <td>{{$form->security->qualification == 'Utama' ? 'v' :''}}</td>
                                                <td>{{$form->security->last_education ?? ''}}</td>
                                                <td>{{$form->security->note ?? ''}}</td>
                                                <form action="{{route('user.monthly-audit.security-form.upload',['monthlyId' => $monthlyId,'formId' => $form->id])}}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <td>
                                                        <input type="file" class="form-control" name="attachment_file_{{$form->id}}" required accept=".pdf">
                                                        <small>Ekstensi file .pdf</small>
                                                        @if($errors->has('attachment_file_'.$form->id))
                                                            <div class="error text-danger">{{ $errors->first('attachment_file_'.$form->id) }}</div>
                                                        @endif
                                                </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            @if ($form->attachment_file)
                                                                <a href="{{asset('uploads/attachment_file_security_form/'.$form->attachment_file)}}" download="" class="btn btn-info btn-sm">Download</a>
                                                            @endif
                                                            <button class="btn btn-success btn-sm">Upload</button>

                                                        </div>
                                                    </td>
                                                </form>
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

