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
            <li class="breadcrumb-item active">Detail Audit Bulanan</li>
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
                        <a class="nav-link active" data-bs-toggle="tab" href="#navtabs2-form" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-home-account"></i></span>
                            <span class="d-none d-sm-block">Form Formulir</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#navtabs2-home" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-home-account"></i></span>
                            <span class="d-none d-sm-block">Jumlah Pekerja</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#navtabs2-security" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-account-outline"></i></span>
                            <span class="d-none d-sm-block">Satuan Pengamanan</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#navtabs2-aght" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                            <span class="d-none d-sm-block">Data AGHT</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#navtabs2-atribut" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                            <span class="d-none d-sm-block">Atribut Peralatan</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#navtabs2-tka" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Tenaga Kerja Asing</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#navtabs2-security-program" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Program Keamanan</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#navtabs2-kerawanan-internal" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Kerawanan Internal</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#navtabs2-kerawanan-external" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Kerawanan Eksternal</span>    
                        </a>
                    </li>
                </ul>

                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active" id="navtabs2-form" role="tabpanel">
                        <div class="security mb-3">
                            <div class="security mb-3">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="title fw-bold">Data Penanggung Jawab Keamanan</span>
                                </div>
                                <div class="container my-4">
                                    <h5>BULAN : {{$monthlyReport->report_date}}<h5>
                                    <!-- Section 1 -->
                                    <div class="mb-3">
                                        <h6 class="fw-bold">1. JUMLAH PEGAWAI PT PLN NUSANTARA POWER</h6>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered text-center align-middle">
                                                        <thead class="table-light">
                                                        <tr>
                                                        <th>Personil</th>
                                                        <th>Total Orang</th>
                                                        <th>Pria</th>
                                                        <th>Wanita</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                        $total = $employee->employee_man + $employee->employee_woman + $employee->student_man + $employee->student_woman;
                                                        $totalEmployee = $employee->employee_man + $employee->employee_woman;
                                                        $totalStudent = $employee->student_man + $employee->student_woman;
                                                        $totalMan = $employee->employee_man + $employee->student_man;
                                                        $totalWoman =$employee->employee_woman  + $employee->student_woman;
                                                    @endphp
                                                        <tr>
                                                        <td>1.1 Pegawai Tetap</td>
                                                        <td>{{number_format($totalEmployee)}} Orang</td>
                                                        <td>{{number_format($employee->employee_man)}} Orang</td>
                                                        <td>{{number_format($employee->employee_man)}} Orang</td>
                                                        </tr>
                                                        <tr>
                                                        <td>1.2 Siswa OJT</td>
                                                        <td>{{number_format($totalStudent)}} Orang</td>
                                                        <td>{{number_format($employee->student_man)}} Orang</td>
                                                        <td>{{number_format($employee->student_woman)}}  Orang</td>
                                                        </tr>
                                                        <tr class="fw-bold">

                                                        <td>Jumlah</td>
                                                        <td>{{number_format($total)}} Orang</td>
                                                        <td>{{number_format($totalMan)}} Orang</td>
                                                        <td>{{number_format($totalWoman)}} Orang</td>
                                                        </tr>
                                                    </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <!-- Section 2 -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between pb-3">
                                            <h6 class="fw-bold">2. JUMLAH KARYAWAN OUTSOURCING</h6>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-center align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                    <th>Personil</th>
                                                    <th>Total Orang</th>
                                                    <th>Pria</th>
                                                    <th>Wanita</th>
                                                    </tr>
                                                </thead>
                                            <tbody class="outsource-table">
                                                @foreach ($outsources as $outsource)
                                                <tr class="outsource-row">
                                                    <td>{{$outsource->name}}</td>
                                                    <td>{{$outsource->total}} Orang</td>
                                                    <td>{{$outsource->man}} Orang</td>
                                                    <td>{{$outsource->woman}} Orang</td>
                                                </tr>
                                                @endforeach

                                                <tr class="fw-bold sum-outsource">
                                                <td>Jumlah</td>
                                                <td>{{number_format($outsources->sum('total'))}} Orang</td>
                                                <td>{{number_format($outsources->sum('man'))}} Orang</td>
                                                <td colspan="2">{{number_format($outsources->sum('woman'))}} Orang</td>
                                                </tr>
                                            </tbody>
                                            </table>
                                        </div>
                                    </div>
                                  
                                    <!-- Section 3 -->
                                    <div class="mb-3">
                                      <h6 class="fw-bold">3. JUMLAH PERSONIL SATPAM PT</h6>
                                      <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light">
                                            <tr>
                                              <th>Personil</th>
                                              <th>Total Orang</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>3.1 Komandan Regu</td>
                                            <td>{{number_format($securityKomandan)}} Orang</td>
                                        </tr>
                                        <tr>
                                            <td>3.2 Anggota Satuan</td>
                                            <td>{{number_format($securityAnggota)}} Orang</td>
                                        </tr>
                                        <tr>
                                            <td>3.3 Chief Satpam</td>
                                            <td>{{number_format($securityChief)}} Orang</td>
                                        </tr>
                                        <tr class="fw-bold">
                                            <td>Jumlah</td>
                                            <td>{{number_format($security)}} Orang</td>
                                        </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  
                                    <!-- Section 4 -->
                                    <div class="mb-3">
                                      <h6 class="fw-bold">4. JASA PENGAMANAN</h6>
                                      <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light">
                                            <tr>
                                              <th>Personil</th>
                                              <th>Total Orang</th>
                                            </tr>
                                          </thead>
                                        <tbody>
                                        <tr>
                                            <td>4.1 Polri</td>
                                            <td>{{number_format($securityPolri)}} Orang</td>
                                        </tr>
                                        <tr>
                                            <td>4.2 TNI</td>
                                            <td>{{number_format($securityTNI)}} Orang</td>
                                        </tr>
                                        <tr class="fw-bold">
                                            <td>Jumlah</td>
                                            <td>{{number_format($securityExternal)}} Orang</td>
                                        </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  
                                    <!-- Section 5 -->
                                    <div class="mb-3">
                                      <h6 class="fw-bold">5. JUMLAH TENAGA KERJA ASING</h6>
                                      <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light">
                                            <tr>
                                              <th>Personil</th>
                                              <th>Total Orang</th>
                                            </tr>
                                          </thead>
                                        <tbody>
                                        <tr>
                                            <td>5.1 Tenaga Ahli</td>
                                            <td>{{number_format($foreignAhli)}} Orang</td>
                                        </tr>
                                        <tr>
                                            <td>5.2 Staff</td>
                                            <td>{{number_format($foreignStaff)}} Orang</td>
                                        </tr>
                                        <tr class="fw-bold">
                                            <td>Jumlah</td>
                                            <td>{{number_format($foreign)}} Orang</td>
                                        </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  
                                    <!-- Section 6 -->
                                    <div class="mb-3">
                                      <h6 class="fw-bold">6. GANGGUAN YANG TERJADI</h6>
                                      <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light">
                                            <tr>
                                              <th>Jenis Gangguan</th>
                                              <th>Total Orang</th>
                                            </tr>
                                          </thead>
                                        <tbody>
                                          <tr><td>6.1 Bersifat Kriminal</td><td>0 Kali</td></tr>
                                          <tr><td>6.2 Bersifat Politis</td><td>0 Kali</td></tr>
                                          <tr><td>6.3 Kebakaran</td><td>0 Kali</td></tr>
                                          <tr><td>6.4 Bencana Alam</td><td>0 Kali</td></tr>
                                          <tr><td>6.5 Lain-lain</td><td>0 Kali</td></tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  
                                    <!-- Final Total -->
                                    <div class="fw-bold">
                                      <p>JUMLAH TOTAL (1+2+3+4): <span>0 Orang</span> (Pria: <span>0 Orang</span>) (Wanita: <span>0 Orang</span>)</p>
                                    </div>
                                  </div>
    
                            </div>

                        </div>
                    </div><!-- end tab pane -->
                    <div class="tab-pane" id="navtabs2-home" role="tabpanel">
                        <div class="security mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title fw-bold">Data Penanggung Jawab Keamanan</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="text-nowrap" rowspan="3">No</th>
                                            <th scope="col" class="text-nowrap" rowspan="3">Nama</th>
                                            <th scope="col" class="text-nowrap" rowspan="3">Jabatan</th>
                                            <th scope="col" class="text-nowrap" rowspan="3">Unit Kerja</th>
                                            <th scope="col" class="text-nowrap" colspan="7">Pelatihan Unit Pengamanan</th>
                                            <th scope="col" class="text-nowrap" rowspan="3">Keterangan</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowrap" colspan="7">Kualifikasi</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowrap">Pelatihan SMP</th>
                                            <th scope="col" class="text-nowrap">Auditor SMP</th>
                                            <th scope="col" class="text-nowrap">Utama</th>
                                            <th scope="col" class="text-nowrap">Investigasi</th>
                                            <th scope="col" class="text-nowrap">Mansirik</th>
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
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="security-personil mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold">Data Personil Keamanan Eksternal</span>
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
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="cooperation mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title fw-bold">Data Perjanjian Kerjasama Eksternal</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Instansi</th>
                                            <th scope="col">Nama / Satuan Wilayah</th>
                                            <th scope="col">Satuan Wilayah</th>
                                            <th scope="col">Nomor PKT</th>
                                            <th scope="col">Judul PKT</th>
                                            <th scope="col">Masa Berlaku</th>
                                            <th scope="col">Keterangan</th>
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
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- end tab pane -->

                    <div class="tab-pane" id="navtabs2-security" role="tabpanel">
                        <div class="cooperation mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title">Data Personil Satuan Pengamanan</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" rowspan="2">No</th>
                                            <th scope="col" rowspan="2">Nama Anggota</th>
                                            <th scope="col" rowspan="2">Unit Kerja</th>
                                            <th scope="col" rowspan="2">NID</th>
                                            <th scope="col" rowspan="2">No Registrasi KTA</th>
                                            <th scope="col" rowspan="2">KTA Berlaku</th>
                                            <th scope="col" rowspan="2">Jabatan</th>
                                            <th scope="col" rowspan="2">Tempat, Tanggal Lahir</th>
                                            <th scope="col" rowspan="2">Umur</th>
                                            <th scope="col" colspan="3">Kualifikasi</th>
                                            <th scope="col" rowspan="2">Pendidikan Umum Terakhir</th>
                                            <th scope="col" rowspan="2">Keterangan</th>
                                            <th scope="col" rowspan="2">Action</th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Pratama</th>
                                            <th scope="col">Madya</th>
                                            <th scope="col">Utama</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($securityForms as $form)
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
                                                <td>
                                                    @if ($form->attachment_file)
                                                        <a href="{{asset('uploads/attachment_file_security_form/'.$form->attachment_file)}}" download="" class="btn btn-info btn-sm">Download</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- end tab pane -->
                    <div class="tab-pane" id="navtabs2-aght" role="tabpanel">
                        <div class="cooperation mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title">Data AGHT</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" rowspan="2">No</th>
                                            <th scope="col" rowspan="2">Uraian Kegiatan</th>
                                            <th scope="col" colspan="3">Waktu Kejadian Perkara</th>
                                            <th scope="col" rowspan="2">Kerugian Akibat Yang Ditimbulkan</th>
                                            <th scope="col" rowspan="2">Tindakan Pengamanan Setelah Kejadian</th>
                                            <th scope="col" rowspan="2">Aparat Yang Dihubungi</th>
                                            <th scope="col" rowspan="2">Keterangan</th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Tanggal</th>
                                            <th scope="col">Pukul</th>
                                            <th scope="col">Lokasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($aghts as $aght)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$aght->activity}}</td>
                                                <td>{{$aght->incident_date}}</td>
                                                <td>{{$aght->incident_time}}</td>
                                                <td>{{$aght->incident_location}}</td>
                                                <td>{{$aght->loss}}</td>
                                                <td>{{$aght->after_incident}}</td>
                                                <td>{{$aght->officer_contacted}}</td>
                                                <td>{{$aght->note}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- end tab pane -->
                    <div class="tab-pane" id="navtabs2-atribut" role="tabpanel">
                        <div class="atribut mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title fw-bold">Atribut & Peralatan</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="text-nowrap" rowspan="2">No</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Uraian</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Status Kepemilikan</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Satuan</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Jumlah Standar Kontrak</th>
                                            <th scope="col" class="text-nowrap" colspan="2">Kondisi</th>
                                            <th scope="col" class="text-nowrap" colspan="2">Masa Berlaku</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Keterangan</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowrap">Ada</th>
                                            <th scope="col" class="text-nowrap">Tidak</th>
                                            <th scope="col" class="text-nowrap">Operasi / Berlaku</th>
                                            <th scope="col" class="text-nowrap">Rusak / Kadaluarsa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($attributes as $attribute)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$attribute->name}}</td>
                                                <td>{{$attribute->status_ownership}}</td>
                                                <td>{{$attribute->unit}}</td>
                                                <td>{{$attribute->standard_contract}}</td>
                                                <td>
                                                    <input type="hidden" value="{{$attribute->id}}" name="attribute[]">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" disabled id="attribute_radioYes{{$loop->iteration}}" {{$attribute->condition == 1 ? 'checked' : ''}}>
                                                        <label class="form-check-label" for="attribute_radioYes{{$loop->iteration}}">
                                                        Ya
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" disabled id="attribute_radiono{{$loop->iteration}}" {{$attribute->condition == 0 ? 'checked' : ''}}>
                                                        <label class="form-check-label" for="attribute_radiono{{$loop->iteration}}">
                                                            Tidak
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input ms-3" type="radio" disabled id="attribute_checkyes{{$loop->iteration}}" {{$attribute->status_item == 1 ? 'checked' : ''}}>
                                                        <label class="form-check-label" for="attribute_checkyes{{$loop->iteration}}">
                                                        Ya
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input ms-3" type="radio" disabled id="attribute_checkno{{$loop->iteration}}" {{$attribute->status_item == 0 ? 'checked' : ''}}>
                                                        <label class="form-check-label" for="attribute_checkno{{$loop->iteration}}">
                                                            Tidak
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>{{$attribute->note}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="administrasi mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title fw-bold">Administrasi</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="text-nowrap" rowspan="2">No</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Uraian</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Status Kepemilikan</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Satuan</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Jumlah Standar Kontrak</th>
                                            <th scope="col" class="text-nowrap" colspan="2">Kondisi</th>
                                            <th scope="col" class="text-nowrap" colspan="2">Masa Berlaku</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Keterangan</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Action</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowrap">Ada</th>
                                            <th scope="col" class="text-nowrap">Tidak</th>
                                            <th scope="col" class="text-nowrap">Operasi / Berlaku</th>
                                            <th scope="col" class="text-nowrap">Rusak / Kadaluarsa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($administrations as $administration)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$administration->name}}</td>
                                                <td>{{$administration->status_ownership}}</td>
                                                <td>{{$administration->unit}}</td>
                                                <td>{{$administration->standard_contract}}</td>
                                                <td>
                                                    <input type="hidden" value="{{$administration->id}}" name="administration[]">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" disabled id="administration_radioYes{{$loop->iteration}}" {{$administration->condition == 1 ? 'checked' : ''}}>
                                                        <label class="form-check-label" for="administration_radioYes{{$loop->iteration}}">
                                                        Ya
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" disabled id="administration_radiono{{$loop->iteration}}" {{$administration->condition == 0 ? 'checked' : ''}}>
                                                        <label class="form-check-label" for="administration_radiono{{$loop->iteration}}">
                                                            Tidak
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input ms-3" type="radio" disabled id="administration_checkyes{{$loop->iteration}}" {{$administration->status_item == 1 ? 'checked' : ''}}>
                                                        <label class="form-check-label" for="administration_checkyes{{$loop->iteration}}">
                                                        Ya
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input ms-3" type="radio" disabled id="administration_checkno{{$loop->iteration}}" {{$administration->status_item == 0 ? 'checked' : ''}}>
                                                        <label class="form-check-label" for="administration_checkno{{$loop->iteration}}">
                                                            Tidak
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{$administration->note}}
                                                </td>
                                                <td>
                                                    @if ($administration->attachment_file)
                                                        <a href="{{asset('uploads/attachment_file_form_attribute/'.$administration->attachment_file)}}" class="btn btn-sm btn-info" download="">Download</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="sarana mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title fw-bold">Sarana & Prasarana</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="text-nowrap" rowspan="2">No</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Uraian</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Status Kepemilikan</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Satuan</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Jumlah Standar Kontrak</th>
                                            <th scope="col" class="text-nowrap" colspan="2">Kondisi</th>
                                            <th scope="col" class="text-nowrap" colspan="2">Masa Berlaku</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Keterangan</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowrap">Ada</th>
                                            <th scope="col" class="text-nowrap">Tidak</th>
                                            <th scope="col" class="text-nowrap">Operasi / Berlaku</th>
                                            <th scope="col" class="text-nowrap">Rusak / Kadaluarsa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($saranas as $sarana)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$sarana->name}}</td>
                                            <td>{{$sarana->status_ownership}}</td>
                                            <td>{{$sarana->unit}}</td>
                                            <td>{{$sarana->standard_contract}}</td>
                                            <td>
                                                <input type="hidden" value="{{$sarana->id}}" name="sarana[]">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" disabled id="sarana_radioYes{{$loop->iteration}}" {{$sarana->condition == 1 ? 'checked' : ''}}>
                                                    <label class="form-check-label" for="sarana_radioYes{{$loop->iteration}}">
                                                    Ya
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" disabled id="sarana_radiono{{$loop->iteration}}" {{$sarana->condition == 0 ? 'checked' : ''}}>
                                                    <label class="form-check-label" for="sarana_radiono{{$loop->iteration}}">
                                                        Tidak
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input ms-3" type="radio" disabled id="sarana_checkyes{{$loop->iteration}}" {{$sarana->status_item == 1 ? 'checked' : ''}}>
                                                    <label class="form-check-label" for="sarana_checkyes{{$loop->iteration}}">
                                                    Ya
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input ms-3" type="radio" disabled id="sarana_checkno{{$loop->iteration}}" {{$sarana->status_item == 0 ? 'checked' : ''}}>
                                                    <label class="form-check-label" for="sarana_checkno{{$loop->iteration}}">
                                                        Tidak
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                               {{$sarana->note}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- end tab pane -->
                    <div class="tab-pane" id="navtabs2-tka" role="tabpanel">
                        <div class="cooperation mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title">Data Tenaga Kerja Asing</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="text-nowarp" rowspan="3">No</th>
                                            <th scope="col" class="text-nowarp" rowspan="3">Nama</th>
                                            <th scope="col" class="text-nowarp" rowspan="3">Kebangsaan</th>
                                            <th scope="col" class="text-nowarp" rowspan="3">Perusahaan</th>
                                            <th scope="col" class="text-nowarp" rowspan="3">Jabatan / Keahlian</th>
                                            <th scope="col" class="text-nowarp" colspan="6">Kategori</th>
                                            <th scope="col" class="text-nowarp" rowspan="2" colspan="2">Tanggal</th>
                                            <th scope="col" class="text-nowarp" rowspan="3">Keterangan</th>
                                            <th scope="col" class="text-nowarp" rowspan="3">Action</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowarp" colspan="2">Tamu</th>
                                            <th scope="col" class="text-nowarp" colspan="4">Pekerja</th>  
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowarp">Paspor</th>
                                            <th scope="col" class="text-nowarp">Visa Kunjungan</th>
                                            <th scope="col" class="text-nowarp">Paspor</th>
                                            <th scope="col" class="text-nowarp">Vitas</th>
                                            <th scope="col" class="text-nowarp">Kitas</th>
                                            <th scope="col" class="text-nowarp">RPTKA</th>
                                            <th scope="col" class="text-nowarp">Datang</th>
                                            <th scope="col" class="text-nowarp">Kembali</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($foreignWorkers as $foreign)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$foreign->name}}</td>
                                            <td>{{$foreign->nationality}}</td>
                                            <td>{{$foreign->company}}</td>
                                            <td>{{$foreign->position}}</td>
                                            @if ($foreign->category == 'Tamu')
                                                <td>{{$foreign->paspor == 1 ? 'v' :''}}</td>
                                                <td>{{$foreign->visa == 1 ? 'v' :''}}</td>
                                            @else
                                                <td></td>
                                                <td></td>
                                            @endif
                                           
                                            @if ($foreign->category == 'Pekerja')
                                                <td>{{$foreign->paspor == 1 ? 'v' :''}}</td>
                                                <td>{{$foreign->vitas == 1 ? 'v' :''}}</td>
                                                <td>{{$foreign->kitas == 1 ? 'v' :''}}</td>
                                                <td>{{$foreign->rptka == 1 ? 'v' :''}}</td>
                                            @else
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            @endif
                                           
                                           
                                            <td>{{$foreign->arrived_date}}</td>
                                            <td>{{$foreign->return_date}}</td>
                                            <td>{{$foreign->note}}</td>
                                            <td>
                                                @if ($foreign->attachment_file)
                                                    <a href="{{asset('uploads/attachment_file_foreign_worker/'.$foreign->attachment_file)}}" download="" class="btn btn-success btn-sm">download</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- end tab pane -->
                    <div class="tab-pane" id="navtabs2-security-program" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered">
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
                                                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#accordionRow{{$loop->iteration}}" aria-expanded="false" aria-controls="accordionRow1">
                                                    Lihat TimeLine
                                                  </button>
                                            </td>
                                        </tr>
                                        <tr id="accordionRow{{$loop->iteration}}" class="collapse accordion-content">
                                            <td colspan="6">
                                                <table class="table table-bordered text-center">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th colspan="53">{{$program->securityProgram->program_name}}</th>
                                                        </tr>
                                                        <tr>
                                                            <th rowspan="2" class="text-center align-middle">No</th>
                                                            <th rowspan="2" class="text-center align-middle" colspan="2">Program</th>
                                                            @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $month)
                                                                <th colspan="4" class="text-center">{{ $month }}</th>
                                                            @endforeach
                                                        </tr>
                                                        <tr>
                                                            @foreach(range(1, 12) as $month)
                                                                @foreach(range(1, 4) as $week)
                                                                    <th class="text-center">{{ $week }}</th>
                                                                @endforeach
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($program->programs as $index => $program)
                                                            <tr>
                                                                <td class="text-center align-middle" rowspan="2">{{ $index + 1 }}</td>
                                                                <td class="text-nowrap my-auto text-center align-middle" rowspan="2">{{ $program->mainProgram->program_name }}</td>
                                                                <td>Planning</td>
                                                                @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $monthIndex => $month)
                                                                    @for($week = 1; $week <= 4; $week++)
                                                                        @php
                                                                            // Hitung index bulan awal dan akhir
                                                                            $startMonthIndex = array_search($program->mainProgram->start_month, ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']);
                                                                            $endMonthIndex = array_search($program->mainProgram->end_month, ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']);
                                                                            
                                                                            $isActive = false;
                                                
                                                                            // Logika untuk menentukan sel aktif
                                                                            if ($monthIndex > $startMonthIndex && $monthIndex < $endMonthIndex) {
                                                                                $isActive = true; // Bulan berada di antara start_month dan end_month
                                                                            } elseif ($monthIndex == $startMonthIndex && $monthIndex == $endMonthIndex) {
                                                                                // Bulan awal dan akhir sama
                                                                                $isActive = ($week >= $program->mainProgram->start_week && $week <= $program->mainProgram->end_week);
                                                                            } elseif ($monthIndex == $startMonthIndex) {
                                                                                // Bulan adalah bulan awal
                                                                                $isActive = ($week >= $program->mainProgram->start_week);
                                                                            } elseif ($monthIndex == $endMonthIndex) {
                                                                                // Bulan adalah bulan akhir
                                                                                $isActive = ($week <= $program->mainProgram->end_week);
                                                                            }
                                                                        @endphp
                                                                        <td class="{{ $isActive ? 'bg-danger text-white' : '' }}"></td>
                                                                    @endfor
                                                                @endforeach
                                                            </tr>
                                                            <tr>
                                                                <td>Realisasi</td>
                                                                @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $monthIndex => $month)
                                                                    @for($week = 1; $week <= 4; $week++)
                                                                        @php
                                                                            // Hitung index bulan awal dan akhir
                                                                            $startMonthIndex = array_search($program->start_month, ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']);
                                                                            $endMonthIndex = array_search($program->end_month, ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']);
                                                                            
                                                                            $isActive = false;
                                                
                                                                            // Logika untuk menentukan sel aktif
                                                                            if ($monthIndex > $startMonthIndex && $monthIndex < $endMonthIndex) {
                                                                                $isActive = true; // Bulan berada di antara start_month dan end_month
                                                                            } elseif ($monthIndex == $startMonthIndex && $monthIndex == $endMonthIndex) {
                                                                                // Bulan awal dan akhir sama
                                                                                $isActive = ($week >= $program->start_week && $week <= $program->end_week);
                                                                            } elseif ($monthIndex == $startMonthIndex) {
                                                                                // Bulan adalah bulan awal
                                                                                $isActive = ($week >= $program->start_week);
                                                                            } elseif ($monthIndex == $endMonthIndex) {
                                                                                // Bulan adalah bulan akhir
                                                                                $isActive = ($week <= $program->end_week);
                                                                            }
                                                                        @endphp
                                                                        <td class="{{ $isActive ? 'bg-info text-white' : '' }}"></td>
                                                                    @endfor
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>  
                                            </td>
                                          </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div><!-- end tab pane -->
                    <div class="tab-pane" id="navtabs2-kerawanan-internal" role="tabpanel">
                        <div class="cooperation mb-3">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                  <thead class="table-light">
                                    <tr>
                                      <th>No.</th>
                                      <th>JENIS KERAWANAN</th>
                                      <th>JUMLAH KEJADIAN</th>
                                      <th colspan="2">TINDAK LANJUT</th>
                                      <th colspan="2">JUMLAH TINDAK LANJUT</th>
                                    </tr>
                                    <tr>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th>DILAPORKAN</th>
                                      <th>TIDAK DILAPORKAN</th>
                                      <th>SELESAI</th>
                                      <th>ON PROGRESS</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach ($internals as $internal)
                                      <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$internal->vulnerability->incident ?? ''}}</td>
                                        <td>{{$internal->total_incident}}</td>
                                        <td>{{$internal->reported}}</td>
                                        <td>{{$internal->not_reported}}</td>
                                        <td>{{$internal->finished}}</td>
                                        <td>{{$internal->on_progress}}</td>
                                      </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- end tab pane -->
                    <div class="tab-pane" id="navtabs2-kerawanan-external" role="tabpanel">
                        <div class="cooperation mb-3">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                  <thead class="table-light">
                                    <tr>
                                      <th>No.</th>
                                      <th>JENIS KERAWANAN</th>
                                      <th>JUMLAH KEJADIAN</th>
                                      <th colspan="2">TINDAK LANJUT</th>
                                      <th colspan="2">JUMLAH TINDAK LANJUT</th>
                                    </tr>
                                    <tr>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th>DILAPORKAN</th>
                                      <th>TIDAK DILAPORKAN</th>
                                      <th>SELESAI</th>
                                      <th>ON PROGRESS</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach ($externals as $external)
                                      <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$external->vulnerability->incident ?? ''}}</td>
                                        <td>{{$external->total_incident}}</td>
                                        <td>{{$external->reported}}</td>
                                        <td>{{$external->not_reported}}</td>
                                        <td>{{$external->finished}}</td>
                                        <td>{{$external->on_progress}}</td>
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

