@extends('layout.app')
@section('styles')
<style>
    .tl-wrap { overflow-x: auto; }
    .tl-table { border-collapse: separate; border-spacing: 0; width: max-content; min-width: 100%; }
    .tl-table th, .tl-table td { border: 1px solid #dee2e6; padding: 0; text-align: center; }
    .tl-table thead th { background: #f0f4f7; font-size: 12px; padding: 4px 2px; }
    .tl-sticky-1 { position: sticky; left: 0; z-index: 3; background: #fff; }
    .tl-sticky-2 { position: sticky; left: 42px; z-index: 3; background: #fff; }
    thead .tl-sticky-1, thead .tl-sticky-2 { background: #f0f4f7; z-index: 4; }
    .tl-no { width: 42px; min-width: 42px; }
    .tl-name { width: 240px; min-width: 240px; padding: 4px !important; }
    .tl-name input { width: 100%; }
    .tl-cell { width: 22px; min-width: 22px; height: 34px; cursor: pointer; user-select: none; }
    .tl-cell.wk1 { border-left: 2px solid #adb5bd !important; }
    .tl-cell:hover { background: #ffe3e6; }
    .tl-cell.on { background: #dc3545; }
    .tl-cell.readonly { cursor: default; }
    .tl-action { min-width: 150px; white-space: nowrap; padding: 4px !important; }
    .tl-row.dirty .tl-name input { border-color: #ffc107; }
    .tl-hint { font-size: 13px; }
</style>
@stop
@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Program Keamanan: {{ $securityProgram->program_name }} ({{ $securityProgram->year }})</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('user.security-program.index') }}">Program Keamanan</a></li>
            <li class="breadcrumb-item active">Timeline</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center pe-3 ps-3 pt-3 gap-2 flex-wrap">
                <a href="{{ route('user.security-program.index') }}" class="btn btn-danger">Kembali</a>
                <div class="d-flex gap-2">
                    @can('create.main.security.program.unit')
                    <button type="button" class="btn btn-success" id="btn-add-row">+ Tambah Program</button>
                    @endcan
                </div>
            </div>

            <div class="card-body">

                <div class="tl-wrap">
                    <table class="tl-table" id="tl-table">
                        <thead>
                            <tr>
                                <th rowspan="2" class="tl-sticky-1 tl-no align-middle">No</th>
                                <th rowspan="2" class="tl-sticky-2 tl-name align-middle">Program</th>
                                @foreach($months as $month)
                                    <th colspan="{{ $weeks }}">{{ $month }}</th>
                                @endforeach
                                <th rowspan="2" class="tl-action align-middle">Action</th>
                            </tr>
                            <tr>
                                @foreach($months as $mi => $month)
                                    @for($w = 1; $w <= $weeks; $w++)
                                        <th class="tl-cell wk{{ $w }}" style="cursor:default;height:auto;">{{ $w }}</th>
                                    @endfor
                                @endforeach
                            </tr>
                        </thead>
                        <tbody id="tl-body"></tbody>
                    </table>
                </div>
                <div id="tl-empty" class="text-center text-muted py-4" style="display:none;">Belum ada program. Klik "+ Tambah Program".</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    const WEEKS = {{ $weeks }};
    const MONTHS = @json($months);
    const CSRF = '{{ csrf_token() }}';
    const STORE_URL = '{{ route('user.main-security-program.store', ['program' => $programId]) }}';
    const can = {
        create: {{ auth()->user()->can('create.main.security.program.unit') ? 'true' : 'false' }},
        edit: {{ auth()->user()->can('edit.main.security.program.unit') ? 'true' : 'false' }},
        del: {{ auth()->user()->can('delete.main.security.program.unit') ? 'true' : 'false' }},
    };
    const initial = @json($rows);

    const body = document.getElementById('tl-body');
    const emptyMsg = document.getElementById('tl-empty');
    let painting = null; // {row, state}

    function key(m, w) { return m + '-' + w; }

    function makeRow(data) {
        const tr = document.createElement('tr');
        tr.className = 'tl-row';
        tr.dataset.id = data.id || '';
        tr.dataset.updateUrl = data.update_url || '';
        tr.dataset.destroyUrl = data.destroy_url || '';
        tr._cells = new Set((data.cells || []).map(c => key(c[0], c[1])));
        tr._dirty = !data.id;

        const editable = data.id ? can.edit : can.create;

        let html = '<td class="tl-sticky-1 tl-no align-middle row-no"></td>' +
            '<td class="tl-sticky-2 tl-name"><input type="text" class="form-control form-control-sm row-name" placeholder="Nama program" ' + (editable ? '' : 'readonly') + '></td>';
        for (let m = 1; m <= 12; m++) {
            for (let w = 1; w <= WEEKS; w++) {
                html += '<td class="tl-cell wk' + w + (editable ? '' : ' readonly') + '" data-m="' + m + '" data-w="' + w + '" title="' + MONTHS[m - 1] + ' minggu ' + w + '"></td>';
            }
        }
        html += '<td class="tl-action">' +
            (editable ? '<button type="button" class="btn btn-sm btn-success btn-save me-1">Simpan</button>' : '') +
            (can.del ? '<button type="button" class="btn btn-sm btn-outline-danger btn-del">Hapus</button>' : '') + '</td>';
        tr.innerHTML = html;
        tr.querySelector('.row-name').value = data.name || '';
        paint(tr);
        refreshState(tr);
        return tr;
    }

    function paint(tr) {
        tr.querySelectorAll('.tl-cell').forEach(td => {
            td.classList.toggle('on', tr._cells.has(key(td.dataset.m, td.dataset.w)));
        });
    }

    function refreshState(tr) {
        tr.classList.toggle('dirty', tr._dirty);
        const btn = tr.querySelector('.btn-save');
        if (btn) {
            btn.classList.toggle('btn-warning', tr._dirty);
            btn.classList.toggle('btn-success', !tr._dirty);
            btn.textContent = tr._dirty ? 'Simpan *' : 'Simpan';
        }
    }

    function renumber() {
        const rows = body.querySelectorAll('tr');
        rows.forEach((tr, i) => tr.querySelector('.row-no').textContent = i + 1);
        emptyMsg.style.display = rows.length ? 'none' : 'block';
    }

    function payload(tr) {
        const cells = Array.from(tr._cells).map(k => k.split('-').map(Number));
        return { program_name: tr.querySelector('.row-name').value.trim(), cells: cells };
    }

    function toast(icon, title) {
        Swal.fire({ toast: true, position: 'top-end', icon: icon, title: title, showConfirmButton: false, timer: 2200 });
    }

    async function save(tr) {
        const p = payload(tr);
        if (!p.program_name) { toast('warning', 'Nama program wajib diisi.'); tr.querySelector('.row-name').focus(); return; }
        if (!p.cells.length) { toast('warning', 'Pilih minimal satu minggu pada timeline.'); return; }

        const isNew = !tr.dataset.id;
        const btn = tr.querySelector('.btn-save');
        btn.disabled = true;
        try {
            const res = await fetch(isNew ? STORE_URL : tr.dataset.updateUrl, {
                method: isNew ? 'POST' : 'PATCH',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify(p),
            });
            const r = await res.json();
            if (!res.ok || !r.success) { Swal.fire('Gagal', r.message || 'Gagal menyimpan program.', 'error'); return; }

            tr.dataset.id = r.id;
            tr.dataset.updateUrl = r.update_url;
            tr.dataset.destroyUrl = r.destroy_url;
            tr._cells = new Set(r.cells.map(c => key(c[0], c[1])));
            tr._dirty = false;
            paint(tr);
            refreshState(tr);
            toast('success', 'Tersimpan');
        } catch (e) {
            Swal.fire('Error', 'Server error saat menyimpan.', 'error');
        } finally {
            btn.disabled = false;
        }
    }

    function remove(tr) {
        const done = () => { tr.remove(); renumber(); };
        if (!tr.dataset.id) { done(); return; }
        Swal.fire({ title: 'Hapus program?', text: 'Program dan jadwalnya akan dihapus.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal', reverseButtons: true })
            .then(async (result) => {
                if (!result.isConfirmed) return;
                const res = await fetch(tr.dataset.destroyUrl, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } });
                if (res.ok) { done(); toast('success', 'Terhapus'); } else { Swal.fire('Gagal', 'Program gagal dihapus.', 'error'); }
            });
    }

    function markDirty(tr) { tr._dirty = true; refreshState(tr); }

    // Klik / seret untuk mengecat sel jadwal (hanya dalam satu baris)
    body.addEventListener('mousedown', function (e) {
        const td = e.target.closest('.tl-cell');
        if (!td || td.classList.contains('readonly')) return;
        e.preventDefault();
        const tr = td.parentElement;
        const k = key(td.dataset.m, td.dataset.w);
        const state = !tr._cells.has(k);
        painting = { row: tr, state: state };
        apply(tr, td, state);
    });
    body.addEventListener('mouseover', function (e) {
        if (!painting) return;
        const td = e.target.closest('.tl-cell');
        if (!td || td.parentElement !== painting.row || td.classList.contains('readonly')) return;
        apply(painting.row, td, painting.state);
    });
    document.addEventListener('mouseup', function () { painting = null; });

    function apply(tr, td, state) {
        const k = key(td.dataset.m, td.dataset.w);
        if (state) tr._cells.add(k); else tr._cells.delete(k);
        td.classList.toggle('on', state);
        markDirty(tr);
    }

    body.addEventListener('input', function (e) {
        if (e.target.classList.contains('row-name')) markDirty(e.target.closest('tr'));
    });
    body.addEventListener('click', function (e) {
        const tr = e.target.closest('tr');
        if (!tr) return;
        if (e.target.closest('.btn-save')) save(tr);
        if (e.target.closest('.btn-del')) remove(tr);
    });

    const addBtn = document.getElementById('btn-add-row');
    if (addBtn) {
        addBtn.addEventListener('click', function () {
            const tr = makeRow({ id: null, name: '', cells: [] });
            body.appendChild(tr);
            renumber();
            tr.querySelector('.row-name').focus();
            tr.scrollIntoView({ block: 'nearest' });
        });
    }

    initial.forEach(r => {
        r.update_url = '{{ url('user/main-security-program/' . $programId) }}/' + r.id + '/update';
        r.destroy_url = '{{ url('user/main-security-program/' . $programId) }}/' + r.id + '/destroy';
        body.appendChild(makeRow(r));
    });
    renumber();
})();
</script>
@endsection
