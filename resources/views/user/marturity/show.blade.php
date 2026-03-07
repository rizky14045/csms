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
        <h4 class="fs-18 fw-semibold m-0">Maturity</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Maturity</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <a href="{{route('user.marturity.index')}}" class="btn btn-danger mb-3"> Kembali</a>
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
                        <div id="collapse{{$area['id']}}" class="accordion-collapse collapse {{request('areaId') == $area['id'] ? 'show' :''}}" aria-labelledby="heading{{$area['id']}}" data-bs-parent="#formAccordion">
                            <div class="accordion-body">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="align-middle text-center">No</th>
                                            <th scope="col" class="align-middle text-center">Sub Area</th>
                                            <th scope="col" class="align-middle text-center">Level</th>
                                            <th scope="col" class="align-middle text-center">Uraian</th>
                                            <th scope="col" class="align-middle text-center">Total Eviden</th>
                                            <th scope="col" class="align-middle text-center">Jumlah Eviden</th>
                                            <th scope="col" class="align-middle text-center">Bobot</th>
                                            <th scope="col" class="align-middle text-center">Catatan Assesment ( Eviden )</th>
                                            <th scope="col" class="align-middle text-center">File</th>
                                            <th scope="col" class="align-middle text-center">Action</th>
                                            <th scope="col" class="align-middle text-center">Hasil Assesment</th>
                                            <th scope="col" class="align-middle text-center">Score ML</th>
                                            <th scope="col" class="align-middle text-center">Hasil</th>
                                         
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($area['sub_areas'] as $subArea)

                                            @php
                                                $totalRowspan = collect($subArea['levels'])->reduce(function ($carry, $level) {
                                                    return $carry + 1 + count($level['notes']);
                                                }, 0);

                                                $previousResult = false;
                                                $firstLevel = true;
                                                $totalResult = 0;
                                            @endphp

                                            <tr>
                                                <td class="text-left" rowspan="{{ $totalRowspan }}">
                                                    {{ $loop->iteration }}
                                                </td>

                                                <td class="text-left w-25" rowspan="{{ $totalRowspan }}">
                                                    <h6 class="fw-bold">{{ $subArea['name'] }}</h6>
                                                    <p>Deskripsi : {{ $subArea['description'] }}</p>
                                                    <span>Referensi : {{ $subArea['reference'] }}</span>
                                                </td>

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

                                                    <td rowspan="{{ $totalNotes + 1 }}" class="w-25">
                                                        {{ $level['description'] }}
                                                    </td>

                                                    <td rowspan="{{ $totalNotes + 1 }}" class="text-center">
                                                        {{ $totalNotes }}
                                                    </td>

                                                    <td rowspan="{{ $totalNotes + 1 }}" class="text-center">
                                                        {{ $sumEviden }}
                                                    </td>

                                                    <td rowspan="{{ $totalNotes + 1 }}" class="text-center">
                                                        {{ $result }}
                                                    </td>

                                                    </tr>

                                                    @foreach ($level['notes'] as $note)
                                                        <tr>
                                                            <td>{{ $note['note'] }}</td>

                                                            <td style="width:50%;">
                                                                <form action="{{ route('user.marturity.uploadNote', [
                                                                    'marturity' => $note['marturity_id'],
                                                                    'areaId' => $subArea['area_id'],
                                                                    'note' => $note['id']
                                                                ]) }}"
                                                                method="POST"
                                                                enctype="multipart/form-data">

                                                                    @csrf
                                                                    @method('PATCH')

                                                                    <input type="file"
                                                                        class="form-control"
                                                                        name="attachment_file_{{ $note['id'] }}"
                                                                        accept=".pdf"
                                                                        required>

                                                                    @error('attachment_file_'.$note['id'])
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror
                                                            </td>

                                                            <td>
                                                                    <div class="d-flex gap-2">

                                                                        @if (!empty($note['attachment_file']))
                                                                            <a href="{{ asset('uploads/attachment_file_marturity_file/'.$note['attachment_file']) }}"
                                                                            class="btn btn-info btn-sm"
                                                                            download>
                                                                            Download
                                                                            </a>
                                                                        @endif

                                                                        <button type="submit"
                                                                                class="btn btn-success btn-sm">
                                                                                Upload
                                                                        </button>

                                                                    </div>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                    @php
                                                        $firstLevel = false;
                                                    @endphp

                                                @endforeach

                                                @php
                                                    $totalLevel = count($subArea['levels']);
                                                    $bobot = $totalLevel > 0 ? round(1 / $totalLevel, 2) : 0;
                                                    $totalML = $totalResult * $bobot;
                                                @endphp

                                                <td rowspan="{{ $totalRowspan }}" class="text-center">
                                                    {{ $bobot }}
                                                </td>

                                                <td rowspan="{{ $totalRowspan }}" class="text-center">
                                                    {{ $totalResult }}
                                                </td>

                                                <td rowspan="{{ $totalRowspan }}" class="text-center">
                                                    {{ $totalML }}
                                                </td>

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

