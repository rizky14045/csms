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

                @if($category->invalid_questions_count > 0)
                <span style="margin-left:8px; padding:4px; background:red; color:white; border-radius:4px;">
                  {{ $category->invalid_questions_count }} belum diisi
                </span>
                @endif
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
                        <th style="min-width:120px;">Level</th>
                        <th style="min-width:250px;">Level Penilaian</th>
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
                      <tr>

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

                          <td>
                            @foreach ($question->levels as $level)
                            <div>Level {{$level->level}}</div>
                            @endforeach
                          </td>

                          <td>
                            @foreach ($question->levels as $level)
                            <div>{{$level->level_description}}</div>
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
                            <div style="display:flex; flex-direction:column; gap:6px;" id="upload-file">

                                @if ($question->attachment_file)
                                <a href="{{ asset('uploads/attachment_file_question_file/'.$question->attachment_file) }}"
                                class="btn btn-success btn-sm" target="_blank">
                                    ⬇ Download File
                                </a>

                                <label style="font-size:12px;">Ganti File:</label>

                                <input type="file"
                                    name="attachment_file_{{$question->id}}"
                                    class="form-control"
                                    accept=".pdf">
                                @else
                                <input type="file"
                                    name="attachment_file_{{$question->id}}"
                                    class="form-control"
                                    accept=".pdf"
                                    required>
                                @endif

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
            @if(count($assesment->getInvalidItemsQuestionByBujp) == 0)
                <form action="{{route('bujp.assesment.send',['assesment'=>$assesment->id])}}" method="post" class="d-inline" id="send-assesment-{{ $assesment->id }}" onsubmit="confirmSave('send-assesment-{{ $assesment->id }}', 'Kirim assesment?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Kirim</button>
                </form>
            @else
                <button type="button" style="background-color: gray" class="btn btn-secondary" disabled>Kirim</button>
            @endif
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

        let errorDiv = document.getElementById("error-" + name);

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

        let input = form.querySelector(`[name="${name}"]`);
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

            let fileUrl = "/uploads/attachment_file_question_file/" + data.attachment_file;

            fileCell.innerHTML = `
                <a href="${fileUrl}"
                class="btn btn-success btn-sm"
                target="_blank">
                ⬇ Download File
                </a>

                <label style="font-size:12px;">Ganti File:</label>

                <input type="file"
                    name="attachment_file_${data.id}"
                    class="form-control"
                    accept=".pdf">

                <div id="error-attachment_file_${data.id}"></div>
            `;
        }

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