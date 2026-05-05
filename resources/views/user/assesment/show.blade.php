@extends('layout.app')

@section('styles')
<style>
  .accordion-button::after {
      filter: invert(100%);
  }

  .error-text {
      font-size:12px;
      color:#dc3545;
      margin-top:4px;
  }
</style>
@stop

@section('content')

<div class="py-3 d-flex justify-content-between">
  <h4>Assesment</h4>
</div>

<div class="card">
  <div class="card-body">

    <div class="accordion" id="formAccordion">

      @foreach ($categories as $category)

      <div class="accordion-item">

        <h2 class="accordion-header">
          <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#c{{$category->id}}">
            {{$category->category_name}}

            @if($category->invalid_questions_count > 0)
            <span style="margin-left:8px; background:red; color:white; padding:3px 6px; border-radius:4px;">
              {{$category->invalid_questions_count}} belum diisi
            </span>
            @endif
          </button>
        </h2>

        <div id="c{{$category->id}}" class="accordion-collapse collapse {{request('signCategoryId') == $category->id ? 'show' :''}}">
          <div class="accordion-body">

            <div style="overflow-x:auto;">
              <table class="table table-bordered align-middle">

                <thead class="table-light">
                  <tr>
                    <th style="min-width:60px;">No</th>
                    <th style="min-width:250px;">Indikator</th>
                    <th style="min-width:120px;">Level</th>
                    <th style="min-width:250px;">Level Penilaian</th>
                    <th style="min-width:120px;">Nilai</th>
                    <th style="min-width:180px;">Note</th>
                    <th style="min-width:160px;">File</th>
                    <th style="min-width:160px;">Penilaian Unit</th>
                    <th style="min-width:200px;">Revisi</th>
                    <th style="min-width:140px;">Action</th>
                  </tr>
                </thead>

                <tbody>

                @foreach ($category->questions as $question)

                <tr>

                  {{-- FORM PER ROW (VALID) --}}
                  <form id="form-{{$question->id}}"
                        action="{{ route('user.assesment.updateQuestion',['question'=>$question->id]) }}"
                        method="POST">

                    @csrf
                    @method('PATCH')

                    <td>{{$loop->iteration}}</td>

                    <td>{{$question->indicator}}</td>

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

                    <td style="
                        @php
                            $level = $question->level ?? 0;

                            switch ($level) {
                                case 5:
                                    $bg = '#28a745'; // hijau
                                    break;
                                case 4:
                                    $bg = '#20c997'; // teal
                                    break;
                                case 3:
                                    $bg = '#ffc107'; // kuning
                                    break;
                                case 2:
                                    $bg = '#fd7e14'; // orange
                                    break;
                                case 1:
                                    $bg = '#dc3545'; // merah
                                    break;
                                default:
                                    $bg = '#6c757d'; // abu
                                    break;
                            }
                        @endphp

                        background: {{ $bg }};
                        color: {{ $level == 3 ? '#000' : '#fff' }};
                        font-weight: 600;
                        text-align: center;
                        vertical-align: middle;
                    ">
                        {{ $level ?: '-' }}
                    </td>

                    <td>{{$question->note}}</td>

                    {{-- FILE --}}
                    <td id="upload-file-{{$question->id}}">
                      @if ($question->attachment_file)
                        <a href="{{ asset('uploads/attachment_file_question_file/'.$question->attachment_file) }}"
                           class="btn btn-success btn-sm"
                           target="_blank">
                           ⬇ Download
                        </a>
                      @else
                        -
                      @endif
                    </td>

                    {{-- EVALUATION --}}
                    <td>
                      <select name="evaluation_unit_{{$question->id}}" class="form-select">
                        <option value="">Pilih</option>
                        @for($i=1;$i<=5;$i++)
                          <option value="{{$i}}" {{$question->evaluation_unit == $i ? 'selected' : ''}}>
                            {{$i}}
                          </option>
                        @endfor
                      </select>
                      <div id="error-evaluation_unit_{{$question->id}}" class="error-text"></div>
                    </td>

                    {{-- REVISION --}}
                    <td>
                      <textarea name="note_revision_{{$question->id}}" class="form-control">{{$question->note_revision}}</textarea>
                      <div id="error-note_revision_{{$question->id}}" class="error-text"></div>
                    </td>

                    {{-- ACTION --}}
                    <td>
                      <button type="button"
                              class="btn btn-success btn-sm btn-update"
                              data-form="form-{{$question->id}}">
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

    </div>

    <div class="mt-4 d-flex justify-content-end gap-2">
        @if(auth()->user()->roles[0]->name == 'Pusat')
        <a href="{{route('admin.assesment.index')}}" class="btn btn-danger"> Kembali</a>
        @else
        <a href="{{route('user.assesment.index')}}" class="btn btn-danger"> Kembali</a>
        @endif
        <form action="{{route('user.assesment.revision',['assesment'=>$assesment->id])}}" method="post" class="d-inline" id="revision-assesment-{{ $assesment->id }}" onsubmit="confirmSave('revision-assesment-{{ $assesment->id }}', 'Revisi assesment?')">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-warning">Revisi</button>
        </form>
        @if(count($assesment->getInvalidItemsQuestionByUnit) == 0)
            <form action="{{route('user.assesment.send',['assesment'=>$assesment->id])}}" method="post" class="d-inline" id="send-assesment-{{ $assesment->id }}" onsubmit="confirmSave('send-assesment-{{ $assesment->id }}', 'Kirim assesment?')">
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

@endsection

@section('scripts')
<script>

// CLEAR ERROR
function clearErrors(form) {
    if (!form) return;

    form.querySelectorAll("[id^='error-']").forEach(el => el.innerHTML = '');
    form.querySelectorAll("input, select, textarea").forEach(el => el.style.border = '');
}

// SHOW ERROR
function showErrors(form, errors) {
    Object.keys(errors).forEach(name => {

        let msg = errors[name][0];

        let errorDiv = document.getElementById("error-" + name);
        if (errorDiv) {
            errorDiv.innerHTML = "⚠ " + msg;
        }

        let input = form.querySelector(`[name="${name}"]`);
        if (input) input.style.border = "1px solid red";

    });
}

// AJAX
async function submitAjax(form, btn) {

    clearErrors(form);

    let formData = new FormData(form);
    let original = btn.innerHTML;

    btn.innerHTML = "⏳ Loading...";
    btn.disabled = true;

    try {

        let res = await fetch(form.action, {
            method: "POST",
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                'Accept': 'application/json'
            }
        });

        let result = await res.json();

        if (!res.ok) {
            if (result.errors) showErrors(form, result.errors);

            btn.innerHTML = original;
            btn.disabled = false;
            return;
        }

        clearErrors(form);

        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            timer: 1000,
            showConfirmButton: false
        });

        let data = result.data;

        if (data && data.attachment_file) {

            let fileCell = document.getElementById("upload-file-" + data.id);

            fileCell.innerHTML = `
                <a href="/uploads/attachment_file_question_file/${data.attachment_file}"
                   class="btn btn-success btn-sm"
                   target="_blank">
                   ⬇ Download
                </a>
            `;
        }

        btn.innerHTML = "✔ Updated";

        setTimeout(() => {
            btn.innerHTML = original;
            btn.disabled = false;
        }, 1200);

    } catch (err) {
        console.error(err);
        Swal.fire('Error','Server error','error');

        btn.innerHTML = original;
        btn.disabled = false;
    }
}

// CONFIRM
function confirmSaveAjax(callback) {
    Swal.fire({
        title: 'Simpan Data?',
        text: 'Data akan disimpan',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal'
    }).then(res => {
        if (res.isConfirmed) callback();
    });
}

// INIT
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".btn-update").forEach(btn => {

        btn.addEventListener("click", function (e) {

            e.preventDefault();

            let formId = btn.getAttribute("data-form");
            let form = document.getElementById(formId);

            if (!form) {
                console.error("Form tidak ditemukan:", formId);
                return;
            }

            confirmSaveAjax(() => {
                submitAjax(form, btn);
            });

        });

    });

});
</script>
@endsection