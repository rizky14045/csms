@extends('layout.app')
@section('styles')
<style>
    .accordion-button::after { filter: invert(100%); }
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

                <div class="d-flex gap-2 mb-3">
                    <a href="{{ $backUrl }}" class="btn btn-danger">Kembali</a>
                    @include('components.assessment-export-buttons', ['kind' => 'marturity', 'item' => $marturity])
                    @if($mode === 'pusat')
                    <form id="form-finish-validasi" action="{{ route('admin.marturity.finish', $marturity->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirmAction('form-finish-validasi', 'Selesaikan Validasi?', 'Setelah selesai, centang tidak bisa diubah lagi.', 'Ya, Selesaikan')">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-primary">✔ Selesaikan Validasi</button>
                    </form>
                    @endif
                    @if($mode === 'mmrk' && (int) $marturity->status === 1)
                    <form id="form-send-pusat" action="{{ route('mmrk.marturity.send', $marturity->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirmAction('form-send-pusat', 'Kirim ke Pusat?', 'Setelah dikirim, data tidak bisa diedit lagi oleh Unit maupun MMRK.', 'Ya, Kirim')">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-primary">📤 Kirim ke Pusat</button>
                    </form>
                    @endif
                </div>

                @php
                    $isAdminUser = auth()->user()->can('view.marturity.admin');
                    $showActual = ($mode === 'pusat') || ($mode === 'view' && ((int) $marturity->status === 3 || ($isAdminUser && (int) $marturity->status >= 2)));
                    $canCheck   = $mode === 'pusat';
                    $grandActual = $actual['total'] ?? 0;
                @endphp

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

                            <table class="table table-bordered align-middle" style="min-width:1100px;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width:50px;"  class="text-center">No</th>
                                        <th style="min-width:200px;" class="text-center">Sub Area</th>
                                        <th style="min-width:55px;"  class="text-center">Level</th>
                                        <th style="min-width:230px;" class="text-center">Uraian</th>
                                        <th style="min-width:220px;" class="text-center">Note / Evidence</th>
                                        <th style="min-width:95px;"  class="text-center">Total Evidence</th>
                                        <th style="min-width:95px;"  class="text-center">Jumlah Evidence</th>
                                        <th style="min-width:220px;" class="text-center">File Evidence</th>
                                        <th style="min-width:80px;"  class="text-center">Bobot</th>
                                        <th style="min-width:80px;"  class="text-center">Hasil</th>
                                        <th style="min-width:90px;"  class="text-center">Score ML</th>
                                        @if($showActual)
                                        <th style="min-width:90px;"  class="text-center">Hasil Aktual</th>
                                        <th style="min-width:100px;" class="text-center">Score ML Aktual</th>
                                        @endif
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
                                    <td style="white-space:normal; text-align:left;">
                                        @forelse (($lc['lvl']['notes'] ?? []) as $noteRow)
                                            <div class="{{ !$loop->last ? 'mb-2 pb-2 border-bottom' : '' }}">{!! nl2br(e($noteRow['note'] ?? '')) !!}</div>
                                        @empty
                                            <span class="text-muted">-</span>
                                        @endforelse
                                    </td>
                                    <td class="text-center">{{ $lc['totalEv'] }}</td>
                                    <td class="text-center">{{ $lc['jumlah'] }}</td>

                                    <td>
                                        @if(count($lc['files']) > 0)
                                        @php $lvlActual = $actual['subAreas'][$subArea['id']]['levels'][$lc['lvl']['id']] ?? ['canCheck' => false]; @endphp
                                        <div class="d-flex flex-column gap-1">
                                            @foreach ($lc['files'] as $fi => $file)
                                            @php $isChecked = isset($checked[$lc['lvl']['id'] . '|' . $file]); @endphp
                                            <div class="d-flex align-items-center gap-2">
                                                @if($showActual)
                                                <input type="checkbox" class="form-check-input chk-file"
                                                       data-url="{{ route('admin.marturity.check', ['marturity' => $marturity->id, 'level' => $lc['lvl']['id']]) }}"
                                                       data-filename="{{ $file }}"
                                                       data-level-id="{{ $lc['lvl']['id'] }}"
                                                       data-area="{{ $area['id'] }}"
                                                       {{ $isChecked ? 'checked' : '' }}
                                                       {{ ($canCheck && ($isChecked || $lvlActual['canCheck'])) ? '' : 'disabled' }}>
                                                @endif
                                                <a href="{{ asset('uploads/attachment_file_marturity_file/' . $file) }}"
                                                   target="_blank"
                                                   class="btn btn-success btn-sm"
                                                   style="font-size:11px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                                   ⬇ File {{ $fi + 1 }}
                                                </a>
                                            </div>
                                            @endforeach
                                        </div>
                                        @else
                                        <span class="text-muted small">-</span>
                                        @endif
                                    </td>

                                    @if ($idx === 0)
                                    <td class="text-center" rowspan="{{ $levelCount }}">{{ round($bobot, 4) }}</td>
                                    <td class="text-center fw-semibold" rowspan="{{ $levelCount }}">{{ $hasil }}</td>
                                    <td class="text-center fw-semibold" rowspan="{{ $levelCount }}">{{ $scoreML }}</td>
                                    @if($showActual)
                                    <td class="text-center fw-semibold" rowspan="{{ $levelCount }}" id="aktual-hasil-{{ $subArea['id'] }}">{{ $actual['subAreas'][$subArea['id']]['hasil'] ?? 0 }}</td>
                                    <td class="text-center fw-semibold text-success" rowspan="{{ $levelCount }}" id="aktual-score-{{ $subArea['id'] }}">{{ $actual['subAreas'][$subArea['id']]['score'] ?? 0 }}</td>
                                    @endif
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
                                    <td>Total Score ML</td>
                                    <td class="text-center fw-bold text-primary">{{ round($grandTotalML, 4) }}</td>
                                </tr>
                                @if($showActual)
                                <tr>
                                    <td>Total Score ML Aktual (hasil cek Pusat)</td>
                                    <td class="text-center fw-bold text-success" id="grand-aktual">{{ round($grandActual, 4) }}</td>
                                </tr>
                                @endif
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
@if($canCheck)
<script>
(function () {
    const boxes = Array.from(document.querySelectorAll('.chk-file'));

    function keyOf(chk) { return chk.dataset.levelId + '|' + chk.dataset.filename; }

    function applyState(state) {
        // PHP mengirim array kosong ([]) alih-alih object ({}) ketika semua
        // checklist ter-uncheck (json_encode tidak bisa membedakan array
        // asosiatif kosong dari array biasa) — normalisasi supaya lookup-nya eksplisit.
        const checkedMap = (state.checked && !Array.isArray(state.checked)) ? state.checked : {};

        const canMap = {};
        Object.values(state.actual.subAreas || {}).forEach(sa => {
            Object.entries(sa.levels || {}).forEach(([lvlId, info]) => { canMap[lvlId] = info.canCheck; });
        });

        boxes.forEach(chk => {
            const isChecked = !!checkedMap[keyOf(chk)];
            chk.checked = isChecked;
            chk.disabled = !(isChecked || canMap[chk.dataset.levelId]);
        });

        Object.entries(state.actual.subAreas || {}).forEach(([id, sa]) => {
            const h = document.getElementById('aktual-hasil-' + id);
            const s = document.getElementById('aktual-score-' + id);
            if (h) h.textContent = sa.hasil;
            if (s) s.textContent = sa.score;
        });

        const g = document.getElementById('grand-aktual');
        if (g) g.textContent = Math.round(state.actual.total * 10000) / 10000;
    }

    boxes.forEach(chk => {
        chk.addEventListener('change', async () => {
            const before = boxes.map(b => b.disabled);
            const intended = chk.checked;
            boxes.forEach(b => b.disabled = true);

            try {
                const res = await fetch(chk.dataset.url, {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json'},
                    body: JSON.stringify({filename: chk.dataset.filename, checked: chk.checked})
                });
                const result = await res.json();

                if (!res.ok) {
                    Swal.fire('Gagal', result.message || 'Gagal menyimpan centang', 'error');
                    chk.checked = !intended;
                    boxes.forEach((b, i) => b.disabled = before[i]);
                    return;
                }

                applyState(result);
            } catch (e) {
                Swal.fire('Error', 'Server error', 'error');
                chk.checked = !intended;
                boxes.forEach((b, i) => b.disabled = before[i]);
            }
        });
    });
})();
</script>
@endif
@endsection
