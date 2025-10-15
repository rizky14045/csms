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
        <h4 class="fs-18 fw-semibold m-0">Maturity</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Maturity</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <a href="{{route('user.marturity.index')}}" class="btn btn-danger mb-3"> Back</a>
                 <!-- Komitmen Management -->
                 <div class="accordion" id="formAccordion">

                    @foreach ($areas as $area)

                    <!-- Section for Each area -->
                    <div class="accordion-item">
                        <h2 class="accordion-header bg-light" id="heading{{$area->id}}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$area->id}}" aria-expanded="false" aria-controls="collapse{{$area->id}}">
                                {{$area->name}}
                            </button>
                        </h2>
                        <div id="collapse{{$area->id}}" class="accordion-collapse collapse" aria-labelledby="heading{{$area->id}}" data-bs-parent="#formAccordion">
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
                                      
                                        @foreach ($area->subAreas as $subArea)
                                        
                                            @php
                                                $totalRowspan = $subArea->levels->reduce(function ($carry, $level) {
                                                    return $carry + 1 + $level->notes()->count();
                                                }, 0); // Total rowspan pertama
                                                $previousResult = false; // Reset setiap subArea
                                                $totalResult = 0;
                                                $firstLevel = true;
                                                $totalSub = $subArea->levels->count();
                                                $bobot = number_format(1 / $totalSub ,2);

                                                $totalML = $totalSub * $bobot;
                                            @endphp
                                            <tr>
                                                <td class="text-left" rowspan="{{ $totalRowspan }}">{{ $loop->iteration }}</td>
                                                <td class="text-left w-25" rowspan="{{ $totalRowspan }}">
                                                    <h6 class="fw-bold">{{ $subArea->name }}</h6>
                                                    <p class="text-justify">Deskripsi : {{ $subArea->description }}</p>
                                                    <span>Referensi : {{ $subArea->reference }}</span>
                                                </td>
                                                <td rowspan="{{ $totalRowspan }}" class="text-center align-middle">{{$bobot}}</td>
                                                <td rowspan="{{ $totalRowspan }}" class="text-center align-middle">{{$totalSub}}</td>
                                                <td rowspan="{{ $totalRowspan }}" class="text-center align-middle">{{$totalML}}</td>
                                            
                                                @php 
                                                   
                                                @endphp
                                                @foreach ($subArea->levels as $level)
                                                
                                                    @if (!$firstLevel)
                                                        <tr>
                                                    @endif
                                                    @php 
                                                        $totalNotes = $level->notes->count();
                                                        $sumEviden = $level->notes->whereNotNull('attachment_file')->count();
                                                        $result = $totalNotes > 0 ? ($sumEviden / $totalNotes) : 0;

                                                    // Jika sebelumnya sudah ada result yang bukan 1 dalam subArea, set result jadi 0
                                                    if ($previousResult) {
                                                        $result = 0;
                                                    }

                                                    // Jika result saat ini bukan 1, tandai bahwa semua result berikutnya harus 0
                                                    if ($result !== 1) {
                                                        $previousResult = true;
                                                    }
                                                    $totalResult = $totalResult + $result;
                                                    @endphp
                                                    <td rowspan="{{ $level->notes()->count() + 1 }}">{{ $level->level }}</td>
                                                    <td rowspan="{{ $level->notes()->count() + 1 }}" class="w-25 text-justify">
                                                        <p class="text-justify">
                                                            {{ $level->description }}
                                                        </p>
                                                    </td>
                                                    <td rowspan="{{ $level->notes()->count() + 1 }}" class="text-center align-middle">{{$totalNotes}}</td>
                                                    <td rowspan="{{ $level->notes()->count() + 1 }}" class="text-center align-middle">{{$sumEviden}}</td>
                                                    <td rowspan="{{ $level->notes()->count() + 1 }}" class="text-center align-middle">{{$result}}</td>
                                                </tr>
                                        
                                                @foreach ($level->notes as $note)
                                                    <tr>
                                                        <td>{{ $note->note }}</td>
                                                        <td>
                                                            <div class="d-flex gap-2 align-items-center">

                                                                @if ($note->attachment_file)
                                                                <a href="{{ asset('uploads/attachment_file_marturity_file/'.$note->attachment_file) }}" class="btn btn-info btn-sm" download>Download</a>
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

