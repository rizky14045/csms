@extends('layout.app')
@section('styles')
<style>
    .card-scrollable {
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 180px);
    }
    .card-scrollable .card-body {
        overflow-y: auto;
        flex: 1;
        min-height: 0;
    }
</style>
@stop
@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Securepedia</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Securepedia</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card card-scrollable">
            <div class="d-flex justify-content-between align-items-center pe-3 ps-3 pt-3 gap-2">
                <form method="GET" action="{{ route('user.securepedia.index') }}" class="d-flex gap-2">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control"
                            placeholder="Cari judul..." value="{{ request('q') }}">
                        <button class="btn btn-outline-primary" type="submit">Cari</button>
                    </div>
                    @if(request('q'))
                        <a href="{{ route('user.securepedia.index') }}" class="btn btn-outline-danger">Reset</a>
                    @endif
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle"
                        style="table-layout: fixed; width: 100%;">
                        <colgroup>
                            <col style="width: 50px;">
                            <col>
                            <col style="width: 140px;">
                            <col style="width: 140px;">
                        </colgroup>
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Status Kepemilikan</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($securepedias as $securepedia)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $securepedia->title }}</td>
                                <td>{{ $securepedia->type }}</td>
                                <td>
                                    @if($securepedia->file)
                                        <a href="{{ asset('uploads/securepedia/' . $securepedia->file) }}"
                                            target="_blank" class="btn btn-primary btn-sm">
                                            <i data-feather="download" style="width:13px;height:13px;margin-right:3px;"></i>Unduh
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Tidak ada data.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $securepedias->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
