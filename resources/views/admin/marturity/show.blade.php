@extends('layout.app')
@section('styles')
<style>
    .accordion-button::after { filter: invert(100%); }
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

                <div class="d-flex gap-2 mb-3">
                    <a href="{{ route('admin.marturity.index') }}" class="btn btn-danger">Kembali</a>
                    <a href="{{ route('admin.marturity.export', $marturity->id) }}" class="btn btn-success">
                        ⬇ Export Excel
                    </a>
                </div>

                @php
                    $totalSubAreas  = collect($areas)->sum(fn($a) => count($a['sub_areas']));
                    $bobot          = $totalSubAreas > 0 ? 1 / $totalSubAreas : 0;
                    $grandTotalBobot = 0;
                    $grandTotalML   = 0;
                @endphp

                <div class="accordion" id="formAccordion">

                @foreach ($areas as $area)
                <div class="accordion-item">
                    <h2 class="accordion-header bg-light">
                        <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $area['id'] }}">
                            {{ $area['name'] }}
                        </button>
                    </h2>

                    <div id="collapse{{ $area['id'] }}"
                         class="accordion-collapse collapse"
                         data-bs-parent="#formAccordion">
                        <div class="accordion-body" style="overflow-x:auto;">

                            <table class="table table-bordered align-middle" style="min-width:1100px;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width:50px;"  class="text-center">No</th>
                                        <th style="min-width:200px;" class="text-center">Sub Area</th>
                                        <th style="min-width:55px;"  class="text-center">Level</th>
                                        <th style="min-width:230px;" class="text-center">Uraian</th>
                                        <th style="min-width:95px;"  class="text-center">Total Evidence</th>
                                        <th style="min-width:95px;"  class="text-center">Jumlah Evidence</th>
                                        <th style="min-width:220px;" class="text-center">File Evidence</th>
                                        <th style="min-width:80px;"  class="text-center">Bobot</th>
                                        <th style="min-width:80px;"  class="text-center">Hasil</th>
                                        <th style="min-width:90px;"  class="text-center">Score ML</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @foreach ($area['sub_areas'] as $subArea)
                                @php
                                    $levels     = $subArea['levels'];
                                    $levelCount = count($levels);

                                    $levelCalcs = [];
                                    foreach ($levels as $lvl) {
                                        $files   = json_decode($lvl['attachment_files'] ?? '[]', true) ?: [];
                                        $jumlah  = count($files);
                                        $totalEv = max(1, (int)($lvl['total_evidence'] ?? 1));
                                        $calc    = round($jumlah / $totalEv, 4);
                                        $levelCalcs[] = compact('lvl', 'files', 'jumlah', 'totalEv', 'calc');
                                    }

                                    $hasil   = round(array_sum(array_column($levelCalcs, 'calc')), 4);
                                    $scoreML = round($hasil * $bobot, 4);
                                    $grandTotalBobot += $bobot;
                                    $grandTotalML    += $scoreML;
                                @endphp

                                @foreach ($levelCalcs as $idx => $lc)
                                <tr>
                                    @if ($idx === 0)
                                    <td class="text-center" rowspan="{{ $levelCount }}">{{ $loop->parent->iteration }}</td>
                                    <td rowspan="{{ $levelCount }}">
                                        <strong>{{ $subArea['name'] }}</strong>
                                        @if($subArea['description'])
                                        <div class="text-muted small mt-1">{{ $subArea['description'] }}</div>
                                        @endif
                                        @if($subArea['reference'])
                                        <div class="text-muted small">Ref: {{ $subArea['reference'] }}</div>
                                        @endif
                                    </td>
                                    @endif

                                    <td class="text-center">{{ $lc['lvl']['level'] }}</td>
                                    <td style="white-space:normal;">{{ $lc['lvl']['description'] }}</td>
                                    <td class="text-center">{{ $lc['totalEv'] }}</td>
                                    <td class="text-center">{{ $lc['jumlah'] }}</td>

                                    <td>
                                        @if(count($lc['files']) > 0)
                                        <div class="d-flex flex-column gap-1">
                                            @foreach ($lc['files'] as $fi => $file)
                                            <a href="{{ asset('uploads/attachment_file_marturity_file/' . $file) }}"
                                               target="_blank"
                                               class="btn btn-success btn-sm"
                                               style="font-size:11px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                               ⬇ File {{ $fi + 1 }}
                                            </a>
                                            @endforeach
                                        </div>
                                        @else
                                        <span class="text-muted small">-</span>
                                        @endif
                                    </td>

                                    @if ($idx === 0)
                                    <td class="text-center" rowspan="{{ $levelCount }}">{{ round($bobot, 4) }}</td>
                                    <td class="text-center fw-semibold" rowspan="{{ $levelCount }}">{{ $hasil }}</td>
                                    <td class="text-center fw-semibold" rowspan="{{ $levelCount }}">{{ $scoreML }}</td>
                                    @endif
                                </tr>
                                @endforeach

                                @endforeach

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                @endforeach

                </div>{{-- end accordion --}}

                {{-- GRAND TOTAL --}}
                <div class="card mt-4 border-0 shadow-sm">
                    <div class="card-header bg-primary text-white py-2">
                        <h6 class="mb-0 fw-semibold">Total Keseluruhan</h6>
                    </div>
                    <div class="card-body p-3">
                        <table class="table table-bordered mb-0" style="max-width:380px;">
                            <thead class="table-light">
                                <tr>
                                    <th>Komponen</th>
                                    <th class="text-center" style="min-width:120px;">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Bobot</td>
                                    <td class="text-center fw-bold">{{ round($grandTotalBobot, 4) }}</td>
                                </tr>
                                <tr>
                                    <td>Total Score ML</td>
                                    <td class="text-center fw-bold text-primary">{{ round($grandTotalML, 4) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
