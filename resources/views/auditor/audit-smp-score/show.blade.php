@extends('layout.app')
@section('styles')
@stop

@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
  <div class="flex-grow-1 d-flex align-items-center gap-2">
    <a href="{{ route('auditor.audit-smp-score.index') }}" class="text-muted text-decoration-none">
      <i data-feather="arrow-left" style="width:18px;height:18px;"></i>
    </a>
    <h4 class="fs-18 fw-semibold m-0">Data Audit {{ $auditData->unit->name }}</h4>
  </div>

  <div class="text-end">
    <ol class="breadcrumb m-0 py-0">
      <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{route('auditor.audit-smp-score.index')}}">Data Audit SMP</a></li>
      <li class="breadcrumb-item active">Detail</li>
    </ol>
  </div>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">

        <div class="table-responsive">

          @if($auditData->childrenHeader->count() == 0)
          <div class="text-center">
            <p class="mb-0">Tidak ada data audit.</p>
          </div>
          @else

          @php
              $grandTotalAudit = 0;
              $grandTotalSelf = 0;
          @endphp

          <table class="table table-bordered text-center align-middle">

            <thead class="text-white text-center align-middle" style="background-color:#5DADE2;">
              <tr>
                <th rowspan="2">Elemen</th>
                <th rowspan="2">Bobot Elemen</th>
                <th colspan="2" rowspan="2">Kriteria</th>

                {{-- SELF AUDIT --}}
                <th colspan="2">Self Audit</th>

                {{-- AUDIT --}}
                <th colspan="2">Audit</th>

                <th rowspan="2">Evidence</th>
                <th rowspan="2">File Evidence</th>
                <th rowspan="2">Temuan</th>
                <th rowspan="2">Rekomendasi</th>
                <th rowspan="2">Due Date</th>
                <th rowspan="2">PIC</th>
              </tr>
              <tr>
                <th>Nilai</th>
                <th>Elemen</th>
                <th>Pencapaian Kriteria</th>
                <th>Pencapaian Nilai Elemen</th>
              </tr>
            </thead>

            <tbody>

              @foreach ($auditData->childrenHeader as $header)

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
                        $nilaiKriteria = (int)($kriteria->pencapaian_nilai_kriteria ?? 0);
                        $nilaiSelf = (int)($kriteria->pencapaian_nilai_kriteria_self ?? 0);

                        $nilaiElemen = ($nilaiKriteria * $header->bobot) / $pembagi;
                        $nilaiElemenSelf = ($nilaiSelf * $header->bobot) / $pembagi;

                        $subAudit += $nilaiElemen;
                        $subSelf += $nilaiElemenSelf;

                        $evidences = $kriteria->evidence->count()
                            ? $kriteria->evidence
                            : collect([null]);

                        $bgAudit = $nilaiKriteria == 2 ? '#28a745' : ($nilaiKriteria == 1 ? '#ffc107' : '#dc3545');
                        $bgSelf = $nilaiSelf == 2 ? '#28a745' : ($nilaiSelf == 1 ? '#ffc107' : '#dc3545');
                    @endphp

                    @foreach($evidences as $evidence)
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

                            {{-- SELF (read-only, diisi oleh unit) --}}
                            <td rowspan="{{ count($evidences) }}" style="background:{{ $bgSelf }};color:white;">
                                {{ $nilaiSelf }}
                            </td>
                            <td rowspan="{{ count($evidences) }}">
                                {{ number_format($nilaiElemenSelf,2) }}%
                            </td>

                            {{-- ACHIEVEMENT --}}
                            @if ($auditData->status != 2)

                            <td rowspan="{{ count($evidences) }}"
                                style="background-color: {{ $bgAudit }}; color: {{ $bgAudit=='#ffc107'?'#000':'#fff' }};">
                                {{ $kriteria->pencapaian_nilai_kriteria ?? '' }}
                            </td>

                            @else

                            {{-- AUDIT ACHIEVEMENT (form tetap seperti existing) --}}
                            <td rowspan="{{ count($evidences) }}"
                                id="audit-cell-{{ $kriteria->id }}"
                                style="background-color: {{ $bgAudit }}; min-width:220px;">

                                <form class="ajax-achievement-form"
                                      id="achievement-form-{{ $kriteria->id }}"
                                      action="{{ route('auditor.audit-smp-score.update-achievement', $kriteria->id ?? 0) }}"
                                      method="POST"
                                      data-header-id="{{ $header->id }}"
                                      data-bobot="{{ $header->bobot }}"
                                      data-pembagi="{{ $pembagi }}">

                                    @csrf
                                    @method('PUT')

                                    <div style="display:flex; gap:6px; align-items:start; flex-direction:column;">

                                        <select name="pencapaian_nilai_kriteria_{{ $kriteria->id }}"
                                                class="form-select form-select-sm">

                                            <option value="0" {{ (int)$kriteria->pencapaian_nilai_kriteria === 0 ? 'selected' : '' }}>0</option>
                                            <option value="1" {{ (int)$kriteria->pencapaian_nilai_kriteria === 1 ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ (int)$kriteria->pencapaian_nilai_kriteria === 2 ? 'selected' : '' }}>2</option>

                                        </select>

                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                            💾 Save
                                        </button>

                                    </div>

                                    <div id="error-pencapaian_nilai_kriteria_{{ $kriteria->id }}"
                                        class="text-danger mt-1"
                                        style="font-size:11px;">
                                    </div>

                                </form>

                            </td>

                            @endif

                            <td rowspan="{{ count($evidences) }}"
                                id="nilai-elemen-audit-{{ $kriteria->id }}"
                                class="nilai-elemen-audit"
                                data-header-id="{{ $header->id }}"
                                data-value="{{ $nilaiElemen }}">
                                {{ number_format($nilaiElemen,2) }}%
                            </td>

                        @endif

                        <td>{{ $evidence->name ?? '-' }}</td>

                        <td>
                            @if(isset($evidence->evidence_file) && $evidence->evidence_file != '')
                            <a href="{{ asset('uploads/evidence_file/' . $evidence->evidence_file) }}" target="_blank" class="btn btn-primary btn-sm">Lihat File</a>
                            @else
                            -
                            @endif
                        </td>

                        {{-- TEMUAN / REKOMENDASI / DUE DATE / PIC (form tetap seperti existing) --}}
                        @if($auditData->status == 2)

                        <td style="min-width:220px;">
                            <form class="ajax-evidence-form"
                                  id="evidence-form-{{ $evidence->id ?? 0 }}"
                                  action="{{ route('auditor.audit-smp-score.update', $evidence->id ?? 0) }}"
                                  method="POST">

                                @csrf
                                @method('PUT')

                                <textarea name="temuan_{{ $evidence->id ?? 0 }}"
                                          class="form-control form-control-sm"
                                          placeholder="Temuan">{{ $evidence->temuan ?? '' }}</textarea>

                                <div id="error-temuan_{{ $evidence->id ?? 0 }}" class="text-danger" style="font-size:11px;"></div>

                        </td>

                        <td style="min-width:220px;">
                                <textarea name="rekomendasi_{{ $evidence->id ?? 0 }}"
                                          class="form-control form-control-sm"
                                          placeholder="Rekomendasi">{{ $evidence->rekomendasi ?? '' }}</textarea>

                                <div id="error-rekomendasi_{{ $evidence->id ?? 0 }}" class="text-danger" style="font-size:11px;"></div>
                        </td>

                        <td style="min-width:170px;">
                                <input type="date"
                                      name="due_date_{{ $evidence->id ?? 0 }}"
                                      class="form-control form-control-sm"
                                      value="{{ $evidence->due_date ?? '' }}">

                                <div id="error-due_date_{{ $evidence->id ?? 0 }}" class="text-danger" style="font-size:11px;"></div>
                        </td>

                        <td style="min-width:170px;">
                                <input type="text"
                                      name="pic_{{ $evidence->id ?? 0 }}"
                                      class="form-control form-control-sm"
                                      placeholder="PIC"
                                      value="{{ $evidence->pic ?? '' }}">

                                <div id="error-pic_{{ $evidence->id ?? 0 }}" class="text-danger" style="font-size:11px;"></div>

                                <button type="submit" class="btn btn-success btn-sm mt-1 w-100">
                                    💾 Save
                                </button>

                            </form>
                        </td>

                        @else

                        <td>{{ $evidence->temuan ?? '-' }}</td>
                        <td>{{ $evidence->rekomendasi ?? '-' }}</td>
                        <td>{{ isset($evidence->due_date) ? \Carbon\Carbon::parse($evidence->due_date)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $evidence->pic ?? '-' }}</td>

                        @endif

                    </tr>
                    @endforeach
                @endforeach

                <tr style="background:#5DADE2;color:white;">
                    <td colspan="4">SubTotal Elemen</td>
                    <td>{{ number_format($subSelf,2) }}%</td>
                    <td></td>
                    <td id="subtotal-audit-{{ $header->id }}">{{ number_format($subAudit,2) }}%</td>
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
                  <td id="grand-total-audit">{{ number_format($grandTotalAudit,2) }}%</td>
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
                  <td id="kategori-audit-cell" style="background:{{ $colorAudit }};color:white;">{{ $kategoriAudit }}</td>
                  <td></td>
                  <td colspan="6"></td>
              </tr>

            </tbody>
          </table>

          @endif

        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>

// ======================================
// HELPER: warna & kategori
// ======================================
function getScoreBgColor(val) {
    return val == 2 ? '#28a745' : (val == 1 ? '#ffc107' : '#dc3545');
}

function getKategori(total) {
    if (total < 55) return { label: 'Kurang', color: '#dc3545' };
    if (total <= 70) return { label: 'Cukup', color: '#ffc107' };
    if (total <= 85) return { label: 'Baik', color: '#28a745' };
    return { label: 'Baik Sekali', color: '#198754' };
}

// ======================================
// RECALC SUBTOTAL/TOTAL/KATEGORI (AUDIT)
// ======================================
function recalcAuditTotals(headerId) {
    let subtotal = 0;
    document.querySelectorAll(`.nilai-elemen-audit[data-header-id="${headerId}"]`).forEach(el => {
        subtotal += parseFloat(el.dataset.value) || 0;
    });

    const subtotalEl = document.getElementById('subtotal-audit-' + headerId);
    if (subtotalEl) subtotalEl.textContent = subtotal.toFixed(2) + '%';

    let grandTotal = 0;
    document.querySelectorAll('[id^="subtotal-audit-"]').forEach(el => {
        grandTotal += parseFloat(el.textContent) || 0;
    });

    const grandTotalEl = document.getElementById('grand-total-audit');
    if (grandTotalEl) grandTotalEl.textContent = grandTotal.toFixed(2) + '%';

    const kategori = getKategori(grandTotal);
    const kategoriEl = document.getElementById('kategori-audit-cell');
    if (kategoriEl) {
        kategoriEl.textContent = kategori.label;
        kategoriEl.style.background = kategori.color;
    }
}

// ======================================
// CLEAR ERROR
// ======================================
function clearErrors(form)
{
    if (!form) return;

    form.querySelectorAll("[id^='error-']").forEach(el => {
        el.innerHTML = '';
    });

    form.querySelectorAll("input, textarea, select").forEach(el => {
        el.style.border = '';
    });
}

// ======================================
// SHOW ERROR
// ======================================
function showErrors(form, errors)
{
    Object.keys(errors).forEach(name => {

        let message = errors[name][0];

        let errorDiv = document.getElementById("error-" + name);

        if (errorDiv) {
            errorDiv.innerHTML = "⚠ " + message;
        }

        let input = form.querySelector(`[name="${name}"]`);

        if (input) {
            input.style.border = "1px solid red";
        }

    });
}

// ======================================
// AJAX SUBMIT
// ======================================
async function submitAjax(form, btn)
{
    clearErrors(form);

    let formData = new FormData(form);

    let originalText = btn.innerHTML;

    // =========================
    // LOADING BUTTON
    // =========================
    btn.disabled = true;

    btn.innerHTML = `
        <span class="spinner-border spinner-border-sm"></span>
        Saving...
    `;

    try {

        let response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                'Accept': 'application/json'
            }
        });

        let result;

        try {

            result = await response.json();

        } catch (e) {

            Swal.fire({
                icon: 'error',
                title: 'Response Invalid',
                text: 'Server tidak mengembalikan JSON'
            });

            btn.disabled = false;
            btn.innerHTML = originalText;

            return;
        }

        // =========================
        // VALIDATION ERROR
        // =========================
        if (!response.ok) {

            if (result.errors) {
                showErrors(form, result.errors);
            }

            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: result.message ?? 'Terjadi kesalahan'
            });

            btn.disabled = false;
            btn.innerHTML = originalText;

            return;
        }

        // =========================
        // UPDATE WARNA & PERHITUNGAN ELEMEN (khusus form achievement)
        // =========================
        if (result.data && result.data.pencapaian_nilai_kriteria !== undefined) {

            const newVal     = parseInt(result.data.pencapaian_nilai_kriteria);
            const kriteriaId = result.data.id;
            const headerId   = form.dataset.headerId;
            const bobot      = parseFloat(form.dataset.bobot);
            const pembagi    = parseFloat(form.dataset.pembagi);

            const cell = document.getElementById('audit-cell-' + kriteriaId);
            if (cell) cell.style.background = getScoreBgColor(newVal);

            const nilaiElemen = (newVal * bobot) / pembagi;
            const elemenEl = document.getElementById('nilai-elemen-audit-' + kriteriaId);
            if (elemenEl) {
                elemenEl.dataset.value = nilaiElemen;
                elemenEl.textContent = nilaiElemen.toFixed(2) + '%';
            }

            recalcAuditTotals(headerId);
        }

        // =========================
        // SUCCESS
        // =========================
        Swal.fire({
            icon: 'success',
            title: 'Berhasil disimpan',
            timer: 1200,
            showConfirmButton: false
        });

        btn.innerHTML = "✔ Saved";

        setTimeout(() => {

            btn.disabled = false;
            btn.innerHTML = originalText;

        }, 1200);

    } catch (err) {

        console.error(err);

        Swal.fire({
            icon: 'error',
            title: 'Server Error',
            text: 'Terjadi kesalahan server'
        });

        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

// ======================================
// INIT
// ======================================
document.addEventListener("DOMContentLoaded", function () {

    // ==================================
    // ACHIEVEMENT FORM
    // ==================================
    document.querySelectorAll('.ajax-achievement-form').forEach(form => {

        form.addEventListener('submit', function(e){

            e.preventDefault();

            let btn = e.submitter;

            Swal.fire({
                title: 'Simpan achievement?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal'
            }).then((res) => {

                if (res.isConfirmed) {
                    submitAjax(form, btn);
                }

            });

        });

    });

    // ==================================
    // EVIDENCE FORM
    // ==================================
    document.querySelectorAll('.ajax-evidence-form').forEach(form => {

        form.addEventListener('submit', function(e){

            e.preventDefault();

            let btn = e.submitter;

            Swal.fire({
                title: 'Simpan evidence?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal'
            }).then((res) => {

                if (res.isConfirmed) {
                    submitAjax(form, btn);
                }

            });

        });

    });

});
</script>
@endsection
