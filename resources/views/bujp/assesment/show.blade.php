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
    <h4 class="fs-18 fw-semibold m-0">Assesment</h4>
  </div>

  <div class="text-end">
    <ol class="breadcrumb m-0 py-0">
      <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{route('bujp.assesment.index', ['unit' => request()->query('unit')])}}">Assesment</a></li>
      <li class="breadcrumb-item active">Detail</li>
    </ol>
  </div>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body">

        <div class="accordion" id="formAccordion">

          @foreach ($categories as $category)

          <div class="accordion-item">

            <h2 class="accordion-header bg-light">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$category->id}}">
                {{$category->category_name}}

                <span id="invalid-badge-{{ $category->id }}"
                      style="margin-left:8px; padding:4px; background:red; color:white; border-radius:4px; {{ $category->invalid_questions_count > 0 ? '' : 'display:none;' }}">
                  <span id="invalid-count-{{ $category->id }}">{{ $category->invalid_questions_count }}</span> belum diisi
                </span>
              </button>
            </h2>

            <div id="collapse{{$category->id}}" class="accordion-collapse collapse {{request('signCategoryId') == $category->id ? 'show' :''}}">

              <div class="accordion-body">

                <div class="table-responsive">
                  <table class="table table-bordered align-middle" style="white-space:nowrap;">

                    <thead class="table-light">
                      <tr>
                        <th style="min-width:60px;">No</th>
                        <th style="min-width:250px;">Indikator</th>
                        <th style="min-width:370px;">Level Penilaian</th>
                        <th style="min-width:150px;">Nilai</th>
                        <th style="min-width:200px;">Note</th>
                        <th style="min-width:260px;">File Upload</th>
                        @if($assesment->send_status == 3)
                        <th style="min-width:200px;">Note Revisi</th>
                        @endif
                        <th style="min-width:150px;">Action</th>
                      </tr>
                    </thead>

                    <tbody>

                      @foreach ($category->questions as $question)
                      @php
                        $questionFiles = $question->attachment_file ? (json_decode($question->attachment_file, true) ?: []) : [];
                        $isQuestionInvalid = empty($question->level) || empty($questionFiles);
                      @endphp
                      <tr data-category-id="{{ $category->id }}" data-invalid="{{ $isQuestionInvalid ? '1' : '0' }}">

                        <form action="{{ route('bujp.assesment.updateQuestion', ['question' => $question->id, 'unit' => request()->query('unit')]) }}"
                              method="POST"
                              enctype="multipart/form-data"
                              id="update-question-{{ $question->id }}">

                          @csrf
                          @method('PATCH')

                          <td>{{$loop->iteration}}</td>

                          <td>
                            {{$question->indicator}}
                            @if($question->note_revision)
                            <span style="margin-left:6px; padding:3px 6px; background:red; color:white; border-radius:4px;">
                              Revisi
                            </span>
                            @endif
                          </td>

                          <td style="text-align:left;">
                            @foreach ($question->levels as $level)
                            <div class="d-flex align-items-start" style="gap:8px; white-space:normal;">
                              <span class="fw-semibold flex-shrink-0">Level {{$level->level}}</span>
                              <span>{{$level->level_description}}</span>
                            </div>
                            @endforeach
                          </td>

                          <td>
                            <select class="form-select" name="level_{{$question->id}}" required>
                                <option value="">Pilih</option>
                                @for($i=1;$i<=5;$i++)
                                <option value="{{$i}}" {{$question->level == $i ? 'selected' : ''}}>{{$i}}</option>
                                @endfor
                            </select>

                            <!-- ERROR -->
                            <div id="error-level_{{$question->id}}"></div>
                        </td>

                          <td>
                            <input type="text"
                                class="form-control"
                                name="note_{{$question->id}}"
                                value="{{$question->note}}">

                            <!-- ERROR -->
                            <div id="error-note_{{$question->id}}"></div>
                        </td>

                          <td>
                            @php
                                $files = [];
                                if ($question->attachment_file) {
                                    $decoded = json_decode($question->attachment_file, true);
                                    $files = is_array($decoded) ? $decoded : [$question->attachment_file];
                                }
                            @endphp
                            <div style="display:flex; flex-direction:column; gap:6px;" id="upload-file">

                                @foreach($files as $file)
                                <a href="{{ asset('uploads/attachment_file_question_file/' . $file) }}"
                                class="btn btn-success btn-sm" target="_blank">
                                    ⬇ Download File
                                </a>
                                @endforeach

                                @if(count($files) > 0)
                                <label style="font-size:12px;">Ganti File:</label>
                                @endif

                                <input type="file"
                                    name="attachment_file_{{$question->id}}[]"
                                    class="form-control"
                                    accept=".pdf"
                                    multiple
                                    {{ count($files) === 0 ? 'required' : '' }}>

                                <div class="form-text text-muted" style="font-size:11px;">Format: PDF, maks 25MB per file</div>

                                <!-- ERROR -->
                                <div id="error-attachment_file_{{$question->id}}"></div>

                            </div>
                        </td>

                          @if ($assesment->send_status == 3)
                          <td>{{ $question->note_revision ?? "-" }}</td>
                          @endif

                          <td>
                            <button type="button" class="btn btn-primary btn-sm btn-update">
                              💾 Update
                            </button>
                          </td>

                        </form>
                      </tr>
                      @endforeach

                    </tbody>
                  </table>
                </div>

              </div>
            </div>
          </div>

          @endforeach

          <div class="mt-4 d-flex justify-content-end gap-2">
            <a href="{{route('bujp.assesment.index', ['unit' => request()->query('unit')])}}" class="btn btn-danger">
              Kembali
            </a>
            @php $allDone = count($assesment->getInvalidItemsQuestionByBujp) == 0; @endphp
            <form action="{{route('bujp.assesment.send',['assesment'=>$assesment->id, 'unit' => request()->query('unit')])}}" method="post" class="d-inline" id="send-assesment-{{ $assesment->id }}" onsubmit="confirmSave('send-assesment-{{ $assesment->id }}', 'Kirim assesment?')">
                @csrf
                @method('PATCH')
                <button type="submit" id="btn-kirim" class="btn {{ $allDone ? 'btn-success' : 'btn-secondary' }}" {{ $allDone ? '' : 'disabled style=background-color:gray;' }}>Kirim</button>
            </form>
          </div>

        </div>

      </div>
    </div>
  </div>
</div>

@endsection


@section('scripts')
<script>

// ================= CLEAR ERROR =================
function clearErrors(form) {

    // clear semua error div
    form.querySelectorAll("[id^='error-']").forEach(el => {
        el.innerHTML = '';
    });

    // reset border
    form.querySelectorAll("input, select, textarea").forEach(el => {
        el.style.border = '';
    });
}

// ================= SHOW ERROR =================
function showErrors(form, errors) {

    Object.keys(errors).forEach(name => {

        let message = errors[name][0];

        // Strip trailing array index (e.g. "attachment_file_5.0" → "attachment_file_5")
        let divName = name.replace(/\.\d+$/, '');

        let errorDiv = document.getElementById("error-" + divName);

        if (errorDiv) {
            errorDiv.innerHTML = `
                <div style="
                    color:#b30000;
                    font-size:12px;
                    margin-top:4px;
                ">
                    ⚠ ${message}
                </div>
            `;
        }

        let input = form.querySelector(`[name="${name}"]`)
                 || form.querySelector(`[name="${divName}[]"]`);
        if (input) {
            input.style.border = '1px solid red';
        }

    });
}

// ================= AJAX =================
async function submitAjax(form, button) {

    clearErrors(form);

    let formData = new FormData(form);
    let originalText = button.innerHTML;

    button.innerHTML = '⏳ Loading...';
    button.disabled = true;

    try {
        let response = await fetch(form.action, {
            method: "POST",
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                'Accept': 'application/json'
            }
        });

        let result = await response.json();

        // ❌ VALIDATION ERROR
        if (!response.ok) {

            if (result.errors) {
                showErrors(form, result.errors);
            }

            button.innerHTML = originalText;
            button.disabled = false;
            return;
        }

        // ================= SUCCESS =================
        clearErrors(form);

        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            timer: 1200,
            showConfirmButton: false
        });

        // 🔥 ambil data dari response
        let data = result.data;

        // 🔥 update file UI
        if (data && data.attachment_file) {

            let fileCell = form.closest("tr").querySelector("#upload-file");

            let files = [];
            try {
                let parsed = JSON.parse(data.attachment_file);
                files = Array.isArray(parsed) ? parsed : [data.attachment_file];
            } catch(e) {
                files = [data.attachment_file];
            }

            let downloadLinks = files.map(f =>
                `<a href="/uploads/attachment_file_question_file/${f}" class="btn btn-success btn-sm" target="_blank">⬇ Download File</a>`
            ).join('');

            fileCell.innerHTML = `
                ${downloadLinks}

                <label style="font-size:12px;">Ganti File:</label>

                <input type="file"
                    name="attachment_file_${data.id}[]"
                    class="form-control"
                    accept=".pdf"
                    multiple>

                <div class="form-text text-muted" style="font-size:11px;">Format: PDF, maks 25MB per file</div>

                <div id="error-attachment_file_${data.id}"></div>
            `;
        }

        // 🔥 update badge "X belum diisi" & tombol Kirim, tanpa reload halaman
        updateInvalidStatus(form, data);

        button.innerHTML = '✔ Updated';

        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        }, 1200);

    } catch (error) {
        Swal.fire('Error', 'Server error', 'error');

        button.innerHTML = originalText;
        button.disabled = false;
    }
}

// ================= UPDATE BADGE "BELUM DIISI" & TOMBOL KIRIM =================
function updateInvalidStatus(form, data) {

    let tr = form.closest('tr');
    let categoryId = tr.dataset.categoryId;

    // cek apakah pertanyaan ini sudah lengkap (level + file terisi)
    let hasLevel = !!data.level;

    let hasFile = false;
    try {
        let parsed = JSON.parse(data.attachment_file || '[]');
        hasFile = Array.isArray(parsed) ? parsed.length > 0 : !!data.attachment_file;
    } catch (e) {
        hasFile = !!data.attachment_file;
    }

    tr.dataset.invalid = (hasLevel && hasFile) ? '0' : '1';

    // hitung ulang jumlah "belum diisi" untuk kategori ini
    let remainingInCategory = document.querySelectorAll(
        `tr[data-category-id="${categoryId}"][data-invalid="1"]`
    ).length;

    let badge = document.getElementById(`invalid-badge-${categoryId}`);
    let countEl = document.getElementById(`invalid-count-${categoryId}`);

    if (badge && countEl) {
        countEl.textContent = remainingInCategory;
        badge.style.display = remainingInCategory > 0 ? 'inline-block' : 'none';
    }

    // hitung ulang total "belum diisi" di seluruh area untuk tombol Kirim
    let totalRemaining = document.querySelectorAll('tr[data-invalid="1"]').length;
    let btnKirim = document.getElementById('btn-kirim');

    if (btnKirim) {
        btnKirim.disabled = totalRemaining > 0;
        btnKirim.classList.toggle('btn-success', totalRemaining === 0);
        btnKirim.classList.toggle('btn-secondary', totalRemaining > 0);
        btnKirim.style.backgroundColor = totalRemaining > 0 ? 'gray' : '';
    }
}

// ================= INIT =================
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".btn-update").forEach(btn => {

        btn.addEventListener("click", function () {

            let form = btn.closest("tr").querySelector("form");

            confirmSaveAjax(form, 'Data akan disimpan?', function () {
                submitAjax(form, btn);
            });

        });

    });

});

</script>
@endsection