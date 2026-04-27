@extends('layout.app')

@section('styles')
@stop

@section('content')

<div class="py-3 d-flex justify-content-between">
  <h4>Data Audit {{ $auditData->unit->name }}</h4>
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
          </tr>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Nilai</th>
            <th>Elemen</th>
            <th>Nilai</th>
            <th>Elemen</th>
          </tr>
        </thead>

        <tbody>

          @foreach($auditData->childrenHeader as $header)

          @php
          $allKriteria = collect();

          // gabung semua kriteria (langsung + pernyataan)
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

          // warna
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

            {{-- SELF --}}
            <td rowspan="{{ count($evidences) }}" style="background:{{ $bgSelf }};color:white;">
              @if($auditData->status == 0)
              <form method="POST" action="{{ route('user.audit-smp-score.update-self-audit',$kriteria->id) }}">
                @csrf
                @method('PUT')
                <select name="pencapaian_nilai_kriteria_self_{{ $kriteria->id }}">
                  <option value="0" {{ $nilaiSelf==0?'selected':'' }}>0</option>
                  <option value="1" {{ $nilaiSelf==1?'selected':'' }}>1</option>
                  <option value="2" {{ $nilaiSelf==2?'selected':'' }}>2</option>
                </select>
                <button class="btn btn-sm btn-success">Save</button>
              </form>
              @else
              {{ $nilaiSelf }}
              @endif
            </td>

            <td rowspan="{{ count($evidences) }}">
              {{ number_format($nilaiElemenSelf,2) }}%
            </td>

            {{-- AUDIT --}}
            <td rowspan="{{ count($evidences) }}" style="background:{{ $bgAudit }};color:white;">
              {{ $nilaiAudit }}
            </td>

            <td rowspan="{{ count($evidences) }}">
              {{ number_format($nilaiElemenAudit,2) }}%
            </td>
            @endif

            {{-- EVIDENCE --}}
            <td>{{ $evidence->name ?? '-' }}</td>

            <td>
              @if($auditData->status == 0)
              <form method="POST" action="{{ route('user.audit-smp-score.update',$evidence->id ?? 0) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="file" name="file" class="form-control mb-1">
                <button class="btn btn-sm btn-success">Save</button>
              </form>
              @else
              -
              @endif
            </td>

            <td>{{ $evidence->temuan ?? '-' }}</td>
            <td>{{ $evidence->rekomendasi ?? '-' }}</td>

          </tr>

          @endforeach
          @endforeach

          {{-- SUBTOTAL HEADER --}}
          <tr style="background:#5DADE2;color:white;">
            <td colspan="4">SubTotal Elemen</td>
            <td>{{ number_format($subSelf,2) }}%</td>
            <td></td>
            <td>{{ number_format($subAudit,2) }}%</td>
            <td></td>
            <td colspan="4"></td>
          </tr>

          @php
          $grandTotalAudit += $subAudit;
          $grandTotalSelf += $subSelf;
          @endphp

          @endforeach

          {{-- GRAND TOTAL --}}
          <tr style="background:#2E86C1;color:white;">
            <td colspan="4">TOTAL</td>
            <td>{{ number_format($grandTotalSelf,2) }}%</td>
            <td></td>
            <td>{{ number_format($grandTotalAudit,2) }}%</td>
            <td></td>
            <td colspan="4"></td>
          </tr>

        </tbody>
      </table>

      @endif

    </div>
  </div>
</div>

@endsection