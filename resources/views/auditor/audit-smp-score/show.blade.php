@extends('layout.app')
@section('styles')

@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Data Audit</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Audit</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">  
                <div class="table-responsive">
                    @if(count($auditData->childrenHeader) == 0)
                        <div class="text-center">
                            <p class="mb-0">Tidak ada data audit.</p>
                        </div>
                    @else
                    <table class="table table-bordered text-center align-middle">
                        <thead class="text-white text-center align-middle" style="background-color:#5DADE2;">
                            <tr>
                                <th rowspan="2" style="background-color:#5DADE2;">Elemen</th>
                                <th rowspan="2" style="background-color:#5DADE2;">Bobot Elemen</th>
                                <th colspan="2" rowspan="2" style="background-color:#5DADE2;">Kriteria</th>
                                <th colspan="2" style="background-color:#5DADE2;">Audit</th>
                                <th rowspan="2" style="background-color:#5DADE2;">Evidence</th>
                                <th rowspan="2" style="background-color:#5DADE2;">Temuan</th>
                                <th rowspan="2" style="background-color:#5DADE2;">Rekomendasi</th>
                                <th rowspan="2" style="background-color:#5DADE2;">Due Date</th>
                                <th rowspan="2" style="background-color:#5DADE2;">PIC</th>
                                <th rowspan="2" style="background-color:#5DADE2;">Action</th>
                            </tr>
                            <tr>
                                <th style="background-color:#5DADE2;">Pencapaian Kriteria</th>
                                <th style="background-color:#5DADE2;">Pencapaian Nilai Elemen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalAllHeader = 0;
                            @endphp
                            @foreach ($auditData->childrenHeader as $header)

                                @php
                                    $countKriteria = 0;
                                    $countPernyataan = 0;

                                    // =========================
                                    // Hitung total baris
                                    // =========================
                                    $totalRows = 0;

                                    // kriteria langsung
                                    foreach ($header->kriteria as $k) {
                                        $totalRows += max(1, $k->evidence->count());
                                    }

                                    // pernyataan + kriteria
                                    foreach ($header->pernyataan as $p) {
                                        $totalRows += 1;
                                        foreach ($p->kriteria as $k) {
                                            $totalRows += max(1, $k->evidence->count());
                                        }
                                    }

                                    $totalPembagi = 
                                            ($header->kriteria->count() +
                                            $header->pernyataan->flatMap->kriteria->count() ) *2;

                                    $totalRows = $totalRows + 1 + count($header->pernyataan);

                                    $isFirstHeaderRow = true;
                                    $subTotalElemen = 0;
                                @endphp

                                {{-- =========================
                                    KRITERIA LANGSUNG
                                ========================== --}}
                                @foreach ($header->kriteria as $kriteria)

                                    @php
                                        $countKriteria++;
                                        $evidenceCount = max(1, $kriteria->evidence->count());
                                        $isFirstKriteriaRow = true;

                                        $evidences = $kriteria->evidence->count()
                                            ? $kriteria->evidence
                                            : collect([null]);
                                    @endphp

                                    @foreach ($evidences as $evidence)
                                        <tr>
                                            {{-- KOLOM A & B --}}
                                            @if ($isFirstHeaderRow)
                                                <td rowspan="{{ $totalRows }}">{{ $header->name }}</td>
                                                <td rowspan="{{ $totalRows }}">{{ $header->bobot }}%</td>
                                                @php $isFirstHeaderRow = false; @endphp
                                            @endif

                                            {{-- KRITERIA --}}
                                            @if ($isFirstKriteriaRow)
                                                <td rowspan="{{ $evidenceCount }}">
                                                    {{ $loop->parent->parent->iteration }}.{{ $countKriteria }}
                                                </td>
                                                <td rowspan="{{ $evidenceCount }}" style="text-align:left">
                                                    {{ $kriteria->name }}
                                                </td>
                                                @php
                                                    $nilaiKriteria = is_numeric($kriteria->pencapaian_nilai_kriteria)
                                                        ? (int) $kriteria->pencapaian_nilai_kriteria
                                                        : 0;

                                                    $bobot = is_numeric($header->bobot) ? (float) $header->bobot : 0;
                                                    $pembagi = is_numeric($totalPembagi) && $totalPembagi != 0 ? (float) $totalPembagi : 0;

                                                    $nilaiElemen = $pembagi > 0
                                                        ? ($nilaiKriteria * $bobot) / $pembagi
                                                        : 0;

                                                    switch ($nilaiKriteria) {
                                                        case 2:
                                                            $bgColor = '#28a745'; // green
                                                            $textColor = '#fff';
                                                            break;
                                                        case 1:
                                                            $bgColor = '#ffc107'; // yellow
                                                            $textColor = '#000';
                                                            break;
                                                        default:
                                                            $bgColor = '#dc3545'; // red (0 or null)
                                                            $textColor = '#fff';
                                                            break;
                                                    }
                                                @endphp
                                                <form action="{{ route('auditor.audit-smp-score.update-achievement', $kriteria->id ?? 0) }}" method="POST" id="form-kriteria-{{ $kriteria->id ?? 'new' }}" onsubmit="confirmSave('form-kriteria-{{ $kriteria->id ?? 'new' }}', 'Data akan disimpan')">
                                                    @csrf
                                                    @method('PUT')
                                                    <td rowspan="{{ $evidenceCount }}" style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
                                                        <input type="text" name="pencapaian_nilai_kriteria_{{ $kriteria->id }}" value="{{ old('pencapaian_nilai_kriteria_' . $kriteria->id, $kriteria->pencapaian_nilai_kriteria ?? '') }}" class="form-control" style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </td>
                                                </form>

                                                <td rowspan="{{ $evidenceCount }}">
                                                    {{ number_format($nilaiElemen, 2) }}%
                                                </td>
                                                @php 
                                                $isFirstKriteriaRow = false; 
                                                $subTotalElemen = $subTotalElemen + $nilaiElemen; 
                                                @endphp
                                            @endif

                                            {{-- EVIDENCE --}}
                                            <td>{{ $evidence->name ?? '-' }}</td>
                                            @php
                                                if($evidence != null){
                                                    $evidenceId = $evidence->id;
                                                }else{
                                                    $evidenceId = 'new';
                                                }
                                            @endphp
                                            <form action="{{ route('auditor.audit-smp-score.update', $evidence->id ?? 0) }}" method="POST" id="form-evidence-{{ $evidence->id ?? 'new' }}" onsubmit="confirmSave('form-evidence-{{ $evidence->id ?? 'new' }}', 'Data akan disimpan')">
                                                @csrf
                                                @method('PUT')
                                                <td>
                                                    <input type="text" name="temuan_{{ $evidenceId }}" value="{{ old('temuan_' . $evidenceId, $evidence->temuan ?? '') }}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="rekomendasi_{{ $evidenceId }}" value="{{ old('rekomendasi_' . $evidenceId, $evidence->rekomendasi ?? '') }}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="date" name="due_date_{{ $evidenceId }}" value="{{ old('due_date_' . $evidenceId, isset($evidence->due_date) ? \Carbon\Carbon::parse($evidence->due_date)->format('Y-m-d') : '') }}" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="pic_{{ $evidenceId }}" value="{{ old('pic_' . $evidenceId, $evidence->pic ?? '') }}" class="form-control">
                                                </td>
                                                <td>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </td>
                                            </form>
                                        </tr>
                                    @endforeach

                                @endforeach

                                {{-- =========================
                                    PERNYATAAN
                                ========================== --}}
                                @foreach ($header->pernyataan as $pernyataan)

                                    @php $countPernyataan++; @endphp

                                    <tr>
                                        {{-- KOLOM A & B tetap rowspan (tidak diisi lagi) --}}
                                        {{-- KOLOM A & B --}}
                                        @if ($isFirstHeaderRow)
                                            <td rowspan="{{ $totalRows }}">{{ $header->name }}</td>
                                            <td rowspan="{{ $totalRows }}">{{ $header->bobot }}%</td>
                                            @php $isFirstHeaderRow = false; @endphp
                                        @endif

                                        {{-- KOLOM C - F untuk judul pernyataan --}}
                                        <td colspan="4" style="text-align:left; font-weight:bold; background:#D3D3D3;">
                                            {{ $loop->parent->iteration }}.{{ chr(64 + $countPernyataan) }}. 
                                            {{ $pernyataan->name }}
                                        </td>

                                        <td colspan="6"></td>
                                    </tr>

                                    {{-- KRITERIA DALAM PERNYATAAN --}}
                                    @php 
                                    $countKriteria = 0;
                                    $subTotal = 0;
                                     @endphp

                                    @foreach ($pernyataan->kriteria as $kriteria)

                                        @php
                                            $countKriteria++;
                                            $evidenceCount = max(1, $kriteria->evidence->count());
                                            $isFirstKriteriaRow = true;

                                            $evidences = $kriteria->evidence->count()
                                                ? $kriteria->evidence
                                                : collect([null]);
                                        @endphp

                                        @foreach ($evidences as $evidence)
                                            <tr>
                                                {{-- KOLOM C - F --}}
                                                @if ($isFirstKriteriaRow)
                                                    <td rowspan="{{ $evidenceCount }}">
                                                        {{ $loop->parent->parent->parent->iteration }}.{{ chr(64 + $countPernyataan) }}.{{ $countKriteria }}
                                                    </td>
                                                    <td rowspan="{{ $evidenceCount }}" style="text-align:left">
                                                        {{ $kriteria->name }}
                                                    </td>
                                                    @php
                                                        $nilaiKriteria = is_numeric($kriteria->pencapaian_nilai_kriteria)
                                                            ? (int) $kriteria->pencapaian_nilai_kriteria
                                                            : 0;

                                                        $bobot = is_numeric($header->bobot) ? (float) $header->bobot : 0;
                                                        $pembagi = is_numeric($totalPembagi) && $totalPembagi != 0 ? (float) $totalPembagi : 0;

                                                        $nilaiElemen = $pembagi > 0
                                                            ? ($nilaiKriteria * $bobot) / $pembagi
                                                            : 0;

                                                        switch ($nilaiKriteria) {
                                                            case 2:
                                                                $bgColor = '#28a745'; // green
                                                                $textColor = '#fff';
                                                                break;
                                                            case 1:
                                                                $bgColor = '#ffc107'; // yellow
                                                                $textColor = '#000';
                                                                break;
                                                            default:
                                                                $bgColor = '#dc3545'; // red (0 or null)
                                                                $textColor = '#fff';
                                                                break;
                                                        }
                                                    @endphp

                                                    <form action="{{ route('auditor.audit-smp-score.update-achievement', $kriteria->id ?? 0) }}" method="POST" id="form-kriteria-{{ $kriteria->id ?? 'new' }}" onsubmit="confirmSave('form-kriteria-{{ $kriteria->id ?? 'new' }}', 'Data akan disimpan')">
                                                        @csrf
                                                        @method('PUT')
                                                        <td rowspan="{{ $evidenceCount }}" style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
                                                            <input type="text" name="pencapaian_nilai_kriteria_{{ $kriteria->id }}" value="{{ old('pencapaian_nilai_kriteria_' . $kriteria->id, $kriteria->pencapaian_nilai_kriteria ?? '') }}" class="form-control" style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
                                                            <button type="submit" class="btn btn-primary">Save</button>
                                                        </td>
                                                    </form>

                                                    <td rowspan="{{ $evidenceCount }}">
                                                        {{ number_format($nilaiElemen, 2) }}%
                                                    </td>
                                                    @php 
                                                    $isFirstKriteriaRow = false; 
                                                    $subTotalElemen = $subTotalElemen + $nilaiElemen;
                                                    $subTotal = $subTotal + $nilaiElemen;
                                                    @endphp
                                                @endif

                                                {{-- KOLOM G --}}
                                                <td>{{ $evidence->name ?? '-' }}</td>
                                                @php
                                                    if($evidence != null){
                                                        $evidenceId = $evidence->id;
                                                    }else{
                                                        $evidenceId = 'new';
                                                    }
                                                @endphp
                                                <form action="{{ route('auditor.audit-smp-score.update', $evidence->id ?? 0) }}" method="POST" id="form-evidence-{{ $evidence->id ?? 'new' }}" onsubmit="confirmSave('form-evidence-{{ $evidence->id ?? 'new' }}', 'Data akan disimpan')">
                                                    @csrf
                                                    @method('PUT')
                                                    <td>
                                                        <input type="text" name="temuan_{{ $evidenceId }}" value="{{ old('temuan_' . $evidenceId, $evidence->temuan ?? '') }}" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="rekomendasi_{{ $evidenceId }}" value="{{ old('rekomendasi_' . $evidenceId, $evidence->rekomendasi ?? '') }}" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="date" name="due_date_{{ $evidenceId }}" value="{{ old('due_date_' . $evidenceId, isset($evidence->due_date) ? \Carbon\Carbon::parse($evidence->due_date)->format('Y-m-d') : '') }}" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="pic_{{ $evidenceId }}" value="{{ old('pic_' . $evidenceId, $evidence->pic ?? '') }}" class="form-control">
                                                    </td>
                                                    <td>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </td>
                                                </form>
                                            </tr>
                                        @endforeach

                                    @endforeach
                                    
                                    @if(count($pernyataan->kriteria) > 0)
                                    <tr>
                                        <td colspan="3" style="text-align:left; background:#5DADE2;">
                                            SubTotal
                                        </td>
                                        <td colspan="1" style="text-align:left; background:#5DADE2;">
                                            {{ number_format($subTotal, 2) }}%
                                        </td>
                                        <td colspan="6"></td>
                                    </tr>
                                    @endif

                                @endforeach
                                <tr>
                                    <td colspan="3" style="text-align:left; font-weight:bold; background:#5DADE2;">
                                        SubTotal Elemen
                                    </td>
                                    <td colspan="1" style="text-align:left; font-weight:bold; background:#5DADE2;">
                                        {{ number_format($subTotalElemen, 2) }}%
                                    </td>
                                    <td colspan="6"></td>
                                </tr>
                                @php
                                    $totalAllHeader += $subTotalElemen;
                                @endphp
                            @endforeach

                            <tr>
                                <td colspan="12"></td>
                            </tr>
                            <tr>
                                <td colspan="5" style="text-align:left; font-weight:bold; background:#5DADE2;">
                                    Total
                                </td>
                                <td colspan="1" style="text-align:left; font-weight:bold; background:#5DADE2;">
                                    {{ number_format($totalAllHeader, 2) }}%
                                </td>
                                <td colspan="6"></td>
                            </tr>
                            </tbody>
                    </table>
                    @endif
                </div>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

