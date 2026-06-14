@extends('layout.app')
@section('styles')
<style>
    .accordion-button::after { filter: invert(100%); }
    .file-list { display:flex; flex-direction:column; gap:4px; }
    .file-item { display:flex; align-items:center; gap:6px; }
    .error-text { color:#dc3545; font-size:11px; margin-top:3px; }
</style>
@stop
@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Maturity</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Maturity</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                @if(auth()->user()->roles[0]->name == 'Pusat')
                <a href="{{ route('admin.marturity.index') }}" class="btn btn-danger mb-3">Kembali</a>
                @else
                <a href="{{ route('user.marturity.index') }}" class="btn btn-danger mb-3">Kembali</a>
                @endif

                @php
                    $totalSubAreas  = collect($areas)->sum(fn($a) => count($a['sub_areas']));
                    $bobot          = $totalSubAreas > 0 ? 1 / $totalSubAreas : 0;
                    $grandTotalBobot = 0;
                    $grandTotalML   = 0;
                @endphp

                <div class="accordion" id="formAccordion">

                @foreach ($areas as $area)
                <div class="accordion-item">
                    <h2 class="accordion-header bg-light">
                        <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $area['id'] }}">
                            {{ $area['name'] }}
                        </button>
                    </h2>

                    <div id="collapse{{ $area['id'] }}"
                         class="accordion-collapse collapse {{ request('areaId') == $area['id'] ? 'show' : '' }}"
                         data-bs-parent="#formAccordion">
                        <div class="accordion-body" style="overflow-x:auto;">

                            <table class="table table-bordered align-middle" style="min-width:1200px;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width:50px;"  class="text-center">No</th>
                                        <th style="min-width:200px;" class="text-center">Sub Area</th>
                                        <th style="min-width:55px;"  class="text-center">Level</th>
                                        <th style="min-width:230px;" class="text-center">Uraian</th>
                                        <th style="min-width:95px;"  class="text-center">Total Evidence</th>
                                        <th style="min-width:95px;"  class="text-center">Jumlah Evidence</th>
                                        <th style="min-width:260px;" class="text-center">File Evidence</th>
                                        <th style="min-width:80px;"  class="text-center">Bobot</th>
                                        <th style="min-width:80px;"  class="text-center">Hasil</th>
                                        <th style="min-width:90px;"  class="text-center">Score ML</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @foreach ($area['sub_areas'] as $subArea)
                                @php
                                    $levels     = $subArea['levels'];
                                    $levelCount = count($levels);

                                    $levelCalcs = [];
                                    foreach ($levels as $lvl) {
                                        $files   = json_decode($lvl['attachment_files'] ?? '[]', true) ?: [];
                                        $jumlah  = count($files);
                                        $totalEv = max(1, (int)($lvl['total_evidence'] ?? 1));
                                        $calc    = round($jumlah / $totalEv, 4);
                                        $levelCalcs[] = compact('lvl', 'files', 'jumlah', 'totalEv', 'calc');
                                    }

                                    $hasil   = round(array_sum(array_column($levelCalcs, 'calc')), 4);
                                    $scoreML = round($hasil * $bobot, 4);
                                    $grandTotalBobot += $bobot;
                                    $grandTotalML    += $scoreML;
                                @endphp

                                @foreach ($levelCalcs as $idx => $lc)
                                <tr>
                                    @if ($idx === 0)
                                    <td class="text-center" rowspan="{{ $levelCount }}">{{ $loop->parent->iteration }}</td>
                                    <td rowspan="{{ $levelCount }}">
                                        <strong>{{ $subArea['name'] }}</strong>
                                        @if($subArea['description'])
                                        <div class="text-muted small mt-1">{{ $subArea['description'] }}</div>
                                        @endif
                                        @if($subArea['reference'])
                                        <div class="text-muted small">Ref: {{ $subArea['reference'] }}</div>
                                        @endif
                                    </td>
                                    @endif

                                    <td class="text-center">{{ $lc['lvl']['level'] }}</td>
                                    <td style="white-space:normal;">{{ $lc['lvl']['description'] }}</td>
                                    <td class="text-center">{{ $lc['totalEv'] }}</td>
                                    <td class="text-center" id="jumlah-{{ $lc['lvl']['id'] }}">{{ $lc['jumlah'] }}</td>

                                    {{-- FILE CELL — stores all data attrs needed by JS --}}
                                    <td data-level-id="{{ $lc['lvl']['id'] }}"
                                        data-subarea-id="{{ $subArea['id'] }}"
                                        data-total-evidence="{{ $lc['totalEv'] }}"
                                        data-upload-url="{{ route('user.marturity.uploadLevel', ['marturity' => $marturity->id, 'level' => $lc['lvl']['id']]) }}"
                                        data-delete-url="{{ route('user.marturity.deleteLevelFile', ['marturity' => $marturity->id, 'level' => $lc['lvl']['id']]) }}">

                                        {{-- Hidden calc value used by JS to sum hasil per subArea --}}
                                        <span id="calc-{{ $lc['lvl']['id'] }}"
                                              class="level-calc d-none"
                                              data-subarea="{{ $subArea['id'] }}">{{ $lc['calc'] }}</span>

                                        {{-- File list --}}
                                        <div id="file-list-{{ $lc['lvl']['id'] }}" class="file-list mb-1">
                                            @foreach ($lc['files'] as $fi => $file)
                                            <div class="file-item">
                                                <a href="{{ asset('uploads/attachment_file_marturity_file/' . $file) }}"
                                                   target="_blank"
                                                   class="btn btn-success btn-sm"
                                                   style="flex:1; font-size:11px; overflow:hidden; text-overflow:ellipsis; max-width:185px; white-space:nowrap;">
                                                   ⬇ File {{ $fi + 1 }}
                                                </a>
                                                <button type="button"
                                                        class="btn btn-danger btn-sm btn-delete-file"
                                                        data-level="{{ $lc['lvl']['id'] }}"
                                                        data-filename="{{ $file }}"
                                                        title="Hapus">✕</button>
                                            </div>
                                            @endforeach
                                        </div>

                                        {{-- Upload section --}}
                                        <div id="upload-section-{{ $lc['lvl']['id'] }}">
                                            @php $remaining = max(0, $lc['totalEv'] - $lc['jumlah']); @endphp
                                            @if ($remaining > 0)
                                            <input type="file"
                                                   id="file-input-{{ $lc['lvl']['id'] }}"
                                                   class="form-control form-control-sm mb-1"
                                                   accept=".pdf"
                                                   multiple>
                                            <div class="form-text" style="font-size:10px;">
                                                PDF, maks 25MB &middot; Sisa slot: {{ $remaining }}
                                            </div>
                                            <div id="error-level-{{ $lc['lvl']['id'] }}" class="error-text"></div>
                                            <button type="button"
                                                    class="btn btn-primary btn-sm btn-upload-level mt-1"
                                                    data-level="{{ $lc['lvl']['id'] }}">
                                                ⬆ Upload
                                            </button>
                                            @else
                                            <div class="text-muted small">Slot penuh ({{ $lc['totalEv'] }}/{{ $lc['totalEv'] }})</div>
                                            @endif
                                        </div>
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

// ─── Update hasil + scoreML for one subArea, then grand totals ───────────────
function updateSubAreaTotals(subAreaId) {
    let hasil = 0;
    document.querySelectorAll(`.level-calc[data-subarea="${subAreaId}"]`).forEach(el => {
        hasil += parseFloat(el.textContent) || 0;
    });
    hasil = Math.round(hasil * 10000) / 10000;
    const scoreML = Math.round(hasil * BOBOT * 10000) / 10000;

    const hasilEl   = document.getElementById('hasil-'   + subAreaId);
    const scoremlEl = document.getElementById('scoreml-' + subAreaId);
    if (hasilEl)   hasilEl.textContent   = hasil;
    if (scoremlEl) scoremlEl.textContent = scoreML;

    // Grand score ML = sum of all per-subArea scoreML cells
    let grandML = 0;
    document.querySelectorAll('[id^="scoreml-"]').forEach(el => {
        if (el.id !== 'grand-scoreml') grandML += parseFloat(el.textContent) || 0;
    });
    const grandEl = document.getElementById('grand-scoreml');
    if (grandEl) grandEl.textContent = Math.round(grandML * 10000) / 10000;
}

// ─── Re-render the file list + upload section for a level ────────────────────
function renderFileList(levelId, files, totalEv, subAreaId) {
    const td        = document.querySelector(`td[data-level-id="${levelId}"]`);
    const listEl    = document.getElementById('file-list-'     + levelId);
    const jumlahEl  = document.getElementById('jumlah-'        + levelId);
    const calcEl    = document.getElementById('calc-'          + levelId);
    const sectionEl = document.getElementById('upload-section-'+ levelId);
    const deleteUrl = td.dataset.deleteUrl;
    const uploadUrl = td.dataset.uploadUrl;

    // File buttons
    listEl.innerHTML = files.map((f, i) => `
        <div class="file-item">
            <a href="/uploads/attachment_file_marturity_file/${f}"
               target="_blank"
               class="btn btn-success btn-sm"
               style="flex:1;font-size:11px;overflow:hidden;text-overflow:ellipsis;max-width:185px;white-space:nowrap;">
               ⬇ File ${i + 1}
            </a>
            <button type="button"
                    class="btn btn-danger btn-sm btn-delete-file"
                    data-level="${levelId}"
                    data-filename="${f}"
                    title="Hapus">✕</button>
        </div>
    `).join('');

    // Jumlah & hidden calc
    const jumlah = files.length;
    if (jumlahEl) jumlahEl.textContent = jumlah;
    const calc = totalEv > 0 ? Math.round((jumlah / totalEv) * 10000) / 10000 : 0;
    if (calcEl) calcEl.textContent = calc;

    // Upload section
    const remaining = Math.max(0, totalEv - jumlah);
    if (sectionEl) {
        if (remaining > 0) {
            sectionEl.innerHTML = `
                <input type="file" id="file-input-${levelId}"
                       class="form-control form-control-sm mb-1" accept=".pdf" multiple>
                <div class="form-text" style="font-size:10px;">PDF, maks 25MB &middot; Sisa slot: ${remaining}</div>
                <div id="error-level-${levelId}" class="error-text"></div>
                <button type="button" class="btn btn-primary btn-sm btn-upload-level mt-1"
                        data-level="${levelId}">⬆ Upload</button>
            `;
            sectionEl.querySelector('.btn-upload-level')
                     .addEventListener('click', () => confirmUpload(levelId));
        } else {
            sectionEl.innerHTML = `<div class="text-muted small">Slot penuh (${totalEv}/${totalEv})</div>`;
        }
    }

    bindDeleteButtons();
    updateSubAreaTotals(subAreaId);
}

// ─── Upload ──────────────────────────────────────────────────────────────────
async function doUpload(levelId) {
    const td        = document.querySelector(`td[data-level-id="${levelId}"]`);
    const uploadUrl = td.dataset.uploadUrl;
    const totalEv   = parseInt(td.dataset.totalEvidence);
    const subAreaId = td.dataset.subareaId;
    const fileInput = document.getElementById('file-input-' + levelId);
    const errorEl   = document.getElementById('error-level-' + levelId);
    const btn       = td.querySelector('.btn-upload-level');

    if (errorEl) errorEl.innerHTML = '';

    if (!fileInput || !fileInput.files.length) {
        if (errorEl) errorEl.innerHTML = 'Pilih minimal 1 file PDF!';
        return;
    }

    const fd = new FormData();
    Array.from(fileInput.files).forEach(f => fd.append('files[]', f));
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
        renderFileList(levelId, result.files, totalEv, subAreaId);

    } catch (e) {
        Swal.fire('Error', 'Server error', 'error');
        if (btn) { btn.innerHTML = '⬆ Upload'; btn.disabled = false; }
    }
}

function confirmUpload(levelId) {
    Swal.fire({
        title: 'Upload file?', icon: 'question',
        showCancelButton: true, confirmButtonText: 'Upload', cancelButtonText: 'Batal'
    }).then(r => { if (r.isConfirmed) doUpload(levelId); });
}

// ─── Delete ──────────────────────────────────────────────────────────────────
async function doDelete(levelId, filename) {
    const td        = document.querySelector(`td[data-level-id="${levelId}"]`);
    const deleteUrl = td.dataset.deleteUrl;
    const totalEv   = parseInt(td.dataset.totalEvidence);
    const subAreaId = td.dataset.subareaId;

    try {
        const res    = await fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ filename })
        });
        const result = await res.json();

        if (!res.ok) {
            Swal.fire('Error', result.message || 'Gagal menghapus', 'error');
            return;
        }

        Swal.fire({ icon: 'success', title: 'File dihapus', timer: 800, showConfirmButton: false });
        renderFileList(levelId, result.files, totalEv, subAreaId);

    } catch (e) {
        Swal.fire('Error', 'Server error', 'error');
    }
}

// ─── Bind delete buttons (safe against duplicates) ───────────────────────────
function bindDeleteButtons() {
    document.querySelectorAll('.btn-delete-file:not([data-bound])').forEach(btn => {
        btn.dataset.bound = '1';
        btn.addEventListener('click', async () => {
            const levelId  = btn.dataset.level;
            const filename = btn.dataset.filename;
            const ok = await Swal.fire({
                title: 'Hapus file?', text: filename, icon: 'warning',
                showCancelButton: true, confirmButtonText: 'Hapus', cancelButtonText: 'Batal'
            });
            if (ok.isConfirmed) doDelete(levelId, filename);
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-upload-level').forEach(btn => {
        btn.addEventListener('click', () => confirmUpload(btn.dataset.level));
    });
    bindDeleteButtons();
});
</script>
@endsection
