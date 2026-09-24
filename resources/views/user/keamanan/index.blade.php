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
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">KPI</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">

                <div class="card-body">
                    <div class="d-flex justify-content-between w-100">
                        <div class="find-data col-md-6">
                            <form action="">
                                <label for="" class="form-label">Cari Data</label>
                                <div class="d-flex gap-3">
                                    <div class="mb-3 col-md-3">
                                        <input type="date" class="form-control d-inline" id="date" name="date"
                                            value="{{ request('date', '') }}">
                                    </div>
                                    <div class="button-search">
                                        <button type="submit" class="btn btn-primary d-inline">Cari</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @can('create.security.kpi.unit')
                            <div class="button-add col-md-12">
                                <div class="d-flex justify-content-end pe-3 pt-3 col-md-6">
                                    <a href="{{ route('user.keamanan.create') }}" class="btn btn-success">Tambah Data</a>
                                </div>
                            </div>
                        @endcan
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Tahun</th>
                                    <th scope="col">Triwulan</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Tgl Kirim ke MMRK</th>
                                    <th scope="col">Tgl Kirim ke Pusat</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kpis as $kpi)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $kpi->year }}</td>
                                        <td>{{ $kpi->period_label }}</td>
                                        <td>@include('components.flow-status', ['status' => $kpi->status ?? 0])</td>
                                        <td>{{ !empty($kpi->mmrk_send_date) ? \Carbon\Carbon::parse($kpi->mmrk_send_date)->format('d-m-Y') : '-' }}</td>
                                        <td>{{ $kpi->send_date ? \Carbon\Carbon::parse($kpi->send_date)->format('d-m-Y') : '-' }}
                                        </td>
                                        <td>
                                            <div
                                                style="
                                                display:flex;
                                                flex-wrap:wrap;
                                                gap:6px;
                                                justify-content:left;
                                                align-items:center;
                                            ">

                                                @if ($kpi->send_status == false)
                                                    {{-- SHOW --}}
                                                    <a href="{{ route('user.keamanan.show', ['kpi' => $kpi->id]) }}"
                                                        class="btn btn-info btn-sm" style="min-width:80px;">
                                                        👁 Show
                                                    </a>
                    @include('components.assessment-export-buttons', ['kind' => 'kpi', 'item' => $kpi])
                    @include('components.status-history-button', ['type' => 'kpi', 'id' => $kpi->id])

                                                    {{-- SEND --}}
                                                    @can('send.security.kpi.unit')
                                                        {{--
                                                        Dinonaktifkan sesuai permintaan: KPI boleh dikirim
                                                        walau belum semua catatan/evidence terisi.
                                                        (catatan: kondisi ini juga selalu false di PHP karena
                                                        membandingkan array/collection dengan int pakai ==,
                                                        jadi sebelumnya tombol Kirim selalu ter-disable permanen)
                                                        @if ($kpi->get_invalid_items_notes_by_unit == 0)
                                                            <form action="{{ route('user.keamanan.send', ['kpi' => $kpi->id]) }}"
                                                                method="post" style="margin:0;"
                                                                id="send-kpi-{{ $kpi->id }}"
                                                                onsubmit="confirmSave('send-kpi-{{ $kpi->id }}', 'Kirim KPI?')">

                                                                @csrf
                                                                @method('PATCH')

                                                                <button type="submit" class="btn btn-success btn-sm"
                                                                    style="min-width:80px;">
                                                                    📤 Kirim
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button class="btn btn-secondary btn-sm"
                                                                style="min-width:80px; opacity:0.6;background-color:gray"
                                                                disabled>
                                                                📤 Kirim
                                                            </button>
                                                        @endif
                                                        --}}

                                                        @if (($kpi->status ?? 0) == 0)
                                                        <form action="{{ route('user.keamanan.send', ['kpi' => $kpi->id]) }}"
                                                            method="post" style="margin:0;"
                                                            id="send-kpi-{{ $kpi->id }}"
                                                            onsubmit="confirmSave('send-kpi-{{ $kpi->id }}', 'Kirim KPI?')">

                                                            @csrf
                                                            @method('PATCH')

                                                            <button type="submit" class="btn btn-success btn-sm"
                                                                style="min-width:80px;">
                                                                📤 Kirim
                                                            </button>
                                                        </form>
                                                        @endif
                                                    @endcan
                                                @else
                                                    {{-- PREVIEW --}}
                                                    <a href="{{ route('user.keamanan.preview', ['kpi' => $kpi->id]) }}"
                                                        class="btn btn-info btn-sm" style="min-width:80px;">
                                                        👁 Show
                                                    </a>
                    @include('components.assessment-export-buttons', ['kind' => 'kpi', 'item' => $kpi])
                    @include('components.status-history-button', ['type' => 'kpi', 'id' => $kpi->id])
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div> <!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div> <!-- end row -->
@endsection
