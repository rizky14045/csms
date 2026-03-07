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
            <h4 class="fs-18 fw-semibold m-0">Audit SMP</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.home.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Data Audit SMP</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end pe-3">
                        {{-- @can('view.kpi.area') --}}
                        <a href="{{ route('admin.audit-smp.create') }}" class="btn btn-sm btn-primary mb-3">Tambah</a>
                        {{-- @endcan --}}
                    </div>
                    <!-- Komitmen Management -->
                    <div class="accordion" id="formAccordion">

                        @foreach ($audits as $audit)
                            <div class="accordion-item">

                                {{-- ================= HEADER ================= --}}
                                <h2 class="accordion-header" id="heading{{ $audit['id'] }}">

                                    <div class="d-flex justify-content-between align-items-center w-100">

                                        {{-- BUTTON ACCORDION --}}
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $audit['id'] }}" aria-expanded="false"
                                            aria-controls="collapse{{ $audit['id'] }}">

                                            <span class="fw-bold">
                                                {{ $audit['name'] }} - Bobot : {{ $audit['bobot'] }} %
                                            </span>
                                        </button>

                                        <div class="d-flex align-items-center gap-2 ms-2">

                                            {{-- @can('edit.kpi.area') --}}
                                            <a href="{{ route('admin.audit-smp.edit', ['audit' => $audit['id']]) }}"
                                                class="btn btn-warning btn-sm">
                                                Edit
                                            </a>
                                            {{-- @endcan --}}

                                            {{-- @can('delete.kpi.area') --}}
                                            <form id="delete-audit-header{{ $audit['id'] }}"
                                                action="{{ route('admin.audit-smp.destroy', ['audit' => $audit['id']]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete(
                            'delete-audit-header{{ $audit['id'] }}',
                            'Audit SMP akan dihapus.'
                        )">
                                                    Hapus
                                                </button>
                                            </form>
                                            {{-- @endcan --}}

                                        </div>

                                    </div>
                                </h2>

                                {{-- ================= BODY ================= --}}
                                <div id="collapse{{ $audit['id'] }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $audit['id'] }}" data-bs-parent="#formAccordion">

                                    <div class="accordion-body">

                                        {{-- @foreach ($audit['child'] as $child) --}}

                                        @if (!empty($audit['kriteria']))
                                            {{-- @can('create.kpi.subarea') --}}
                                            <a href="{{ route('admin.audit-smp.createElement', ['auditId' => $audit['id']]) }}"
                                                class="btn btn-primary btn-sm mb-3">
                                                Tambah Data
                                            </a>
                                            {{-- @endcan --}}

                                            <table class="table table-bordered text-center">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama</th>
                                                        <th width="260">Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>

                                                    @foreach ($audit['kriteria'] as $kriteria)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td class="text-start">{{ $kriteria['name'] }}</td>

                                                            <td>
                                                                <div class="d-flex flex-wrap gap-2">

                                                                    {{-- @can('create.kpi.level') --}}
                                                                    <a href="{{ route('admin.audit-smp.createEvident', ['auditId' => $kriteria['id']]) }}"
                                                                        class="btn btn-success btn-sm">
                                                                        Tambah Evident
                                                                    </a>
                                                                    {{-- @endcan --}}

                                                                    {{-- @can('edit.kpi.subarea') --}}
                                                                    <a href="{{ route('admin.audit-smp.editElement', ['auditId' => $audit['id'], 'elementId' => $kriteria['id']]) }}"
                                                                        class="btn btn-warning btn-sm">
                                                                        Edit
                                                                    </a>
                                                                    {{-- @endcan --}}

                                                                    {{-- @can('delete.kpi.subarea') --}}
                                                                    <form
                                                                        id="delete-audit-smp-element-{{ $kriteria['id'] }}"
                                                                        action="{{ route('admin.audit-smp.deleteElement', ['elementId' => $kriteria['id'], 'auditId' => $audit['id']]) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <button type="button" class="btn btn-danger btn-sm"
                                                                            onclick="confirmDelete(
                                                'delete-audit-smp-element-{{ $kriteria['id'] }}',
                                                'Element akan dihapus.'
                                            )">
                                                                            Hapus
                                                                        </button>
                                                                    </form>
                                                                    {{-- @endcan --}}

                                                                </div>
                                                            </td>
                                                        </tr>

                                                        {{-- ================= LEVEL (COMMENT TETAP) ================= --}}
                                                        {{--
                        @can('view.kpi.level')
                        <tr id="accordionRow{{$subArea['id']}}" class="collapse accordion-content">
                            ....
                        </tr>
                        @endcan
                        --}}
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        @else
                                            <div class="text-muted">
                                                Belum ada data kriteria.
                                            </div>
                                        @endif

                                        {{-- @endforeach --}}

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
