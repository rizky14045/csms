@extends('layout.app')
@section('styles')
@stop

@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
  <div class="flex-grow-1">
    <h4 class="fs-18 fw-semibold m-0">Data Audit {{ $auditData->unit->name }}</h4>
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

                @if($auditData->status == 2)
                <th rowspan="2">Action</th>
                @endif
              </tr>
              <tr>
                <th>Nilai</th>
                <th>Elemen</th>
                <th>Pencapaian Kriteria</th>
                <th>Pencapaian Nilai Elemen</th>
              </tr>
            </thead>

            <tbody>

              @php $totalAllHeader = 0; @endphp

              @foreach ($auditData->childrenHeader as $header)

              @php
              $countKriteria = 0;
              $countPernyataan = 0;
              $totalRows = 0;

              foreach ($header->kriteria as $k) {
              $totalRows += max(1, $k->evidence->count());
              }
              foreach ($header->pernyataan as $p) {
              $totalRows += 1;
              foreach ($p->kriteria as $k) {
              $totalRows += max(1, $k->evidence->count());
              }
              }

              $totalPembagi = ($header->kriteria->count() +
              $header->pernyataan->flatMap->kriteria->count()) * 2;

              $totalRows += 1 + count($header->pernyataan);

              $isFirstHeaderRow = true;
              $subTotalElemen = 0;
              @endphp

              {{-- ================= KRITERIA ================= --}}
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

                @if ($isFirstHeaderRow)
                <td rowspan="{{ $totalRows }}">{{ $header->name }}</td>
                <td rowspan="{{ $totalRows }}">{{ $header->bobot }}%</td>
                @php $isFirstHeaderRow = false; @endphp
                @endif

                @if ($isFirstKriteriaRow)

                <td rowspan="{{ $evidenceCount }}">
                  {{ $loop->parent->parent->iteration }}.{{ $countKriteria }}
                </td>

                <td rowspan="{{ $evidenceCount }}" style="text-align:left">
                  {{ $kriteria->name }}
                </td>

                @php
                $nilaiKriteria = (int)($kriteria->pencapaian_nilai_kriteria ?? 0);
                $nilaiSelf = (int)($kriteria->pencapaian_nilai_kriteria_self ?? 0);

                $bobot = (float)$header->bobot;
                $pembagi = $totalPembagi ?: 1;

                $nilaiElemen = ($nilaiKriteria * $bobot) / $pembagi;
                $nilaiElemenSelf = ($nilaiSelf * $bobot) / $pembagi;

                $bgAudit = $nilaiKriteria == 2 ? '#28a745' : ($nilaiKriteria == 1 ? '#ffc107' : '#dc3545');
                $bgSelf = $nilaiSelf == 2 ? '#28a745' : ($nilaiSelf == 1 ? '#ffc107' : '#dc3545');
                @endphp

                {{-- SELF --}}
                <td rowspan="{{ $evidenceCount }}" style="background:{{ $bgSelf }};color:white;">
                  {{ $nilaiSelf }}
                </td>
                <td rowspan="{{ $evidenceCount }}">
                  {{ number_format($nilaiElemenSelf,2) }}%
                </td>

                {{-- ACHIEVEMENT --}}
                {{-- ============================== --}}
                @if ($auditData->status != 2)

                <td rowspan="{{ $evidenceCount }}"
                    style="background-color: {{ $bgAudit }}; color: {{ $bgAudit=='#ffc107'?'#000':'#fff' }};">

                    {{ $kriteria->pencapaian_nilai_kriteria ?? '' }}

                </td>

                @else

                {{-- AUDIT ACHIEVEMENT --}}
                <td rowspan="{{ $evidenceCount }}"
                    style="background-color: {{ $bgAudit }}; min-width:220px;">

                    <form class="ajax-achievement-form"
                          id="achievement-form-{{ $kriteria->id }}"
                          action="{{ route('auditor.audit-smp-score.update-achievement', $kriteria->id ?? 0) }}"
                          method="POST">

                        @csrf
                        @method('PUT')

                        <div style="display:flex; gap:6px; align-items:start; flex-direction:column;">

                            <select name="pencapaian_nilai_kriteria_{{ $kriteria->id }}"
                                    class="form-select form-select-sm">

                                <option value="0"
                                    {{ (int)$kriteria->pencapaian_nilai_kriteria === 0 ? 'selected' : '' }}>
                                    0
                                </option>

                                <option value="1"
                                    {{ (int)$kriteria->pencapaian_nilai_kriteria === 1 ? 'selected' : '' }}>
                                    1
                                </option>

                                <option value="2"
                                    {{ (int)$kriteria->pencapaian_nilai_kriteria === 2 ? 'selected' : '' }}>
                                    2
                                </option>

                            </select>

                            <button type="submit"
                                    class="btn btn-success btn-sm w-100">
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

                <td rowspan="{{ $evidenceCount }}">
                  {{ number_format($nilaiElemen,2) }}%
                </td>

                @php $subTotalElemen += $nilaiElemen; $isFirstKriteriaRow = false; @endphp
                @endif

                <td>{{ $evidence->name ?? '-' }}</td>

                <td>
                  @if(isset($evidence->evidence_file))
                  <a href="/uploads/evidence_file/{{ $evidence->evidence_file }}" target="_blank">Lihat</a>
                  @else - @endif
                </td>

                {{-- EVIDENCE --}}
                {{-- ============================== --}}
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

                        <div id="error-temuan_{{ $evidence->id ?? 0 }}"
                            class="text-danger"
                            style="font-size:11px;"></div>

                </td>

                <td style="min-width:220px;">

                        <textarea name="rekomendasi_{{ $evidence->id ?? 0 }}"
                                  class="form-control form-control-sm"
                                  placeholder="Rekomendasi">{{ $evidence->rekomendasi ?? '' }}</textarea>

                        <div id="error-rekomendasi_{{ $evidence->id ?? 0 }}"
                            class="text-danger"
                            style="font-size:11px;"></div>

                </td>

                <td style="min-width:170px;">

                        <input type="date"
                              name="due_date_{{ $evidence->id ?? 0 }}"
                              class="form-control form-control-sm"
                              value="{{ $evidence->due_date ?? '' }}">

                        <div id="error-due_date_{{ $evidence->id ?? 0 }}"
                            class="text-danger"
                            style="font-size:11px;"></div>

                </td>

                <td style="min-width:170px;">

                        <input type="text"
                              name="pic_{{ $evidence->id ?? 0 }}"
                              class="form-control form-control-sm"
                              placeholder="PIC"
                              value="{{ $evidence->pic ?? '' }}">

                        <div id="error-pic_{{ $evidence->id ?? 0 }}"
                            class="text-danger"
                            style="font-size:11px;"></div>

                </td>

                <td style="min-width:120px;">

                        <button type="submit"
                                class="btn btn-success btn-sm">
                            💾 Save
                        </button>

                    </form>

                </td>

                @else

                <td>
                    {{ $evidence->temuan ?? '-' }}
                </td>

                <td>
                    {{ $evidence->rekomendasi ?? '-' }}
                </td>

                <td>
                    {{ isset($evidence->due_date)
                        ? \Carbon\Carbon::parse($evidence->due_date)->format('d-m-Y')
                        : '-' }}
                </td>

                <td>
                    {{ $evidence->pic ?? '-' }}
                </td>

                @endif

              </tr>
              @endforeach
              @endforeach

              {{-- SUBTOTAL --}}
              <tr>
                <td colspan="6" style="background:#5DADE2;">SubTotal Elemen</td>
                <td>{{ number_format($subTotalElemen,2) }}%</td>
                <td colspan="8"></td>
              </tr>

              @php $totalAllHeader += $subTotalElemen; @endphp

              @endforeach

              <tr>
                <td colspan="6" style="background:#5DADE2;">Total</td>
                <td>{{ number_format($totalAllHeader,2) }}%</td>
                <td colspan="8"></td>
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