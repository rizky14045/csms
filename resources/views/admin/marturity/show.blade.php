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
        <h4 class="fs-18 fw-semibold m-0">Marturity</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Marturity</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <a href="{{route('admin.marturity.index')}}" class="btn btn-danger mb-3"> Kembali</a>
                 <!-- Komitmen Management -->
                 <div class="accordion" id="formAccordion">
                    @foreach ($areas as $area)
                    <!-- Section for Each area -->
                    <div class="accordion-item">
                        <h2 class="accordion-header bg-light" id="heading{{$area['id']}}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$area['id']}}" aria-expanded="false" aria-controls="collapse{{$area['id']}}">
                                {{$area['name']}}
                            </button>
                        </h2>
                        <div id="collapse{{$area['id']}}" class="accordion-collapse collapse" aria-labelledby="heading{{$area['id']}}" data-bs-parent="#formAccordion">
                            <div class="accordion-body">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="align-middle text-center">No</th>
                                            <th scope="col" class="align-middle text-center">Sub Area</th>
                                            <th scope="col" class="align-middle text-center">Hasil Assesment</th>
                                            <th scope="col" class="align-middle text-center">Score ML</th>
                                            <th scope="col" class="align-middle text-center">Hasil</th>
                                            <th scope="col" class="align-middle text-center">Level</th>
                                            <th scope="col" class="align-middle text-center">Uraian</th>
                                            <th scope="col" class="align-middle text-center">Total Eviden</th>
                                            <th scope="col" class="align-middle text-center">Jumlah Eviden</th>
                                            <th scope="col" class="align-middle text-center">Bobot</th>
                                            <th scope="col" class="align-middle text-center">Catatan Assesment ( Eviden )</th>
                                            <th scope="col" class="align-middle text-center">Action</th>
                                         
                                        </tr>
                                    </thead>
                                    <tbody>
                                      
                                        @foreach ($area['sub_areas'] as $subArea)
                                        
                                            @php
                                                $totalRowspan = collect($subArea['levels'])->reduce(function ($carry, $level) {
                                                    return $carry + 1 + count($level['notes']);
                                                }, 0);

                                                $previousResult = false;
                                                $totalResult = 0;
                                                $firstLevel = true;

                                                $totalSub = count($subArea['levels']);
                                                $bobot = $totalSub > 0 ? number_format(1 / $totalSub, 2) : 0;
                                                $totalML = $totalSub * $bobot;
                                            @endphp
                                            <tr>
                                                <td class="text-left" rowspan="{{ $totalRowspan }}">{{ $loop->iteration }}</td>
                                                <td class="text-left w-25" rowspan="{{ $totalRowspan }}">
                                                    <h6 class="fw-bold">{{ $subArea['name'] }}</h6>
                                                    <p class="text-justify">Deskripsi : {{ $subArea['description'] }}</p>
                                                    <span>Referensi : {{ $subArea['reference'] }}</span>
                                                </td>
                                                <td rowspan="{{ $totalRowspan }}" class="text-center align-middle">{{$bobot}}</td>
                                                <td rowspan="{{ $totalRowspan }}" class="text-center align-middle">{{$totalSub}}</td>
                                                <td rowspan="{{ $totalRowspan }}" class="text-center align-middle">{{$totalML}}</td>
                                            
                                                @php 
                                                   
                                                @endphp
                                                @foreach ($subArea['levels'] as $level)
                                                
                                                    @if (!$firstLevel)
                                                        <tr>
                                                    @endif
                                                    @php
                                                    $totalNotes = count($level['notes']);
                                                    $sumEviden = collect($level['notes'])
                                                        ->whereNotNull('attachment_file')
                                                        ->count();

                                                    $result = $totalNotes > 0 ? ($sumEviden / $totalNotes) : 0;

                                                    if ($previousResult) {
                                                        $result = 0;
                                                    }

                                                    if ($result !== 1) {
                                                        $previousResult = true;
                                                    }

                                                    $totalResult += $result;
                                                @endphp
                                                    <td rowspan="{{ $totalNotes + 1 }}">
                                                        {{ $level['level'] }}
                                                    </td>

                                                    <td rowspan="{{ $totalNotes + 1 }}">
                                                        {{ $level['description'] }}
                                                    </td>

                                                    <td rowspan="{{ $totalNotes + 1 }}">
                                                        {{ $totalNotes }}
                                                    </td>

                                                    <td rowspan="{{ $totalNotes + 1 }}">
                                                        {{ $sumEviden }}
                                                    </td>

                                                    <td rowspan="{{ $totalNotes + 1 }}">
                                                        {{ $result }}
                                                    </td>
                                                </tr>
                                        
                                                @foreach ($level['notes'] as $note)
                                                    <tr>
                                                        <td>{{ $note['note'] }}</td>
                                                        <td>
                                                            <div class="d-flex gap-2 align-items-center">

                                                                @if ($note['attachment_file'])
                                                                <a href="{{ asset('uploads/attachment_file_marturity_file/'.$note['attachment_file']) }}" class="btn btn-info btn-sm" download>Download</a>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                    
                                                @php $firstLevel = false; @endphp
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

