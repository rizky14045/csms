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
        <h4 class="fs-18 fw-semibold m-0">Maturity</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Maturity</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                @if(auth()->user()->roles[0]->name == 'Pusat')
                <a href="{{route('admin.marturity.index')}}" class="btn btn-danger mb-3"> Kembali</a>
                @else
                <a href="{{route('user.marturity.index')}}" class="btn btn-danger mb-3"> Kembali</a>
                @endif
                 <!-- Komitmen Management -->
                 <div class="accordion" id="formAccordion">

                    @foreach ($areas as $area)

                    <!-- Section for Each area -->
                    <div class="accordion-item">
                        <h2 class="accordion-header bg-light" id="heading{{$area['id']}}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$area['id']}}" aria-expanded="false" aria-controls="collapse{{$area['id']}}">
                                {{$area['name']}}
                            </button>
                        </h2>
                        <div id="collapse{{$area['id']}}" class="accordion-collapse collapse {{request('areaId') == $area['id'] ? 'show' :''}}" aria-labelledby="heading{{$area['id']}}" data-bs-parent="#formAccordion">
                            <div class="accordion-body" style="overflow-x:auto; width:100%;">
                                <table class="table table-bordered" style="min-width:1200px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="min-width:60px;" class="text-center">No</th>
                                            <th style="min-width:250px;" class="text-center">Sub Area</th>
                                            <th style="min-width:80px;" class="text-center">Level</th>
                                            <th style="min-width:300px;" class="text-center">Uraian</th>
                                            <th style="min-width:120px;" class="text-center">Total Eviden</th>
                                            <th style="min-width:140px;" class="text-center">Jumlah Eviden</th>
                                            <th style="min-width:100px;" class="text-center">Bobot</th>
                                            <th style="min-width:300px;" class="text-center">Catatan</th>
                                            <th style="min-width:200px;" class="text-center">File</th>
                                            <th style="min-width:160px;" class="text-center">Action</th>
                                            <th style="min-width:120px;" class="text-center">Hasil</th>
                                            <th style="min-width:120px;" class="text-center">Score ML</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($area['sub_areas'] as $subArea)

                                            @php
                                                $totalRowspan = collect($subArea['levels'])->reduce(function ($carry, $level) {
                                                    return $carry + 1 + count($level['notes']);
                                                }, 0);

                                                $previousResult = false;
                                                $firstLevel = true;
                                                $totalResult = 0;
                                            @endphp

                                            <tr>
                                                <td class="text-left" rowspan="{{ $totalRowspan }}">
                                                    {{ $loop->iteration }}
                                                </td>

                                                <td class="text-left w-25" rowspan="{{ $totalRowspan }}">
                                                    <h6 class="fw-bold">{{ $subArea['name'] }}</h6>
                                                    <p>Deskripsi : {{ $subArea['description'] }}</p>
                                                    <span>Referensi : {{ $subArea['reference'] }}</span>
                                                </td>

                                                @foreach ($subArea['levels'] as $level)

                                                    @if (!$firstLevel)
                                                        <tr>
                                                    @endif

                                                    @php
                                                        $totalNotes = count($level['notes']);
                                                        $sumEviden = collect($level['notes'])
                                                            ->whereNotNull('attachment_file')
                                                            ->count();

                                                        $result = $totalNotes > 0 ? ($sumEviden / $totalNotes) : 0;

                                                        if ($previousResult) {
                                                            $result = 0;
                                                        }

                                                        if ($result !== 1) {
                                                            $previousResult = true;
                                                        }

                                                        $totalResult += $result;
                                                    @endphp

                                                    <td rowspan="{{ $totalNotes + 1 }}">
                                                        {{ $level['level'] }}
                                                    </td>

                                                    <td rowspan="{{ $totalNotes + 1 }}" class="w-25">
                                                        {{ $level['description'] }}
                                                    </td>

                                                    <td rowspan="{{ $totalNotes + 1 }}" class="text-center">
                                                        {{ $totalNotes }}
                                                    </td>

                                                    <td rowspan="{{ $totalNotes + 1 }}" class="text-center">
                                                        {{ $sumEviden }}
                                                    </td>

                                                    <td rowspan="{{ $totalNotes + 1 }}" class="text-center">
                                                        {{ $result }}
                                                    </td>

                                                    </tr>

                                                    @foreach ($level['notes'] as $note)
                                                        <tr>
                                                            <td>{{ $note['note'] }}</td>

                                                            <td style="min-width:200px;">
                                                                <div id="upload-file-{{$note['id']}}" style="display:flex; flex-direction:column; gap:6px;">

                                                                    @if (!empty($note['attachment_file']))
                                                                        <a href="{{ asset('uploads/attachment_file_marturity_file/'.$note['attachment_file']) }}"
                                                                        class="btn btn-success btn-sm"
                                                                        target="_blank">
                                                                        ⬇ Download File
                                                                        </a>

                                                                        <label style="font-size:12px;">Ganti File:</label>
                                                                    @endif

                                                                    <form id="form-upload-{{$note['id']}}"
                                                                        action="{{ route('user.marturity.uploadNote', [
                                                                            'marturity' => $note['marturity_id'],
                                                                            'areaId' => $subArea['area_id'],
                                                                            'note' => $note['id']
                                                                        ]) }}"
                                                                        method="POST"
                                                                        enctype="multipart/form-data">

                                                                        @csrf
                                                                        @method('PATCH')

                                                                        <input type="file"
                                                                            class="form-control form-control-sm"
                                                                            name="attachment_file_{{ $note['id'] }}"
                                                                            accept=".pdf"
                                                                            {{ empty($note['attachment_file']) ? 'required' : '' }}>

                                                                        <div id="error-attachment_file_{{ $note['id'] }}" class="error-text"></div>
                                                                    </form>

                                                                </div>
                                                            </td>

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

                                                @php
                                                    $totalLevel = count($subArea['levels']);
                                                    $bobot = $totalLevel > 0 ? round(1 / $totalLevel, 2) : 0;
                                                    $totalML = $totalResult * $bobot;
                                                @endphp

                                                <td rowspan="{{ $totalRowspan }}" class="text-center">
                                                    {{ $bobot }}
                                                </td>

                                                <td rowspan="{{ $totalRowspan }}" class="text-center">
                                                    {{ $totalResult }}
                                                </td>

                                                <td rowspan="{{ $totalRowspan }}" class="text-center">
                                                    {{ $totalML }}
                                                </td>

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

        // UPDATE DOWNLOAD BUTTON TANPA RELOAD
        if (result.data && result.data.attachment_file) {

            let fileCell = document.getElementById("upload-file-" + noteId);

            let fileUrl = "/uploads/attachment_file_marturity_file/" + result.data.attachment_file;

            fileCell.innerHTML = `
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

