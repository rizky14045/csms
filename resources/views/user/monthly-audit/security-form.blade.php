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
        <h4 class="fs-18 fw-semibold m-0">Audit Bulanan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
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
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.penyerapan-anggaran.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                            <span class="d-none d-sm-block">Penyerapan Anggaran</span>    
                        </a>
                    </li>
                </ul>

                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active" id="navtabs2-security" role="tabpanel">
                        <div class="cooperation mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title fw-bold">Data Personil Satuan Pengamanan</span>
                                @can('create.security.unit')
                                <a href="{{ route('user.security.create') }}?monthly_id={{ $monthlyId }}" class="btn btn-success btn-sm">Tambah</a>
                                @endcan
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle"
                                    style="table-layout: fixed; width: 100%; min-width: 1600px;">
                                    <colgroup>
                                        <col style="width: 45px;">
                                        <col style="width: 140px;">
                                        <col style="width: 110px;">
                                        <col style="width: 100px;">
                                        <col style="width: 120px;">
                                        <col style="width: 100px;">
                                        <col style="width: 90px;">
                                        <col style="width: 150px;">
                                        <col style="width: 60px;">
                                        <col style="width: 70px;">
                                        <col style="width: 70px;">
                                        <col style="width: 70px;">
                                        <col style="width: 130px;">
                                        <col style="width: 100px;">
                                        <col style="width: 130px;">
                                    </colgroup>
                                    <thead class="table-light">
                                        <tr>
                                            <th rowspan="2" class="align-middle">No</th>
                                            <th rowspan="2" class="align-middle">Nama Anggota</th>
                                            <th rowspan="2" class="align-middle">Unit Kerja</th>
                                            <th rowspan="2" class="align-middle">NID</th>
                                            <th rowspan="2" class="align-middle">No Registrasi KTA</th>
                                            <th rowspan="2" class="align-middle">KTA Berlaku</th>
                                            <th rowspan="2" class="align-middle">Jabatan</th>
                                            <th rowspan="2" class="align-middle">Tempat, Tanggal Lahir</th>
                                            <th rowspan="2" class="align-middle">Umur</th>
                                            <th colspan="3" class="align-middle">Kualifikasi</th>
                                            <th rowspan="2" class="align-middle">Pendidikan Terakhir</th>
                                            <th rowspan="2" class="align-middle">Keterangan</th>
                                            <th rowspan="2" class="align-middle">File KTA</th>
                                        </tr>
                                        <tr>
                                            <th>Pratama</th>
                                            <th>Madya</th>
                                            <th>Utama</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($forms as $form)
                                        @php
                                            $birthDate  = $form->security->birth_date ?? null;
                                            $birthFormatted = $birthDate ? \Carbon\Carbon::parse($birthDate)->format('d-m-Y') : '-';
                                            $age        = $birthDate ? \Carbon\Carbon::parse($birthDate)->diffInYears($form->created_at) : '-';
                                        @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-start">{{ $form->security->name ?? '' }}</td>
                                                <td>{{ $form->security->unit_work ?? '' }}</td>
                                                <td>{{ $form->security->nid ?? '' }}</td>
                                                <td>{{ $form->security->registration_number ?? '' }}</td>
                                                <td>{{ $form->security->expired_card_date ? \Carbon\Carbon::parse($form->security->expired_card_date)->format('d-m-Y') : '' }}</td>
                                                <td>{{ $form->security->position ?? '' }}</td>
                                                <td>{{ $form->security->birth_place ?? '' }}, {{ $birthFormatted }}</td>
                                                <td>{{ $age }}</td>
                                                <td>{{ $form->security->qualification == 'Pratama' ? '✓' : '' }}</td>
                                                <td>{{ $form->security->qualification == 'Madya' ? '✓' : '' }}</td>
                                                <td>{{ $form->security->qualification == 'Utama' ? '✓' : '' }}</td>
                                                <td>{{ $form->security->last_education ?? '' }}</td>
                                                <td>{{ $form->security->note ?? '' }}</td>
                                                <td>
                                                    @if($form->security->kta_file ?? null)
                                                        <a href="{{ asset('uploads/kta_files/' . $form->security->kta_file) }}" target="_blank" class="btn btn-info btn-sm">Lihat KTA</a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
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

