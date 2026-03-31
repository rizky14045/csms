@extends('layout.app')
@section('styles')

@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Data Audit</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Audit</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="d-flex justify-content-end pe-3 pt-3">
                @can('create.audit.smp.score.admin')
                <a href="{{route('admin.audit-smp-score.create')}}" class="btn btn-primary">Tambah Data</a>
                @endcan
            </div>
            <div class="card-body">  
                <div class="table-responsive">
                    @if(count($audits) == 0)
                        <div class="text-center">
                            <p class="mb-0">Tidak ada data audit.</p>
                        </div>
                    @else
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama Unit</th>
                                <th scope="col">Tanggal Mulai</th>
                                <th scope="col">Tanggal Selesai</th>
                                @canany(['edit.audit.smp.score.admin', 'delete.audit.smp.score.admin'])
                                <th scope="col">Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($audits as $audit)    
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$audit->unit->name ?? "-"}}</td>
                                    <td>{{ \Carbon\Carbon::parse($audit->start_audit)->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($audit->end_audit)->format('d-m-Y') }}</td>
                                    @canany(['view.audit.smp.score.admin', 'edit.audit.smp.score.admin', 'delete.audit.smp.score.admin'])                                        
                                    <td class="text-center">
                                        @can('view.audit.smp.score.admin')
                                        <a href="{{route('admin.audit-smp-score.show',['audit'=>$audit->id])}}" class="btn btn-primary btn-sm">View</a>
                                        @endcan
                                        @if($audit->status == 0)
                                        @can('edit.audit.smp.score.admin')
                                        <a href="{{route('admin.audit-smp-score.edit',['audit'=>$audit->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                        @endcan
                                        @can('delete.audit.smp.score.admin')
                                        <form
                                            id="delete-audit-{{ $audit->id }}"
                                            action="{{route('admin.audit-smp-score.destroy',['audit'=>$audit->id])}}"
                                            method="POST"
                                            class="d-inline"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                onclick="confirmDelete(
                                                    'delete-audit-{{ $audit->id }}',
                                                    'Audit akan dihapus.'
                                                )"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                        @endcan
                                        @if($audit->auditor_lead_id == auth()->user()->id && $audit->status == 0)
                                        <form
                                            id="send-audit-{{ $audit->id }}"
                                            action="{{route('admin.audit-smp-score.send',['audit'=>$audit->id])}}"
                                            method="POST"
                                            class="d-inline"
                                        >
                                            @csrf

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                onclick="confirmDelete(
                                                    'send-audit-{{ $audit->id }}',
                                                    'Audit akan dikirim.'
                                                )"
                                            >
                                                Kirim
                                            </button>
                                        </form>
                                        @endif
                                        @endif
                                    </td>
                                    @endcanany
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$audits->links()}}
                    @endif
                </div>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

