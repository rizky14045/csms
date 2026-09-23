@extends('layout.app')
@section('styles')
<style>
    .accordion-button::after { filter: invert(100%); }
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

                <div class="d-flex gap-2 mb-3">
                    <a href="{{ $backUrl }}" class="btn btn-danger">Kembali</a>
                    @if($mode !== 'mmrk' && auth()->user()->can('view.security.kpi.admin'))
                    <a href="{{ route('admin.keamanan.export', $kpi->id) }}" class="btn btn-success">⬇ Export Excel</a>
                    @endif
                    @if($mode === 'pusat')
                    <form id="form-finish-validasi" action="{{ route('admin.keamanan.finish', $kpi->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirmAction('form-finish-validasi', 'Selesaikan Validasi?', 'Setelah selesai, centang tidak bisa diubah lagi.', 'Ya, Selesaikan')">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-primary">✔ Selesaikan Validasi</button>
                    </form>
                    @endif
                    @if($mode === 'mmrk' && (int) $kpi->status === 1)
                    <form id="form-send-pusat" action="{{ route('mmrk.keamanan.send', $kpi->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirmAction('form-send-pusat', 'Kirim ke Pusat?', 'Setelah dikirim, data tidak bisa diedit lagi oleh Unit maupun MMRK.', 'Ya, Kirim')">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-primary">📤 Kirim ke Pusat</button>
                    </form>
                    @endif
                </div>

                @php
                    $isAdminUser = auth()->user()->can('view.security.kpi.admin');
                    $showActual = ($mode === 'pusat') || ($mode === 'view' && ((int) $kpi->status === 3 || ($isAdminUser && (int) $kpi->status >= 2)));
                    $canCheck    = $mode === 'pusat';
                    $grandActual = $actual['total'] ?? 0;
                @endphp

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
                                data-bs-target="#collapse{{ $area['id'] }}">
                            {{ $area['name'] }}
                        </button>
                    </h2>

                    <div id="collapse{{ $area['id'] }}"
                         class="accordion-collapse collapse {{ request('areaId') == $area['id'] ? 'show' : '' }}"
                         data-bs-parent="#formAccordion">
                        <div class="accordion-body" style="overflow-x:auto;">

                            <table class="table table-bordered align-middle" style="min-width:900px;">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width:45px;"  class="text-center">No</th>
                                        <th style="min-width:200px;" class="text-center">Sub Area</th>
                                        <th style="min-width:55px;"  class="text-center">Level</th>
                                        <th style="min-width:220px;" class="text-center">Uraian</th>
                                        <th style="min-width:160px;" class="text-center">File Evidence</th>
                                        <th style="min-width:80px;"  class="text-center">Bobot</th>
                                        <th style="min-width:90px;"  class="text-center">Hasil Assesment</th>
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

                                    <td class="text-center">
                                        @if(!empty($level['attachment_file']))
                                        @php
                                            $isChecked = isset($checked[$level['id']]);
                                            $lvlActual = $actual['subAreas'][$subArea['id']]['levels'][$level['id']] ?? ['canCheck' => false];
                                        @endphp
                                        @if($showActual)
                                        <input type="checkbox" class="form-check-input chk-file me-2"
                                               data-url="{{ route('admin.keamanan.check', ['kpi' => $kpi->id, 'level' => $level['id']]) }}"
                                               data-level-id="{{ $level['id'] }}"
                                               data-area="{{ $area['id'] }}"
                                               {{ $isChecked ? 'checked' : '' }}
                                               {{ ($canCheck && ($isChecked || $lvlActual['canCheck'])) ? '' : 'disabled' }}>
                                        @endif
                                        <a href="{{ asset('uploads/attachment_file_kpi_file/' . $level['attachment_file']) }}"
                                           target="_blank"
                                           class="btn btn-success btn-sm"
                                           style="font-size:11px;">
                                           ⬇ Download File
                                        </a>
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

        </div>

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
                                    <td class="text-center fw-bold">{{ round($grandTotalBobot, 4) }}</td>
                                </tr>
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

    function keyOf(chk) { return chk.dataset.levelId; }

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
                    body: JSON.stringify({checked: chk.checked})
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
