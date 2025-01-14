@extends('user.layout.app')
@section('styles')

@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Program Keamanan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Program Keamanan</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="d-flex justify-content-between pe-3 pt-3 ps-3">
                <a href="{{route('user.monthly-audit.realization-program.index',['monthlyId' => $monthlyId,'programId'=>$programId])}}" class="btn btn-danger">Back</a>
            </div>
            <div class="card-body">  
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="table-light">
                            <tr>
                                <th colspan="53">{{$securityProgram->program_name}}</th>
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
                            @foreach($programs as $index => $program)
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
                </div>
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

