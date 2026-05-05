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
        <h4 class="fs-18 fw-semibold m-0">KPI</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data KPI</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                @if(auth()->user()->roles[0]->name == 'Pusat')
                <a href="{{route('admin.keamanan.index')}}" class="btn btn-danger mb-3"> Kembali</a>
                @else
                <a href="{{route('user.keamanan.index')}}" class="btn btn-danger mb-3"> Kembali</a>
                @endif
                 <!-- Komitmen Management -->
                 <div class="accordion" id="formAccordion">
                    @foreach ($areas as $area)

                        <div class="accordion-item">
                            <h2 class="accordion-header bg-light" id="heading{{$area['id']}}">
                                <button class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{$area['id']}}">

                                    {{$area['name']}}
                                </button>
                            </h2>

                            <div id="collapse{{$area['id']}}"
                                class="accordion-collapse collapse {{request('areaId') == $area['id'] ? 'show' :''}}">

                                <div class="accordion-body">

                                    <table class="table table-bordered">
                                        {{-- <thead class="table-light">
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">Sub Area</th>
												<th class="text-center">Level</th>
												<th class="text-center">Uraian</th>
												<th class="text-center">Catatan Assesment (Eviden)</th>
												<th class="text-center">File</th>
											</tr>
										</thead> --}}
                                        <tbody>

                                        @foreach ($area['sub_areas'] as $subArea)

                                        @php
                                            $totalRowspan = 0;

                                            foreach ($subArea['levels'] as $level) {
                                                $totalRowspan += 1 + count($level['notes']);
                                            }

                                            $totalSub = count($subArea['levels']);
                                            $bobot = number_format(1 / $totalSub,2);

                                            $firstLevel = true;
                                        @endphp

                                        <tr>
                                            <td rowspan="{{$totalRowspan}}">
                                                {{$loop->iteration}}
                                            </td>

                                            <td rowspan="{{$totalRowspan}}" class="w-25">
                                                <h6 class="fw-bold">{{$subArea['name']}}</h6>

                                                <p>Deskripsi : {{$subArea['description']}}</p>

                                                <span>Referensi : {{$subArea['reference']}}</span>
                                            </td>

                                            <td rowspan="{{$totalRowspan}}" class="text-center">
                                                {{$bobot}}
                                            </td>

                                            <td rowspan="{{$totalRowspan}}" class="text-center">
                                                {{$totalSub}}
                                            </td>

                                            <td rowspan="{{$totalRowspan}}" class="text-center">
                                                {{$loop->iteration}}
                                            </td>

                                            @foreach ($subArea['levels'] as $level)

                                                @if(!$firstLevel)
                                                <tr>
                                                @endif

                                                <td rowspan="{{count($level['notes']) + 1}}">
                                                    {{$level['level']}}
                                                </td>

                                                <td rowspan="{{count($level['notes']) + 1}}">
                                                    {{$level['description']}}
                                                </td>

                                        </tr>

                                        @foreach ($level['notes'] as $note)

                                        <tr>

                                            <td>{{$note['note']}}</td>

                                            {{-- FILE --}}
                                            <td style="min-width:220px;">
                                                <div id="upload-file-{{$note['id']}}" style="display:flex; flex-direction:column; gap:6px;">

                                                    @if ($note['attachment_file'])
                                                        <a href="{{ asset('uploads/attachment_file_kpi_file/'.$note['attachment_file']) }}"
                                                        class="btn btn-success btn-sm"
                                                        target="_blank">
                                                        ⬇ Download File
                                                        </a>

                                                        <label style="font-size:12px;">Ganti File:</label>
                                                    @endif

                                                    <form id="form-upload-{{$note['id']}}"
                                                        action="{{route('user.keamanan.uploadNote',[
                                                                'kpi'=>$note['kpi_id'],
                                                                'areaId'=>$subArea['area_id'],
                                                                'note'=>$note['id']
                                                        ])}}"
                                                        method="POST"
                                                        enctype="multipart/form-data">

                                                        @csrf
                                                        @method('PATCH')

                                                        <input type="file"
                                                            name="attachment_file_{{$note['id']}}"
                                                            class="form-control form-control-sm"
                                                            accept=".pdf"
                                                            {{ !$note['attachment_file'] ? 'required' : '' }}>

                                                        <div id="error-attachment_file_{{$note['id']}}" class="error-text"></div>
                                                    </form>

                                                </div>
                                            </td>

                                            {{-- ACTION --}}
                                            <td style="min-width:140px;">
                                                <button type="button"
                                                        class="btn btn-success btn-sm btn-upload"
                                                        data-form="form-upload-{{$note['id']}}"
                                                        data-id="{{$note['id']}}">
                                                    💾 Upload
                                                </button>
                                            </td>

                                        </tr>

                                        @endforeach

                                        @php
                                            $firstLevel = false;
                                        @endphp

                                        @endforeach

                                        </tr>

                                        @endforeach

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                        @endforeach
                </div>
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

@section('scripts')
<script>
    // CLEAR ERROR
    function clearErrors(form) {
        form.querySelectorAll("[id^='error-']").forEach(el => el.innerHTML = '');
        form.querySelectorAll("input").forEach(el => el.style.border = '');
    }

    // SHOW ERROR
    function showErrors(form, errors) {
        Object.keys(errors).forEach(name => {
            let msg = errors[name][0];

            let errorDiv = document.getElementById("error-" + name);
            if (errorDiv) errorDiv.innerHTML = msg;

            let input = form.querySelector(`[name="${name}"]`);
            if (input) input.style.border = "1px solid red";
        });
    }

    // SUBMIT AJAX
    async function submitUpload(form, btn, noteId) {

        clearErrors(form);

        let formData = new FormData(form);
        let original = btn.innerHTML;

        btn.innerHTML = "Uploading...";
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

            Swal.fire({
                icon: 'success',
                title: 'Upload berhasil',
                timer: 1000,
                showConfirmButton: false
            });

            // 🔥 UPDATE UI (INI YANG KAMU MAU)
            if (result.data && result.data.attachment_file) {

                let container = document.getElementById("upload-file-" + noteId);

                let fileUrl = "/uploads/attachment_file_kpi_file/" + result.data.attachment_file;

                container.innerHTML = `
                    <a href="${fileUrl}"
                    class="btn btn-success btn-sm"
                    target="_blank">
                    ⬇ Download File
                    </a>

                    <label style="font-size:12px;">Ganti File:</label>

                    <form id="form-upload-${noteId}" enctype="multipart/form-data">
                        <input type="file"
                            name="attachment_file_${noteId}"
                            class="form-control form-control-sm"
                            accept=".pdf">

                        <div id="error-attachment_file_${noteId}" class="error-text"></div>
                    </form>
                `;
            }

            btn.innerHTML = "✔ Uploaded";

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

    // INIT
    document.addEventListener("DOMContentLoaded", function () {

        document.querySelectorAll(".btn-upload").forEach(btn => {

            btn.addEventListener("click", function () {

                let formId = btn.getAttribute("data-form");
                let noteId = btn.getAttribute("data-id");
                let form = document.getElementById(formId);

                if (!form) return;

                Swal.fire({
                    title: 'Upload file?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Upload'
                }).then(res => {
                    if (res.isConfirmed) {
                        submitUpload(form, btn, noteId);
                    }
                });

            });

        });

    });
</script>
@endsection