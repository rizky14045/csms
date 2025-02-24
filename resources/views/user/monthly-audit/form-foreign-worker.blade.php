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
                        <a class="nav-link" href="{{route('user.monthly-audit.form-attribute.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                            <span class="d-none d-sm-block">Atribut Peralatan</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
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
                    <div class="tab-pane active" id="navtabs2-tka" role="tabpanel">
                        <div class="cooperation mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title">Data Tenaga Kerja Asing</span>
                                <a href="{{route('user.monthly-audit.form-foreign-worker.create',['monthlyId'=>$monthlyId])}}" class="btn btn-success btn-sm float-right">Tambah Data</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="text-nowarp align-middle" rowspan="4">No</th>
                                            <th scope="col" class="text-nowarp align-middle" rowspan="4">Nama</th>
                                            <th scope="col" class="text-nowarp align-middle" rowspan="4">Kebangsaan</th>
                                            <th scope="col" class="text-nowarp align-middle" rowspan="4">Perusahaan</th>
                                            <th scope="col" class="text-nowarp align-middle" rowspan="4">Jabatan / Keahlian</th>
                                            <th scope="col" class="text-nowarp align-middle" colspan="12">Kategori</th>
                                            <th scope="col" class="text-nowarp align-middle" rowspan="3" colspan="2">Tanggal</th>
                                            <th scope="col" class="text-nowarp align-middle" rowspan="4">Keterangan</th>
                                            <th scope="col" class="text-nowarp align-middle" rowspan="4">Action</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowarp align-middle" colspan="4">Tamu</th>
                                            <th scope="col" class="text-nowarp align-middle" colspan="8">Pekerja</th>  
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowarp align-middle" colspan="2">Paspor</th>
                                            <th scope="col" class="text-nowarp align-middle" colspan="2">Visa</th>  
                                            <th scope="col" class="text-nowarp align-middle" colspan="2">Paspor</th>
                                            <th scope="col" class="text-nowarp align-middle" colspan="2">Vitas</th>
                                            <th scope="col" class="text-nowarp align-middle" colspan="2">Kitas</th>
                                            <th scope="col" class="text-nowarp align-middle" colspan="2">RPTKA</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowarp align-middle">Paspor</th>
                                            <th scope="col" class="text-nowarp align-middle">File Attachment</th>
                                            <th scope="col" class="text-nowarp align-middle">Visa Kunjungan</th>
                                            <th scope="col" class="text-nowarp align-middle">File Attachment</th>
                                            <th scope="col" class="text-nowarp align-middle">Paspor</th>
                                            <th scope="col" class="text-nowarp align-middle">File Attachment</th>
                                            <th scope="col" class="text-nowarp align-middle">Vitas</th>
                                            <th scope="col" class="text-nowarp align-middle">File Attachment</th>
                                            <th scope="col" class="text-nowarp align-middle">Kitas</th>
                                            <th scope="col" class="text-nowarp align-middle">File Attachment</th>
                                            <th scope="col" class="text-nowarp align-middle">RPTKA</th>
                                            <th scope="col" class="text-nowarp align-middle">File Attachment</th>
                                            <th scope="col" class="text-nowarp align-middle">Datang</th>
                                            <th scope="col" class="text-nowarp align-middle">Kembali</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($foreigns as $foreign)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$foreign->name}}</td>
                                                <td>{{$foreign->nationality}}</td>
                                                <td>{{$foreign->company}}</td>
                                                <td>{{$foreign->position}}</td>
                                                @if ($foreign->category == 'Tamu')
                                                    <td>{{$foreign->paspor == 1 ? 'v' :''}}</td>
                                                    <td>
                                                        @if ($foreign->paspor_file)
                                                            <a href="{{asset('uploads/attachment_file_foreign_worker/'.$foreign->paspor_file)}}" download="" class="btn btn-success btn-sm">download</a>
                                                        @endif
                                                    </td>
                                                    <td>{{$foreign->visa == 1 ? 'v' :''}}</td>
                                                    <td>
                                                        @if ($foreign->visa_file)
                                                            <a href="{{asset('uploads/attachment_file_foreign_worker/'.$foreign->visa_file)}}" download="" class="btn btn-success btn-sm">download</a>
                                                        @endif
                                                    </td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                               
                                                @if ($foreign->category == 'Pekerja')
                                                    {{-- paspor --}}
                                                    <td>{{$foreign->paspor == 1 ? 'v' :''}}</td>
                                                    <td>
                                                        @if ($foreign->paspor_file)
                                                            <a href="{{asset('uploads/attachment_file_foreign_worker/'.$foreign->paspor_file)}}" download="" class="btn btn-success btn-sm">download</a>
                                                        @endif
                                                    </td>
                                                    {{-- vitas --}}
                                                    <td>{{$foreign->vitas == 1 ? 'v' :''}}</td>
                                                    <td>
                                                        @if ($foreign->vitas_file)
                                                            <a href="{{asset('uploads/attachment_file_foreign_worker/'.$foreign->vitas_file)}}" download="" class="btn btn-success btn-sm">download</a>
                                                        @endif
                                                    </td>
                                                    {{-- kitas --}}
                                                    <td>{{$foreign->kitas == 1 ? 'v' :''}}</td>
                                                    <td>
                                                        @if ($foreign->kitas_file)
                                                            <a href="{{asset('uploads/attachment_file_foreign_worker/'.$foreign->kitas_file)}}" download="" class="btn btn-success btn-sm">download</a>
                                                        @endif
                                                    </td>
                                                    {{-- rptka --}}
                                                    <td>{{$foreign->rptka == 1 ? 'v' :''}}</td>
                                                    <td>
                                                        @if ($foreign->rptka_file)
                                                            <a href="{{asset('uploads/attachment_file_foreign_worker/'.$foreign->rptka_file)}}" download="" class="btn btn-success btn-sm">download</a>
                                                        @endif
                                                    </td>
  
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                               
                                               
                                                <td>{{$foreign->arrived_date}}</td>
                                                <td>{{$foreign->return_date}}</td>
                                                <td>{{$foreign->note}}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        
                                                        <a href="{{route('user.monthly-audit.form-foreign-worker.edit',['monthlyId'=>$monthlyId,'foreignId' =>$foreign->id])}}" class="btn btn-sm btn-warning">edit</a>
                                                        <form action="{{route('user.monthly-audit.form-foreign-worker.destroy',['monthlyId'=>$monthlyId,'foreignId' =>$foreign->id])}}" method="post" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                        </form>
                                                    </div>
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

