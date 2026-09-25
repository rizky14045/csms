@extends('layout.app')
@section('styles')
<style>
    .pe-grid { border-collapse: separate; border-spacing: 0; }
    .pe-grid th, .pe-grid td { border: 1px solid #dee2e6; text-align: center; padding: 0; }
    .pe-grid thead th { background: #f0f4f7; font-size: 12px; padding: 4px 6px; }
    .pe-cell { width: 26px; min-width: 26px; height: 30px; cursor: pointer; user-select: none; }
    .pe-cell:hover { background: #f8d7da; }
    .pe-cell.on { background: #dc3545; }
    .pe-cell.wk1 { border-left: 2px solid #adb5bd; }
</style>
@stop
@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Laporan Bulanan</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Ubah {{ $title }}</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ $action }}" method="POST" id="form-program-edit" class="my-3"
                      onsubmit="return confirmSave('form-program-edit', 'Perubahan disimpan ke laporan bulanan ini.')">
                    @csrf
                    @method('PATCH')

                    <div class="form-group mb-3 col-xl-8">
                        <label class="form-label"><span class="text-danger">*</span> Nama {{ $title }}</label>
                        <input class="form-control @error('program_name') is-invalid @enderror" type="text" name="program_name" required
                               value="{{ old('program_name', $name) }}">
                        @error('program_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    @if ($cells !== null)
                        <div class="form-group mb-3">
                            <label class="form-label"><span class="text-danger">*</span> Jadwal Rencana</label>
                            <div class="form-text mb-1">Klik kotak minggu untuk memilih / membatalkan (merah = rencana).</div>
                            @php $active = collect(old('cells') ? (json_decode(old('cells'), true) ?: []) : $cells)->map(fn($c) => $c[0] . '-' . $c[1])->all(); @endphp
                            <div style="overflow-x:auto;">
                                <table class="pe-grid">
                                    <thead>
                                        <tr>
                                            @foreach ($months as $month)
                                                <th colspan="{{ $weeks }}">{{ $month }}</th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            @foreach ($months as $month)
                                                @for ($w = 1; $w <= $weeks; $w++)
                                                    <th class="wk{{ $w }}" style="font-weight:normal;">{{ $w }}</th>
                                                @endfor
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @foreach (range(1, 12) as $m)
                                                @for ($w = 1; $w <= $weeks; $w++)
                                                    <td class="pe-cell wk{{ $w }} {{ in_array($m . '-' . $w, $active) ? 'on' : '' }}" data-m="{{ $m }}" data-w="{{ $w }}"
                                                        title="{{ $months[$m - 1] }} minggu {{ $w }}"></td>
                                                @endfor
                                            @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" name="cells" id="cells-input" value="">
                            @error('cells') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    @endif

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="save_to_master" value="1" id="save_to_master" {{ old('save_to_master') ? 'checked' : '' }}>
                        <label class="form-check-label" for="save_to_master">
                            Update juga ke master data
                            @unless ($hasMaster)
                                <span class="text-muted">(master data asal tidak ditemukan{{ $cells !== null ? ', hanya laporan ini yang diubah bila master program juga tidak ada' : ', akan dibuat sebagai master baru' }})</span>
                            @endunless
                        </label>
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('user.monthly-audit.security-program.index', ['monthlyId' => $monthlyId]) }}" class="btn btn-danger">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if ($cells !== null)
<script>
(function () {
    const cells = document.querySelectorAll('.pe-cell');
    const input = document.getElementById('cells-input');

    function sync() {
        const on = [];
        cells.forEach(c => { if (c.classList.contains('on')) on.push([parseInt(c.dataset.m), parseInt(c.dataset.w)]); });
        input.value = JSON.stringify(on);
    }

    let painting = null;
    cells.forEach(c => {
        c.addEventListener('mousedown', e => { e.preventDefault(); painting = !c.classList.contains('on'); c.classList.toggle('on', painting); sync(); });
        c.addEventListener('mouseenter', () => { if (painting !== null) { c.classList.toggle('on', painting); sync(); } });
    });
    document.addEventListener('mouseup', () => { painting = null; });
    sync();
})();
</script>
@endif
@endsection
