@extends('layout.app')
@section('styles')
<style>
    .accordion-button::after { filter: invert(100%); }
    .file-cell { display:flex; flex-direction:column; gap:5px; }
    .error-text { color:#dc3545; font-size:11px; margin-top:3px; }
</style>
@stop
@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">KPI Keamanan</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data KPI</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                @if(auth()->user()->roles[0]->name == 'Pusat')
                <a href="{{ route('admin.keamanan.index') }}" class="btn btn-danger mb-3">Kembali</a>
                @else
                <a href="{{ route('user.keamanan.index') }}" class="btn btn-danger mb-3">Kembali</a>
                @endif

                @php
                    $totalSubAreas   = collect($areas)->sum(fn($a) => count($a['sub_areas']));
                    $bobot           = $totalSubAreas > 0 ? 1 / $totalSubAreas : 0;
                    $grandTotalBobot = 0;
                    $grandTotalML    = 0;
                @endphp

                <div class="accordion" id="formAccordion">

                @foreach ($areas as $area)
                <div class="accordion-item">
                    <h2 class="accordion-header bg-light">
                        <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $area['id'] }}"
                                aria-expanded="false">
                            {{ $area['name'] }}
                        </button>
                    </h2>

                    <div id="collapse{{ $area['id'] }}"
                         class="accordion-collapse collapse {{ request('areaId') == $area['id'] ? 'show' : '' }}"
                         data-bs-parent="#formAccordion">
                        <div class="accordion-body" style="overflow-x:auto;">

                            <table class="table table-bordered align-middle" style="min-width:1000px;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width:45px;"  class="text-center">No</th>
                                        <th style="min-width:200px;" class="text-center">Sub Area</th>
                                        <th style="min-width:55px;"  class="text-center">Level</th>
                                        <th style="min-width:220px;" class="text-center">Uraian</th>
                                        <th style="min-width:240px;" class="text-center">File Evidence</th>
                                        <th style="min-width:80px;"  class="text-center">Bobot</th>
                                        <th style="min-width:90px;"  class="text-center">Hasil Assesment</th>
                                        <th style="min-width:90px;"  class="text-center">Score ML</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @foreach ($area['sub_areas'] as $subArea)
                                @php
                                    $levels     = $subArea['levels'];
                                    $levelCount = count($levels);
                                    $uploaded   = collect($levels)->filter(fn($l) => !empty($l['attachment_file']))->count();
                                    $hasil      = $levelCount > 0 ? round($uploaded / $levelCount, 4) : 0;
                                    $scoreML    = round($hasil * $bobot, 4);
                                    $grandTotalBobot += $bobot;
                                    $grandTotalML    += $scoreML;
                                @endphp

                                @foreach ($levels as $idx => $level)
                                <tr>
                                    @if ($idx === 0)
                                    <td class="text-center" rowspan="{{ $levelCount }}">{{ $loop->parent->iteration }}</td>
                                    <td rowspan="{{ $levelCount }}">
                                        <strong>{{ $subArea['name'] }}</strong>
                                        @if(!empty($subArea['description']))
                                        <div class="text-muted small mt-1">{{ $subArea['description'] }}</div>
                                        @endif
                                        @if(!empty($subArea['reference']))
                                        <div class="text-muted small">Ref: {{ $subArea['reference'] }}</div>
                                        @endif
                                    </td>
                                    @endif

                                    <td class="text-center">{{ $level['level'] }}</td>
                                    <td style="white-space:normal;">{{ $level['description'] }}</td>

                                    {{-- FILE CELL --}}
                                    <td data-level-id="{{ $level['id'] }}"
                                        data-subarea-id="{{ $subArea['id'] }}"
                                        data-upload-url="{{ route('user.keamanan.uploadLevel', ['kpi' => $kpi->id, 'level' => $level['id']]) }}">

                                        <div id="file-display-{{ $level['id'] }}" class="file-cell mb-1">
                                            @if(!empty($level['attachment_file']))
                                            <a href="{{ asset('uploads/attachment_file_kpi_file/' . $level['attachment_file']) }}"
                                               target="_blank"
                                               class="btn btn-success btn-sm"
                                               style="font-size:11px;">
                                               ⬇ Download File
                                            </a>
                                            <span class="text-muted" style="font-size:10px;">Ganti file:</span>
                                            @endif
                                        </div>

                                        <input type="file"
                                               id="file-input-{{ $level['id'] }}"
                                               class="form-control form-control-sm mb-1"
                                               accept=".pdf">
                                        <div class="form-text" style="font-size:10px;">PDF, maks 25MB</div>
                                        <div id="error-level-{{ $level['id'] }}" class="error-text"></div>
                                        <button type="button"
                                                class="btn btn-primary btn-sm btn-upload-level mt-1"
                                                data-level="{{ $level['id'] }}">
                                            ⬆ Upload
                                        </button>
                                    </td>

                                    @if ($idx === 0)
                                    <td class="text-center" rowspan="{{ $levelCount }}">{{ round($bobot, 4) }}</td>
                                    <td class="text-center fw-semibold" rowspan="{{ $levelCount }}" id="hasil-{{ $subArea['id'] }}">{{ $hasil }}</td>
                                    <td class="text-center fw-semibold" rowspan="{{ $levelCount }}" id="scoreml-{{ $subArea['id'] }}">{{ $scoreML }}</td>
                                    @endif
                                </tr>
                                @endforeach

                                @endforeach

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                @endforeach

                </div>{{-- end accordion --}}

                {{-- GRAND TOTAL --}}
                <div class="card mt-4 border-0 shadow-sm">
                    <div class="card-header bg-primary text-white py-2">
                        <h6 class="mb-0 fw-semibold">Total Keseluruhan</h6>
                    </div>
                    <div class="card-body p-3">
                        <table class="table table-bordered mb-0" style="max-width:380px;">
                            <thead class="table-light">
                                <tr>
                                    <th>Komponen</th>
                                    <th class="text-center" style="min-width:120px;">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Total Bobot</td>
                                    <td class="text-center fw-bold" id="grand-bobot">{{ round($grandTotalBobot, 4) }}</td>
                                </tr>
                                <tr>
                                    <td>Total Score ML</td>
                                    <td class="text-center fw-bold text-primary" id="grand-scoreml">{{ round($grandTotalML, 4) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const CSRF  = '{{ csrf_token() }}';
const BOBOT = {{ round($bobot, 8) }};

// Store per-level upload state: levelId → hasFile (bool)
const levelHasFile = {
    @foreach ($areas as $area)
        @foreach ($area['sub_areas'] as $subArea)
            @foreach ($subArea['levels'] as $level)
            {{ $level['id'] }}: {{ !empty($level['attachment_file']) ? 'true' : 'false' }},
            @endforeach
        @endforeach
    @endforeach
};

// subArea → [levelIds]
const subAreaLevels = {
    @foreach ($areas as $area)
        @foreach ($area['sub_areas'] as $subArea)
        {{ $subArea['id'] }}: [{{ implode(',', array_column($subArea['levels'], 'id')) }}],
        @endforeach
    @endforeach
};

function updateSubAreaTotals(subAreaId) {
    const levelIds  = subAreaLevels[subAreaId] || [];
    const total     = levelIds.length;
    const uploaded  = levelIds.filter(id => levelHasFile[id]).length;
    const hasil     = total > 0 ? Math.round((uploaded / total) * 10000) / 10000 : 0;
    const scoreML   = Math.round(hasil * BOBOT * 10000) / 10000;

    const hasilEl   = document.getElementById('hasil-'   + subAreaId);
    const scoremlEl = document.getElementById('scoreml-' + subAreaId);
    if (hasilEl)   hasilEl.textContent   = hasil;
    if (scoremlEl) scoremlEl.textContent = scoreML;

    // Grand total score ML
    let grandML = 0;
    document.querySelectorAll('[id^="scoreml-"]').forEach(el => {
        if (el.id !== 'grand-scoreml') grandML += parseFloat(el.textContent) || 0;
    });
    const grandEl = document.getElementById('grand-scoreml');
    if (grandEl) grandEl.textContent = Math.round(grandML * 10000) / 10000;
}

async function doUpload(levelId) {
    const td        = document.querySelector(`td[data-level-id="${levelId}"]`);
    const uploadUrl = td.dataset.uploadUrl;
    const subAreaId = td.dataset.subareaId;
    const fileInput = document.getElementById('file-input-' + levelId);
    const errorEl   = document.getElementById('error-level-' + levelId);
    const btn       = td.querySelector('.btn-upload-level');
    const displayEl = document.getElementById('file-display-' + levelId);

    if (errorEl) errorEl.innerHTML = '';

    if (!fileInput || !fileInput.files.length) {
        if (errorEl) errorEl.innerHTML = 'Pilih file PDF terlebih dahulu!';
        return;
    }

    const fd = new FormData();
    fd.append('file', fileInput.files[0]);
    fd.append('_token', CSRF);

    if (btn) { btn.innerHTML = '⏳ Uploading…'; btn.disabled = true; }

    try {
        const res    = await fetch(uploadUrl, {
            method: 'POST', body: fd,
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });
        const result = await res.json();

        if (!res.ok) {
            const msgs = result.errors
                ? Object.values(result.errors).flat().join('<br>')
                : (result.message || 'Upload gagal');
            if (errorEl) errorEl.innerHTML = msgs;
            if (btn) { btn.innerHTML = '⬆ Upload'; btn.disabled = false; }
            return;
        }

        Swal.fire({ icon: 'success', title: 'Upload berhasil', timer: 1000, showConfirmButton: false });

        const fileUrl = '/uploads/attachment_file_kpi_file/' + result.filename;
        if (displayEl) {
            displayEl.innerHTML = `
                <a href="${fileUrl}" target="_blank" class="btn btn-success btn-sm" style="font-size:11px;">
                    ⬇ Download File
                </a>
                <span class="text-muted" style="font-size:10px;">Ganti file:</span>
            `;
        }
        if (fileInput) fileInput.value = '';
        if (btn) { btn.innerHTML = '⬆ Upload'; btn.disabled = false; }

        levelHasFile[levelId] = true;
        updateSubAreaTotals(subAreaId);

    } catch (e) {
        Swal.fire('Error', 'Server error', 'error');
        if (btn) { btn.innerHTML = '⬆ Upload'; btn.disabled = false; }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-upload-level').forEach(btn => {
        btn.addEventListener('click', () => {
            const levelId = btn.dataset.level;
            Swal.fire({
                title: 'Upload file?', icon: 'question',
                showCancelButton: true, confirmButtonText: 'Upload', cancelButtonText: 'Batal'
            }).then(r => { if (r.isConfirmed) doUpload(levelId); });
        });
    });
});
</script>
@endsection
