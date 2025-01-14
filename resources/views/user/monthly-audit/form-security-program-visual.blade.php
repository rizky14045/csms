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
                <a href="{{route('user.monthly-audit.security-program.index',['monthlyId' => $monthlyId])}}" class="btn btn-danger"> Back</a>
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
                                    @foreach ($items as $item)    
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$item->securityProgram->program_name}}</td>
                                            <td>{{$item->securityProgram->description}}</td>
                                            <td>{{$item->securityProgram->year}}</td>
                                            <td>{{$item->programs->count()}}</td>
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
                                                            <th colspan="53">{{$item->securityProgram->program_name}}</th>
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
                                                        @foreach($item->programs as $index => $program)
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
                </div>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

