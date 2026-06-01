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
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Data Maturity</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    @if (auth()->user()->roles[0]->name == 'Pusat')
                        <a href="{{ route('admin.marturity.index') }}" class="btn btn-danger mb-3"> Kembali</a>
                    @else
                        <a href="{{ route('admin.marturity.index') }}" class="btn btn-danger mb-3"> Kembali</a>
                    @endif
                    <div class="accordion" id="formAccordion">

                        @foreach ($areas as $area)
                            @php
                                // 1. HITUNG BOBOT AREA (1 / total levels dalam area)
                                $totalLevelsInArea = 0;
                                foreach ($area['sub_areas'] as $sa) {
                                    $totalLevelsInArea += count($sa['levels']);
                                }
                                $bobotArea = $totalLevelsInArea > 0 ? round(1 / $totalLevelsInArea, 2) : 0;
                            @endphp

                            <div class="accordion-item">
                                <h2 class="accordion-header bg-light" id="heading{{ $area['id'] }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $area['id'] }}" aria-expanded="false"
                                        aria-controls="collapse{{ $area['id'] }}">
                                        {{ $area['name'] }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $area['id'] }}"
                                    class="accordion-collapse collapse {{ request('areaId') == $area['id'] ? 'show' : '' }}"
                                    aria-labelledby="heading{{ $area['id'] }}" data-bs-parent="#formAccordion">
                                    <div class="accordion-body" style="overflow-x:auto; width:100%;">
                                        <table class="table table-bordered" style="min-width:1200px;">
                                            <thead class="table-light">
                                                <tr>
                                                    {{-- Sesuai urutan th asli kamu --}}
                                                    <th scope="col" class="align-middle text-center">No</th>
                                                    <th scope="col" class="align-middle text-center">Sub Area</th>
                                                    <th scope="col" class="align-middle text-center">Bobot</th>
                                                    <th scope="col" class="align-middle text-center">Hasil Assesment</th>
                                                    <th scope="col" class="align-middle text-center">Score ML</th>
                                                    <th scope="col" class="align-middle text-center">Level</th>
                                                    <th scope="col" class="align-middle text-center">Uraian</th>
                                                    <th scope="col" class="align-middle text-center">Total Eviden</th>
                                                    <th scope="col" class="align-middle text-center">Jumlah Eviden</th>
                                                    <th scope="col" class="align-middle text-center">Hasil</th>
                                                    <th scope="col" class="align-middle text-center">Catatan Assesment (
                                                        Eviden )</th>
                                                    <th scope="col" class="align-middle text-center">File</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($area['sub_areas'] as $subArea)
                                                    @php
                                                        $totalRowspan = collect($subArea['levels'])->reduce(function (
                                                            $carry,
                                                            $level,
                                                        ) {
                                                            return $carry + 1 + count($level['notes']);
                                                        }, 0);

                                                        $previousResult = false;
                                                        $firstLevel = true;
                                                        $totalResult = 0;

                                                        // Pre-calculate totalResult (Hasil Assessment) per sub-area dengan validasi chain-level
                                                        foreach ($subArea['levels'] as $level) {
                                                            $totalNotes = count($level['notes']);
                                                            $sumEviden = collect($level['notes'])
                                                                ->whereNotNull('attachment_file')
                                                                ->count();

                                                            $result = $totalNotes > 0 ? $sumEviden / $totalNotes : 0;

                                                            if ($previousResult) {
                                                                $result = 0;
                                                            }
                                                            if ($result !== 1.0 && $result !== 1) {
                                                                $previousResult = true;
                                                            }
                                                            $totalResult += $result;
                                                        }

                                                        // Hitung Score ML (totalResult * bobot area)
                                                        $totalML = $totalResult * $bobotArea;

                                                        // Reset penanda untuk loop render HTML tabel di bawah
                                                        $previousResult = false;
                                                        $isFirstSubAreaRow = true;
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

                                                            $result = $totalNotes > 0 ? $sumEviden / $totalNotes : 0;

                                                            // Jika level di atasnya ada yang belum lengkap, otomatis level ini bernilai 0
                                                            if ($previousResult) {
                                                                $result = 0;
                                                            }

                                                            if ($result !== 1.0 && $result !== 1) {
                                                                $previousResult = true;
                                                            }
                                                        @endphp

                                                        {{-- Kolom No dan Nama Sub Area (Hanya muncul sekali di baris pertama Sub Area) --}}
                                                        @if ($isFirstSubAreaRow)
                                                            <td class="text-left" rowspan="{{ $totalRowspan }}">
                                                                {{ $loop->parent->iteration }}
                                                            </td>

                                                            <td class="text-left w-25" rowspan="{{ $totalRowspan }}">
                                                                <h6 class="fw-bold">{{ $subArea['name'] }}</h6>
                                                                <p>Deskripsi : {{ $subArea['description'] }}</p>
                                                                <span>Referensi : {{ $subArea['reference'] }}</span>
                                                            </td>

                                                            {{-- Kolom Bobot Dinamis per area --}}
                                                            <td rowspan="{{ $totalRowspan }}"
                                                                class="text-center align-middle">
                                                                {{ $bobotArea }}
                                                            </td>

                                                            {{-- Kolom Hasil Assessment --}}
                                                            <td rowspan="{{ $totalRowspan }}"
                                                                class="text-center align-middle">
                                                                {{ $totalResult }}
                                                            </td>

                                                            {{-- Kolom Score ML --}}
                                                            <td rowspan="{{ $totalRowspan }}"
                                                                class="text-center align-middle">
                                                                {{ $totalML }}
                                                            </td>

                                                            @php $isFirstSubAreaRow = false; @endphp
                                                        @endif

                                                        {{-- Kelompok Kolom Level --}}
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

                                                        {{-- Kolom Hasil Per-Level --}}
                                                        <td rowspan="{{ $totalNotes + 1 }}" class="text-center">
                                                            {{ $result }}
                                                        </td>

                                                        @if (count($level['notes']) == 0)
                                                            <td colspan="2" class="text-center text-muted">Tidak ada
                                                                catatan</td>
                                                            </tr>
                                                        @endif

                                                        {{-- Kelompok Kolom Notes --}}
                                                        @foreach ($level['notes'] as $note)
                                                            <tr>
                                                                <td>{{ $note['note'] }}</td>

                                                                <td style="min-width:200px;">
                                                                    <div class="d-flex gap-2 align-items-center">
                                                                        @if (!empty($note['attachment_file']))
                                                                            <a href="{{ asset('uploads/attachment_file_marturity_file/' . $note['attachment_file']) }}"
                                                                                class="btn btn-success btn-sm"
                                                                                target="_blank" download>
                                                                                ⬇ Download File
                                                                            </a>
                                                                        @else
                                                                            <span class="text-danger"
                                                                                style="font-size: 12px; font-style: italic;">Belum
                                                                                upload berkas</span>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                        @php
                                                            $firstLevel = false;
                                                        @endphp
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
</div> @endsection
