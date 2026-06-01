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
            <h4 class="fs-18 fw-semibold m-0">KPI</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Data KPI</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">

                    <a href="{{ route('user.keamanan.index') }}" class="btn btn-danger mb-3">
                        Kembali
                    </a>

                    <div class="accordion" id="formAccordion">

                        @foreach ($areas as $area)
                            @php
                                // 1. HITUNG TOTAL LEVELS DALAM AREA (UNTUK BOBOT KARENA DIKUNCI PER AREA)
                                $totalLevelsInArea = 0;
                                foreach ($area['sub_areas'] as $sa) {
                                    $totalLevelsInArea += count($sa['levels']);
                                }
                                $bobotArea = $totalLevelsInArea > 0 ? number_format(1 / $totalLevelsInArea, 2) : 0;
                            @endphp

                            <div class="accordion-item">

                                <h2 class="accordion-header bg-light" id="heading{{ $area['id'] }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $area['id'] }}">
                                        {{ $area['name'] }}
                                    </button>
                                </h2>

                                <div id="collapse{{ $area['id'] }}" class="accordion-collapse collapse"
                                    data-bs-parent="#formAccordion">

                                    <div class="accordion-body">

                                        <table class="table table-bordered">

                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Sub Area</th>
                                                    <th class="text-center">Bobot</th>
                                                    <th class="text-center">Hasil Assessment</th>
                                                    <th class="text-center">Score ML</th>
                                                    <th class="text-center">Level</th>
                                                    <th class="text-center">Uraian</th>
                                                    <th class="text-center">Catatan Assesment (Eviden)</th>
                                                    <th class="text-center">File</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                @foreach ($area['sub_areas'] as $subArea)
                                                    @php
                                                        $totalRowspan = 0;

                                                        foreach ($subArea['levels'] as $level) {
                                                            $totalRowspan += 1 + count($level['notes']);
                                                        }

                                                        $firstLevel = true;

                                                        // 2. CEK APAKAH SUB-AREA INI DATA UPLOADNYA SUDAH LENGKAP SEMUA
                                                        $semuaNoteSelesai = true;
                                                        foreach ($subArea['levels'] as $lvl) {
                                                            foreach ($lvl['notes'] as $nt) {
                                                                if (empty($nt['attachment_file'])) {
                                                                    $semuaNoteSelesai = false;
                                                                    break 2;
                                                                }
                                                            }
                                                        }

                                                        // Nilai 1 jika lengkap, 0 jika belum
                                                        $hasilAssessmentSubArea =
                                                            $semuaNoteSelesai && count($subArea['levels']) > 0 ? 1 : 0;

                                                        // 3. HITUNG SCORE ML PER SUB-AREA
                                                        $scoreMLSubArea = number_format(
                                                            $bobotArea * $hasilAssessmentSubArea,
                                                            2,
                                                        );
                                                    @endphp

                                                    <tr>

                                                        <td rowspan="{{ $totalRowspan }}">
                                                            {{ $loop->iteration }}
                                                        </td>

                                                        <td rowspan="{{ $totalRowspan }}">
                                                            <h6 class="fw-bold">
                                                                {{ $subArea['name'] }}
                                                            </h6>

                                                            <p>
                                                                Deskripsi : {{ $subArea['description'] }}
                                                            </p>

                                                            <span>
                                                                Referensi : {{ $subArea['reference'] }}
                                                            </span>
                                                        </td>

                                                        {{-- KOLOM BOBOT --}}
                                                        <td rowspan="{{ $totalRowspan }}" class="text-center">
                                                            {{ $bobotArea }}
                                                        </td>

                                                        {{-- KOLOM HASIL ASSESSMENT --}}
                                                        <td rowspan="{{ $totalRowspan }}" class="text-center">
                                                            {{ $hasilAssessmentSubArea }}
                                                        </td>

                                                        {{-- KOLOM SCORE ML --}}
                                                        <td rowspan="{{ $totalRowspan }}" class="text-center">
                                                            {{ $scoreMLSubArea }}
                                                        </td>

                                                        @foreach ($subArea['levels'] as $level)
                                                            @if (!$firstLevel)
                                                    <tr>
                                                @endif

                                                <td rowspan="{{ count($level['notes']) + 1 }}">
                                                    {{ $level['level'] }}
                                                </td>

                                                <td rowspan="{{ count($level['notes']) + 1 }}" class="w-25">
                                                    <p>
                                                        {{ $level['description'] }}
                                                    </p>
                                                </td>

                                                @if (count($level['notes']) == 0)
                                                    <td colspan="2" class="text-center text-muted">Tidak ada catatan</td>
                                                    </tr>
                                                @endif

                                                @foreach ($level['notes'] as $note)
                                                    <tr>

                                                        <td>
                                                            {{ $note['note'] }}
                                                        </td>

                                                        <td>

                                                            <div class="d-flex gap-2 align-items-center">

                                                                {{-- Logika download & fallback teks merah --}}
                                                                @if ($note['attachment_file'])
                                                                    <a href="{{ asset('uploads/attachment_file_kpi_file/' . $note['attachment_file']) }}"
                                                                        class="btn btn-info btn-sm" download>
                                                                        Download
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

                        </tr>
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
    </div>

@endsection
