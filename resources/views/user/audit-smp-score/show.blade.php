@extends('layout.app')

@section('styles')
<style>
    .error-text {
        font-size: 12px;
        color: #dc3545;
        margin-top: 4px;
    }
</style>
@stop

@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1 d-flex align-items-center gap-2">
        <a href="{{ route('user.audit-smp-score.index') }}" class="text-muted text-decoration-none">
            <i data-feather="arrow-left" style="width:18px;height:18px;"></i>
        </a>
        <h4 class="fs-18 fw-semibold m-0">Data Audit {{ $auditData->unit->name }}</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.audit-smp-score.index') }}">Data Audit SMP</a></li>
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

                                {{-- SELF AUDIT --}}
                                <td rowspan="{{ count($evidences) }}"
                                    style="background:{{ $bgSelf }};color:white;">

                                    @if($auditData->status == 0)

                                    <form class="ajax-form"
                                          action="{{ route('user.audit-smp-score.update-self-audit',$kriteria->id) }}"
                                          method="POST">

                                        @csrf
                                        @method('PUT')

                                        <div class="d-flex gap-2 align-items-center">
                                            <select name="pencapaian_nilai_kriteria_self_{{ $kriteria->id }}"
                                                    class="form-select form-select-sm">

                                                <option value="0" {{ $nilaiSelf==0?'selected':'' }}>0</option>
                                                <option value="1" {{ $nilaiSelf==1?'selected':'' }}>1</option>
                                                <option value="2" {{ $nilaiSelf==2?'selected':'' }}>2</option>

                                            </select>

                                            <button type="submit"
                                                    class="btn btn-success btn-sm">
                                                💾
                                            </button>
                                        </div>

                                    </form>

                                    @else
                                        {{ $nilaiSelf }}
                                    @endif
                                </td>

                                <td rowspan="{{ count($evidences) }}">
                                    {{ number_format($nilaiElemenSelf,2) }}%
                                </td>

                                {{-- AUDIT --}}
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

                            {{-- FILE --}}
                            <td style="min-width:250px;">

                                @if($auditData->status == 0)

                                <div id="upload-file-{{ $evidence->id ?? 0 }}"
                                     style="display:flex; flex-direction:column; gap:6px;">

                                    @if(isset($evidence->evidence_file) && $evidence->evidence_file != '')
                                        <a href="{{ asset('uploads/evidence_file/' . $evidence->evidence_file) }}"
                                           target="_blank"
                                           class="btn btn-success btn-sm">
                                            ⬇ Download File
                                        </a>

                                        <label style="font-size:12px;">
                                            Ganti File:
                                        </label>
                                    @endif

                                    <form class="ajax-form-file"
                                          id="form-upload-{{ $evidence->id ?? 0 }}"
                                          action="{{ route('user.audit-smp-score.update',$evidence->id ?? 0) }}"
                                          method="POST"
                                          enctype="multipart/form-data">

                                        @csrf
                                        @method('PUT')

                                        <input type="file"
                                            name="evidence_file_{{ $evidence->id ?? 0 }}"
                                            class="form-control form-control-sm">

                                        <div class="error-text"
                                             id="error-file-{{ $evidence->id ?? 0 }}"></div>

                                    </form>

                                    <button type="button"
                                            class="btn btn-success btn-sm btn-upload"
                                            data-form="form-upload-{{ $evidence->id ?? 0 }}"
                                            data-id="{{ $evidence->id ?? 0 }}">
                                        💾 Upload
                                    </button>

                                </div>

                                @else
                                    -
                                @endif

                            </td>

                            <td>{{ $evidence->temuan ?? '-' }}</td>
                            <td>{{ $evidence->rekomendasi ?? '-' }}</td>

                        </tr>

                        @endforeach
                    @endforeach

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

                <tr style="background:#2E86C1;color:white;">
                    <td colspan="4">TOTAL</td>
                    <td>{{ number_format($grandTotalSelf,2) }}%</td>
                    <td></td>
                    <td>{{ number_format($grandTotalAudit,2) }}%</td>
                    <td></td>
                    <td colspan="4"></td>
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
                    <td colspan="4"></td>
                </tr>

                </tbody>
            </table>

            @endif

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>

// AJAX NORMAL FORM
document.querySelectorAll('.ajax-form').forEach(form => {

    form.addEventListener('submit', async function(e){

    e.preventDefault();

    Swal.fire({
        title: 'Simpan data?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Simpan'
    }).then(async (res) => {

        if(!res.isConfirmed) return;

        let btn = form.querySelector('button');
        let original = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '⏳';

        try {

            let formData = new FormData(form);

            let response = await fetch(form.action,{
                method:'POST',
                body:formData,
                headers:{
                    'X-CSRF-TOKEN':document.querySelector('input[name=_token]').value,
                    'Accept':'application/json'
                }
            });

            let result = await response.json();

            if(!response.ok){
                throw result;
            }

            Swal.fire({
                icon:'success',
                title:'Berhasil disimpan',
                timer:1000,
                showConfirmButton:false
            });

        } catch(err){

            Swal.fire(
                'Error',
                err.message || 'Gagal menyimpan',
                'error'
            );

        }

        btn.disabled = false;
        btn.innerHTML = original;

    });

});

});

// AJAX FILE
document.querySelectorAll('.btn-upload').forEach(btn => {

    btn.addEventListener('click', async function(){

        let formId = btn.dataset.form;
        let evidenceId = btn.dataset.id;

        let form = document.getElementById(formId);

        if(!form) return;

        let formData = new FormData(form);

        btn.disabled = true;
        btn.innerHTML = 'Uploading...';

        try {

            let res = await fetch(form.action,{
                method:'POST',
                body:formData,
                headers:{
                    'X-CSRF-TOKEN':document.querySelector('input[name=_token]').value,
                    'Accept':'application/json'
                }
            });

            let result = await res.json();

            if(!res.ok){

                let errorKey = 'evidence_file_' + evidenceId;

                if(result.errors?.[errorKey]){

                    document.getElementById('error-file-'+evidenceId)
                        .innerHTML = result.errors[errorKey][0];
                }

                btn.disabled = false;
                btn.innerHTML = '💾 Upload';
                return;
            }

            Swal.fire({
                icon:'success',
                title:'Upload berhasil',
                timer:1000,
                showConfirmButton:false
            });

            if(result.data?.evidence_file){

                let container = document.getElementById('upload-file-'+evidenceId);

                let fileUrl = "uploads/evidence_file/" + result.data.evidence_file;

                container.querySelector('a')?.remove();

                let link = document.createElement('a');

                link.href = fileUrl;
                link.target = '_blank';
                link.className = 'btn btn-success btn-sm';
                link.innerHTML = '⬇ Download File';

                container.prepend(link);
            }

        } catch(err){

            Swal.fire('Error','Upload gagal','error');

        }

        btn.disabled = false;
        btn.innerHTML = '💾 Upload';

    });

});

</script>
@endsection