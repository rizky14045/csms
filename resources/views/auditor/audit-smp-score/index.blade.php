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
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($audits as $audit)    
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$audit->unit->name ?? "-"}}</td>
                                    <td>{{ \Carbon\Carbon::parse($audit->start_audit)->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($audit->end_audit)->format('d-m-Y') }}</td>
                                    <td class="text-center">
                                        @can('view.audit.smp.score.auditor')
                                        <a href="{{route('auditor.audit-smp-score.show',['audit'=>$audit->id])}}" class="btn btn-primary btn-sm">View</a>
                                        @endcan
                                        @if($audit->auditor_lead_id == auth()->user()->id && $audit->status == 0)
                                        <form
                                            id="send-audit-{{ $audit->id }}"
                                            action="{{route('auditor.audit-smp-score.send',['audit'=>$audit->id])}}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="confirmSave('send-audit-{{ $audit->id }}', 'Audit akan dikirim.')"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-success btn-sm"
                                            >
                                                Kirim
                                            </button>
                                        </form>
                                        @endif
                                    </td>
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

