@extends('layout.app')

@section('styles')
@stop

@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1 d-flex align-items-center gap-2">
        <a href="{{ route('admin.audit-smp-score.index') }}" class="text-muted text-decoration-none">
            <i data-feather="arrow-left" style="width:18px;height:18px;"></i>
        </a>
        <h4 class="fs-18 fw-semibold m-0">Data Audit {{ $auditData->unit->name }}</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.audit-smp-score.index') }}">Data Audit SMP</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <div class="table-responsive">

            @if($auditData->childrenHeader->count() == 0)

                <div class="text-center">
                    Tidak ada data
                </div>

            @else

            @php
                $grandTotalAudit = 0;
                $grandTotalSelf = 0;
            @endphp

            <table class="table table-bordered text-center align-middle">

                <thead style="background:#5DADE2; color:white;">
                    <tr>
                        <th rowspan="2">Elemen</th>
                        <th rowspan="2">Bobot</th>
                        <th colspan="2">Kriteria</th>
                        <th colspan="2">Self Audit</th>
                        <th colspan="2">Audit</th>
                        <th rowspan="2">Evidence</th>
                        <th rowspan="2">File</th>
                        <th rowspan="2">Temuan</th>
                        <th rowspan="2">Rekomendasi</th>
                        <th rowspan="2">Due Date</th>
                        <th rowspan="2">PIC</th>
                    </tr>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th style="min-width:150px;">Nilai</th>
                        <th>Elemen</th>
                        <th>Nilai</th>
                        <th>Elemen</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($auditData->childrenHeader as $header)

                    @php
                        $allKriteria = collect();

                        $allKriteria = $allKriteria->merge($header->kriteria);

                        foreach($header->pernyataan as $p){
                            $allKriteria = $allKriteria->merge($p->kriteria);
                        }

                        $pembagi = max(1, $allKriteria->count() * 2);

                        $subAudit = 0;
                        $subSelf = 0;

                        $rowspan = 0;

                        foreach($allKriteria as $k){
                            $rowspan += max(1, $k->evidence->count());
                        }
                    @endphp

                    @foreach($allKriteria as $kriteria)

                        @php
                            $nilaiSelf = (int)($kriteria->pencapaian_nilai_kriteria_self ?? 0);
                            $nilaiAudit = (int)($kriteria->pencapaian_nilai_kriteria ?? 0);

                            $nilaiElemenSelf = ($nilaiSelf * $header->bobot) / $pembagi;
                            $nilaiElemenAudit = ($nilaiAudit * $header->bobot) / $pembagi;

                            $subSelf += $nilaiElemenSelf;
                            $subAudit += $nilaiElemenAudit;

                            $evidences = $kriteria->evidence->count()
                                ? $kriteria->evidence
                                : collect([null]);

                            $bgSelf = $nilaiSelf == 2 ? '#28a745' : ($nilaiSelf == 1 ? '#ffc107' : '#dc3545');
                            $bgAudit = $nilaiAudit == 2 ? '#28a745' : ($nilaiAudit == 1 ? '#ffc107' : '#dc3545');
                        @endphp

                        @foreach($evidences as $i => $evidence)

                        <tr>

                            @if($loop->parent->first && $loop->first)
                                <td rowspan="{{ $rowspan }}">{{ $header->name }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $header->bobot }}%</td>
                            @endif

                            @if($loop->first)

                                <td rowspan="{{ count($evidences) }}">
                                    {{ $loop->parent->iteration }}
                                </td>

                                <td rowspan="{{ count($evidences) }}" style="text-align:left">
                                    {{ $kriteria->name }}
                                </td>

                                {{-- SELF AUDIT (read-only, halaman admin cuma tampilan) --}}
                                <td rowspan="{{ count($evidences) }}"
                                    style="background:{{ $bgSelf }};color:white; min-width:150px;">
                                    {{ $nilaiSelf }}
                                </td>

                                <td rowspan="{{ count($evidences) }}">
                                    {{ number_format($nilaiElemenSelf,2) }}%
                                </td>

                                {{-- AUDIT (read-only) --}}
                                <td rowspan="{{ count($evidences) }}"
                                    style="background:{{ $bgAudit }};color:white;">
                                    {{ $nilaiAudit }}
                                </td>

                                <td rowspan="{{ count($evidences) }}">
                                    {{ number_format($nilaiElemenAudit,2) }}%
                                </td>

                            @endif

                            {{-- EVIDENCE --}}
                            <td>{{ $evidence->name ?? '-' }}</td>

                            {{-- FILE (read-only) --}}
                            <td style="min-width:150px;">
                                @if(isset($evidence->evidence_file) && $evidence->evidence_file != '')
                                    <a href="{{ asset('uploads/evidence_file/' . $evidence->evidence_file) }}"
                                       target="_blank"
                                       class="btn btn-primary btn-sm">
                                        Lihat File
                                    </a>
                                @else
                                    -
                                @endif
                            </td>

                            <td>{{ $evidence->temuan ?? '-' }}</td>
                            <td>{{ $evidence->rekomendasi ?? '-' }}</td>
                            <td>{{ isset($evidence->due_date) ? \Carbon\Carbon::parse($evidence->due_date)->format('d-m-Y') : '-' }}</td>
                            <td>{{ $evidence->pic ?? '-' }}</td>

                        </tr>

                        @endforeach
                    @endforeach

                    <tr style="background:#5DADE2;color:white;">
                        <td colspan="4">SubTotal Elemen</td>
                        <td>{{ number_format($subSelf,2) }}%</td>
                        <td></td>
                        <td>{{ number_format($subAudit,2) }}%</td>
                        <td></td>
                        <td colspan="6"></td>
                    </tr>

                    @php
                        $grandTotalAudit += $subAudit;
                        $grandTotalSelf += $subSelf;
                    @endphp

                @endforeach

                <tr style="background:#2E86C1;color:white;">
                    <td colspan="4">TOTAL</td>
                    <td>{{ number_format($grandTotalSelf,2) }}%</td>
                    <td></td>
                    <td>{{ number_format($grandTotalAudit,2) }}%</td>
                    <td></td>
                    <td colspan="6"></td>
                </tr>

                @php
                    $kategoriSelf  = $grandTotalSelf  < 55 ? 'Kurang' : ($grandTotalSelf  <= 70 ? 'Cukup' : ($grandTotalSelf  <= 85 ? 'Baik' : 'Baik Sekali'));
                    $kategoriAudit = $grandTotalAudit < 55 ? 'Kurang' : ($grandTotalAudit <= 70 ? 'Cukup' : ($grandTotalAudit <= 85 ? 'Baik' : 'Baik Sekali'));
                    $colorSelf     = $grandTotalSelf  < 55 ? '#dc3545' : ($grandTotalSelf  <= 70 ? '#ffc107' : ($grandTotalSelf  <= 85 ? '#28a745' : '#198754'));
                    $colorAudit    = $grandTotalAudit < 55 ? '#dc3545' : ($grandTotalAudit <= 70 ? '#ffc107' : ($grandTotalAudit <= 85 ? '#28a745' : '#198754'));
                @endphp

                <tr style="background:#2E86C1;color:white;">
                    <td colspan="4">KATEGORI</td>
                    <td style="background:{{ $colorSelf }};color:white;">{{ $kategoriSelf }}</td>
                    <td></td>
                    <td style="background:{{ $colorAudit }};color:white;">{{ $kategoriAudit }}</td>
                    <td></td>
                    <td colspan="6"></td>
                </tr>

                </tbody>
            </table>

            @endif

        </div>
    </div>
</div>

@endsection
