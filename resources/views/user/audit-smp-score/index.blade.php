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
                @can('create.audit.smp.score.unit')
                <a href="{{route('user.audit-smp-score.create')}}" class="btn btn-primary">Tambah Data</a>
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
                                @canany(['edit.audit.smp.score.unit'])
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
                                    @canany(['view.audit.smp.score.unit', 'edit.audit.smp.score.unit', 'send.audit.smp.score.unit'])                                        
                                    <td class="text-center">
                                        @if($audit->status == 1)
                                        @can('view.audit.smp.score.unit')
                                        <div style="
                                                display:flex;
                                                flex-wrap:wrap;
                                                gap:6px;
                                                justify-content:left;
                                                align-items:center;
                                            ">

                                                {{-- SHOW --}}
                                                <a href="{{route('user.audit-smp-score.show',['audit'=>$audit->id])}}"
                                                class="btn btn-info btn-sm"
                                                style="min-width:80px;">
                                                    👁 Show
                                                </a>
                                        </div>
                                        @endcan
                                        @elseif($audit->status == 0)
                                        <div style="
                                                display:flex;
                                                flex-wrap:wrap;
                                                gap:6px;
                                                justify-content:left;
                                                align-items:center;
                                            ">
                                                @can('view.audit.smp.score.unit')
                                                {{-- SHOW --}}
                                                <a href="{{route('user.audit-smp-score.show',['audit'=>$audit->id])}}"
                                                class="btn btn-info btn-sm"
                                                style="min-width:80px;">
                                                    👁 Show
                                                </a>
                                                @endcan

                                                @can('send.audit.smp.score.unit')
                                                {{-- SEND --}}
                                                @if($audit->get_invalid_items_evidence_by_unit == 0)
                                                <form action="{{route('user.audit-smp-score.send',['audit'=>$audit->id])}}"
                                                    method="post"
                                                    style="margin:0;"
                                                    id="send-audit-{{$audit->id}}"
                                                    onsubmit="confirmSave('send-audit-{{$audit->id}}', 'Kirim Audit?')">

                                                    @csrf
                                                    @method('PATCH')

                                                    <button type="submit"
                                                            class="btn btn-success btn-sm"
                                                            style="min-width:80px;">
                                                        📤 Kirim
                                                    </button>
                                                </form>
                                                @else
                                                <button class="btn btn-secondary btn-sm" style="min-width:80px; opacity:0.6;background-color:gray" disabled>
                                                    📤 Kirim
                                                </button>
                                                @endif 
                                                @endcan                                              
                                        </div>
                                        @elseif($audit->status == 2)
                                        <div style="
                                                display:flex;
                                                flex-wrap:wrap;
                                                gap:6px;
                                                justify-content:left;
                                                align-items:center;
                                            ">

                                                {{-- SHOW --}}
                                                <a href="{{route('auditor.audit-smp-score.show',['audit'=>$audit->id])}}"
                                                class="btn btn-info btn-sm"
                                                style="min-width:80px;">
                                                    👁 Show
                                                </a>
                                        </div>
                                        @if($audit->auditor_lead_id == auth()->user()->id && $audit->status == 2)
                                        <form action="{{route('auditor.audit-smp-score.send',['audit'=>$audit->id])}}"
                                            method="post"
                                            style="margin:0;"
                                            id="send-audit-{{$audit->id}}"
                                            onsubmit="confirmSave('send-audit-{{$audit->id}}', 'Kirim Audit?')">

                                            @csrf

                                            <button type="submit"
                                                    class="btn btn-success btn-sm"
                                                    style="min-width:80px;">
                                                📤 Kirim
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

