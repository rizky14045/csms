{{-- @extends('layout.app')

@section('styles') 
<style>
   /* General Accordion Styling */
   .accordion-item {
      border: 1px solid #eef2f7;
      border-radius: 12px !important;
      margin-bottom: 1rem;
      overflow: hidden;
      box-shadow: 0 2px 5px rgba(0,0,0,0.02);
   }

   .accordion-button {
      padding: 1rem 1.25rem;
      font-weight: 600;
      color: #343a40;
   }

   .accordion-button:not(.collapsed) {
      background-color: #f8faff;
      box-shadow: none;
   }

   /* Level Accents - Agar mata mudah membedakan tingkatan */
   .audit-item { border-left: 6px solid #4e73df; }
   .pernyataan-item { border-left: 5px solid #1cc88a; margin-top: 10px; }
   .kriteria-item { border-left: 4px solid #f6c23e; margin-bottom: 8px; }

   /* Button Styling - Perbaikan Masalah "Dempet" */
   .btn-action-group {
      display: flex;
      gap: 8px; /* Ini yang bikin tombol tidak dempet */
      align-items: center;
   }

   .btn-xs {
      padding: 4px 10px;
      font-size: 11px;
      border-radius: 6px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.3px;
   }

   /* Soft Button Colors */
   .btn-soft-primary { background: #e8eeff; color: #4e73df; border: 1px solid #d1dfff; }
   .btn-soft-primary:hover { background: #4e73df; color: white; }

   .btn-soft-success { background: #e3f9ef; color: #1cc88a; border: 1px solid #c9f2de; }
   .btn-soft-success:hover { background: #1cc88a; color: white; }

   .btn-soft-warning { background: #fff9e6; color: #f6c23e; border: 1px solid #ffecb3; }
   .btn-soft-warning:hover { background: #f6c23e; color: white; }

   .btn-soft-danger { background: #ffeef0; color: #e74a3b; border: 1px solid #ffdadd; }
   .btn-soft-danger:hover { background: #e74a3b; color: white; }

   /* Evident Item Styling */
   .evident-item {
      border: 1px dashed #d1d3e2;
      border-radius: 8px;
      padding: 12px 15px;
      margin-bottom: 8px;
      background: #fafafa;
      transition: all 0.2s;
   }
   .evident-item:hover { background: #f8f9fc; border-color: #4e73df; }

   .badge-bobot {
      background: #4e73df;
      color: white;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 12px;
      margin-left: 10px;
   }

   .label-section {
      font-size: 11px;
      font-weight: 800;
      color: #b7b9cc;
      text-transform: uppercase;
      margin-bottom: 10px;
      display: block;
   }
</style>
@stop

@section('content') 
<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
   <div class="flex-grow-1">
      <h4 class="fs-18 fw-bold m-0 text-primary">Audit SMP</h4>
   </div>
   <div class="text-end">
      <ol class="breadcrumb m-0 py-0">
         <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
         <li class="breadcrumb-item active">Data Audit SMP</li>
      </ol>
   </div>
</div>

<div class="row">
   <div class="col-xl-12">
      <div class="card shadow-sm border-0">
         <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
               <h5 class="m-0 fw-bold text-dark">Struktur Instrumen</h5>
               @can('create.audit.smp.admin')
               <a href="{{ route('admin.audit-smp.create') }}" class="btn btn-primary shadow-sm btn-sm px-3">
                  <i class="fe-plus me-1"></i> Tambah Audit Utama
               </a>
               @endcan
            </div>

            <div class="accordion" id="auditAccordion">
               @foreach ($audits as $audit)
               <div class="accordion-item audit-item">
                  <h2 class="accordion-header">
                     <div class="d-flex justify-content-between align-items-center w-100 pe-3">
                        <button class="accordion-button collapsed flex-grow-1" data-bs-toggle="collapse" data-bs-target="#audit{{ $audit['id'] }}">
                           <span>{{ $audit['name'] }}</span>
                           <span class="badge-bobot">Bobot: {{ $audit['bobot'] }} %</span>
                        </button>
                        <div class="btn-action-group ms-3">
                           <a href="{{ route('admin.audit-smp.edit', ['audit' => $audit['id']]) }}" class="btn btn-soft-warning btn-xs">Edit</a>
                           <form id="delete-audit{{ $audit['id'] }}" action="{{ route('admin.audit-smp.destroy', ['audit' => $audit['id']]) }}" method="POST">
                              @csrf @method('DELETE')
                              <button type="button" class="btn btn-soft-danger btn-xs" onclick="confirmDelete('delete-audit{{ $audit['id'] }}','Audit akan dihapus')">Hapus</button> 
                           </form>
                        </div>
                     </div>
                  </h2>
                  <div id="audit{{ $audit['id'] }}" class="accordion-collapse collapse" data-bs-parent="#auditAccordion">
                     <div class="accordion-body bg-white">
                        <div class="mb-4">
                           <a href="{{ route('admin.audit-smp.createElement', ['auditId' => $audit['id']]) }}" class="btn btn-soft-primary btn-sm px-4">
                              <i class="fe-plus-circle me-1"></i> Tambah Elemen
                           </a>
                        </div>

                        @if (!empty($audit['pernyataan']))
                        <span class="label-section"><i class="fe-layers me-1"></i>Pernyataan</span>
                        <div class="accordion mb-3">
                           @foreach ($audit['pernyataan'] as $pernyataan)
                           <div class="accordion-item pernyataan-item shadow-none border">
                              <h2 class="accordion-header">
                                 <div class="d-flex justify-content-between align-items-center w-100 pe-3">
                                    <button class="accordion-button collapsed py-2" data-bs-toggle="collapse" data-bs-target="#pernyataan{{ $pernyataan['id'] }}">
                                       <span class="fs-13">{{ $pernyataan['name'] }}</span>
                                    </button>
                                    <div class="btn-action-group ms-3">
                                       <a href="{{ route('admin.audit-smp.createKriteria', ['auditId' => $pernyataan['id']]) }}" class="btn btn-soft-success btn-xs">+ Kriteria</a>
                                       <a href="{{ route('admin.audit-smp.edit', ['audit' => $pernyataan['id']]) }}" class="btn btn-soft-warning btn-xs">Edit</a>
                                       <form id="delete-pernyataan{{ $pernyataan['id'] }}" action="{{ route('admin.audit-smp.destroy', ['audit' => $pernyataan['id']]) }}" method="POST">
                                          @csrf @method('DELETE')
                                          <button type="button" class="btn btn-soft-danger btn-xs" onclick="confirmDelete('delete-pernyataan{{ $pernyataan['id'] }}','Pernyataan akan dihapus')">Hapus</button> 
                                       </form>
                                    </div>
                                 </div>
                              </h2>
                              <div id="pernyataan{{ $pernyataan['id'] }}" class="accordion-collapse collapse">
                                 <div class="accordion-body border-top">
                                    @if (!empty($pernyataan['kriteria']))
                                    @foreach ($pernyataan['kriteria'] as $kriteria)
                                    <div class="accordion-item kriteria-item shadow-none border">
                                       <h2 class="accordion-header">
                                          <div class="d-flex justify-content-between align-items-center w-100 pe-3">
                                             <button class="accordion-button collapsed py-2 text-muted fs-13" data-bs-toggle="collapse" data-bs-target="#kriteria{{ $kriteria['id'] }}">
                                                {{ $kriteria['name'] }}
                                             </button>
                                             <div class="btn-action-group ms-3">
                                                <a href="{{ route('admin.audit-smp.createEvident', ['auditId' => $pernyataan['id'], 'type' => 'evident']) }}" class="btn btn-soft-success btn-xs">+ Evident</a>
                                                <a href="{{ route('admin.audit-smp.editKriteria', ['auditId' => $pernyataan['id'], 'kriteriaId' => $kriteria['id']]) }}" class="btn btn-soft-warning btn-xs">Edit</a>
                                                <form id="delete-kriteria{{ $kriteria['id'] }}" action="{{ route('admin.audit-smp.deleteKriteria', ['auditId' => $pernyataan['id'], 'kriteriaId' => $kriteria['id']]) }}" method="POST">
                                                   @csrf @method('DELETE')
                                                   <button type="button" class="btn btn-soft-danger btn-xs" onclick="confirmDelete('delete-kriteria{{ $kriteria['id'] }}','Kriteria akan dihapus')">Hapus</button> 
                                                </form>
                                             </div>
                                          </div>
                                       </h2>
                                       <div id="kriteria{{ $kriteria['id'] }}" class="accordion-collapse collapse">
                                          <div class="accordion-body bg-light-subtle">
                                             @if (!empty($kriteria['evident']))
                                             @foreach ($kriteria['evident'] as $evident)
                                             <div class="evident-item d-flex justify-content-between align-items-center shadow-sm">
                                                <div class="text-dark">
                                                   <span class="text-primary fw-bold me-2">{{ $loop->iteration }}.</span> {{ $evident['name'] }}
                                                </div>
                                                <div class="btn-action-group">
                                                   <a href="{{ route('admin.audit-smp.editEvident', ['auditId' => $pernyataan['id'], 'evidentId' => $evident['id']]) }}" class="btn btn-soft-warning btn-xs">Edit</a>
                                                   <form id="delete-evident{{ $evident['id'] }}" action="{{ route('admin.audit-smp.deleteEvident', ['auditId' => $pernyataan['id'], 'evidentId' => $evident['id']]) }}" method="POST">
                                                      @csrf @method('DELETE')
                                                      <button type="button" class="btn btn-soft-danger btn-xs" onclick="confirmDelete('delete-evident{{ $evident['id'] }}','Evident akan dihapus')">Hapus</button>
                                                   </form>
                                                </div>
                                             </div>
                                             @endforeach
                                             @endif
                                          </div>
                                       </div>
                                    </div>
                                    @endforeach
                                    @endif
                                 </div>
                              </div>
                           </div>
                           @endforeach
                        </div>
                        @endif
                        @if (!empty($audit['kriteria']))
                        <span class="label-section mt-4"><i class="fe-check-square me-1"></i> Kriteria</span>
                        <div class="accordion">
                           @foreach ($audit['kriteria'] as $kriteria)
                           <div class="accordion-item kriteria-item shadow-none border">
                              <h2 class="accordion-header">
                                 <div class="d-flex justify-content-between align-items-center w-100 pe-3">
                                    <button class="accordion-button collapsed py-2 text-muted fs-13" data-bs-toggle="collapse" data-bs-target="#kriteriaDirect{{ $kriteria['id'] }}">
                                       {{ $kriteria['name'] }}
                                    </button>
                                    <div class="btn-action-group ms-3">
                                       <a href="{{ route('admin.audit-smp.createEvident', ['auditId' => $audit['id']]) }}" class="btn btn-soft-success btn-xs">+ Evident</a>
                                       <a href="{{ route('admin.audit-smp.editKriteria', ['auditId' => $audit['id'], 'kriteriaId' => $kriteria['id']]) }}" class="btn btn-soft-warning btn-xs">Edit</a>
                                       <form id="delete-kriteria-direct{{ $kriteria['id'] }}" action="{{ route('admin.audit-smp.deleteKriteria', ['auditId' => $audit['id'], 'kriteriaId' => $kriteria['id']]) }}" method="POST">
                                          @csrf @method('DELETE')
                                          <button type="button" class="btn btn-soft-danger btn-xs" onclick="confirmDelete('delete-kriteria-direct{{ $kriteria['id'] }}','Kriteria akan dihapus')">Hapus</button> 
                                       </form>
                                    </div>
                                 </div>
                              </h2>
                              <div id="kriteriaDirect{{ $kriteria['id'] }}" class="accordion-collapse collapse">
                                 <div class="accordion-body bg-light-subtle">
                                    @if (!empty($kriteria['evident']))
                                    @foreach ($kriteria['evident'] as $evident)
                                    <div class="evident-item d-flex justify-content-between align-items-center shadow-sm">
                                       <div class="text-dark">
                                          <span class="text-primary fw-bold me-2">{{ $loop->iteration }}.</span> {{ $evident['name'] }}
                                       </div>
                                       <div class="btn-action-group">
                                          <a href="{{ route('admin.audit-smp.editEvident', ['auditId' => $audit['id'], 'evidentId' => $evident['id']]) }}" class="btn btn-soft-warning btn-xs">Edit</a>
                                          <form id="delete-evident-direct{{ $evident['id'] }}" action="{{ route('admin.audit-smp.deleteEvident', ['auditId' => $audit['id'], 'evidentId' => $evident['id']]) }}" method="POST">
                                             @csrf @method('DELETE')
                                             <button type="button" class="btn btn-soft-danger btn-xs" onclick="confirmDelete('delete-evident-direct{{ $evident['id'] }}','Evident akan dihapus')">Hapus</button> 
                                          </form>
                                       </div>
                                    </div>
                                    @endforeach
                                    @endif
                                 </div>
                              </div>
                           </div>
                           @endforeach
                        </div>
                        @endif
                     </div>
                  </div>
               </div>
               @endforeach
            </div>
         </div>
      </div>
   </div>
</div>
@endsection --}}



@extends('layout.app')
@section('styles')
<style>
    .accordion-button::after {
        filter: invert(100%);
    }

    .badge-bobot {
      background: #4e73df;
      color: white;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 12px;
      margin-left: 10px;
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
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Audit SMP</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end pe-3">
                    @can('create.audit.smp.admin')
                        <a href="{{route('admin.audit-smp.create')}}" class="btn btn-sm btn-primary mb-3">Tambah Audit</a>
                    @endcan
                </div>
                <!-- Komitmen Management -->
                <div class="accordion" id="formAccordion">

                <!-- Section A: Komitmen Management -->
                    <div class="accordion-item">
                        @foreach ($audits as $audit)
                            
                        <h2 class="accordion-header bg-light" id="heading{{$audit['id']}}">
                            <div
                                class="d-flex justify-content-between align-items-center w-100"
                                style="padding: 10px 15px; border: none;"
                            >
                                <!-- Area interaktif untuk accordion -->
                                <div
                                    class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#audit_{{$audit['id']}}"
                                    aria-expanded="false"
                                    aria-controls="collapseA"
                                    style="flex: 1; border: none; background-color: transparent;"
                                >
                                    <span class="fw-bold" style="white-space: normal; word-break: break-word;">{{$audit['name']}} </span><span class="badge-bobot ml-2">Bobot : {{$audit['bobot']}}%</span>
                                </div>
                                    <div style="display: flex;align-items:center;gap:10px">
                                        @can('edit.audit.smp.admin')
                                        <a href="{{route('admin.audit-smp.edit',['audit' => $audit['id']])}}" class="btn btn-warning btn-sm mt-1">Edit Audit</a>
                                        @endcan
                                        @can('delete.audit.smp.admin')
                                        <form
                                            id="delete-audit-smp-{{ $audit['id'] }}"
                                            action="{{route('admin.audit-smp.destroy',['audit'=>$audit['id']])}}"
                                            method="POST"
                                            class="d-inline"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                onclick="confirmDelete(
                                                    'delete-audit-smp-{{ $audit['id'] }}',
                                                    'Audit akan dihapus.'
                                                )"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                    @endcan
                            </div>
                        </h2>
                        @can('view.element.audit.smp.admin')
                        
                        <div id="audit_{{$audit['id']}}" class="accordion-collapse collapse" aria-labelledby="category{{$audit['id']}}" data-bs-parent="#formAccordion">
                            <div class="accordion-body">
                                @can('create.element.audit.smp.admin')
                                <a href="{{route('admin.element.audit-smp.create',['audit'=>$audit['id']])}}" class="btn btn-primary btn-sm mb-3">Tambah Element</a>
                                @endcan
                                <h4 class="">Pernyataan</h4>
                                <table class="table table-bordered text-center">
                                    <thead class="table-light">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($audit['pernyataan'] as $pernyataan)
                                            
                                            <tr>
                                                <td class="text-center">{{$loop->iteration}}</td>
                                                <td class="text-start">{{$pernyataan['name']}}</td>
                                                <td class="">
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @can('create.criteria.audit.smp.admin')
                                                        <a href="{{route('admin.criteria.audit-smp.create',['audit'=>$pernyataan['id']])}}" class="btn btn-success btn-sm">Tambah Kriteria</a>
                                                        @endcan
                                                        @can('edit.element.audit.smp.admin')
                                                        <a href="{{route('admin.element.audit-smp.edit',['audit'=>$audit['id'],'element'=>$pernyataan['id']])}}" class="btn btn-warning btn-sm">Edit</a>
                                                        @endcan
                                                        @can('delete.element.audit.smp.admin')
                                                        <form
                                                            id="delete-pernyataan-{{ $pernyataan['id'] }}"
                                                            action="{{ route('admin.element.audit-smp.delete',['audit'=>$audit['id'],'element'=>$pernyataan['id']]) }}"
                                                            method="POST"
                                                            class="d-inline"
                                                        >
                                                            @csrf
                                                            @method('DELETE')

                                                            <button
                                                                type="button"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="confirmDelete(
                                                                    'delete-pernyataan-{{ $pernyataan['id'] }}',
                                                                    'Pernyataan akan dihapus.'
                                                                )"
                                                            >
                                                                Hapus
                                                            </button>
                                                        </form>
                                                        @endcan
                                                        @can('view.criteria.audit.smp.admin')
                                                        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#accordionRow{{$pernyataan['id']}}" aria-expanded="false" aria-controls="accordionRow{{$pernyataan['id']}}">
                                                            Lihat Detail Kriteria
                                                        </button>
                                                        @endcan
                                                    </div>
                                                </td>
                                                
                                            </tr>
                                            @can('view.criteria.audit.smp.admin')
                                            <tr id="accordionRow{{$pernyataan['id']}}" class="collapse accordion-content">
                                                <td colspan="6">
                                                    <table class="table table-bordered text-center">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="text-center align-middle">No</th>
                                                                <th class="text-center align-middle">Nama</th>
                                                                <th class="text-center align-middle">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (isset($pernyataan['kriteria']))
                                                                
                                                                @foreach ($pernyataan['kriteria'] as $kriteria)
                                                                    <tr>
                                                                        <h4 style="text-align: left">Kriteria</h2>
                                                                        <td>{{$loop->iteration}}</td>
                                                                        <td>{{$kriteria['name']}}</td>
                                                                        <td class="">
                                                                            <div class="d-flex flex-wrap gap-2">
                                                                                @can('create.evidence.audit.smp.admin')
                                                                                <a href="{{route('admin.evidence.audit-smp.create',['audit'=> $kriteria['id']])}}" class="btn btn-success btn-sm">Tambah Evidence</a>
                                                                                @endcan
                                                                                @can('edit.criteria.audit.smp.admin')
                                                                                <a href="{{route('admin.criteria.audit-smp.edit',['kriteria' => $kriteria['id'], 'audit' => $pernyataan['id']])}}" class="btn btn-warning btn-sm">Edit</a>
                                                                                @endcan
                                                                                @can('delete.criteria.audit.smp.admin')
                                                                                <form
                                                                                    id="delete-criteria-{{ $kriteria['id'] }}"
                                                                                    action="{{ route('admin.criteria.audit-smp.delete',['kriteria' => $kriteria['id'], 'audit' => $pernyataan['id']]) }}"
                                                                                    method="POST"
                                                                                    class="d-inline"
                                                                                >
                                                                                    @csrf
                                                                                    @method('DELETE')

                                                                                    <button
                                                                                        type="button"
                                                                                        class="btn btn-danger btn-sm"
                                                                                        onclick="confirmDelete(
                                                                                            'delete-criteria-{{ $kriteria['id'] }}',
                                                                                            'Kriteria akan dihapus.'
                                                                                        )"
                                                                                    >
                                                                                        Hapus
                                                                                    </button>
                                                                                </form>
                                                                                @endcan
                                                                                @can('view.evidence.audit.smp.admin')
                                                                                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#evidenceRow{{$kriteria['id']}}" aria-expanded="false" aria-controls="evidenceRow{{$kriteria['id']}}">
                                                                                    Lihat Detail Evidence
                                                                                </button>
                                                                                @endcan
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    @can('view.evidence.audit.smp.admin')
                                                                    <tr id="evidenceRow{{$kriteria['id']}}" class="collapse accordion-content">
                                                                        <td colspan="6">
                                                                            <table class="table table-bordered text-center">
                                                                                <thead class="table-light">
                                                                                    <tr>
                                                                                        <th class="text-center align-middle">No</th>
                                                                                        <th class="text-center align-middle">Name</th>
                                                                                        <th class="text-center align-middle">Action</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($kriteria['evidence'] as $evidence)   
                                                                                        <tr>
                                                                                            <td>{{$loop->iteration}}</td>
                                                                                            <td>{{$evidence['name']}}</td>
                                                                                            <td class="">
                                                                                                <div class="d-flex justify-content-end gap-2">
                                                                                                    @can('edit.evidence.audit.smp.admin')
                                                                                                    <a href="{{route('admin.evidence.audit-smp.edit',['audit' => $kriteria['id'],'evidence' => $evidence['id']])}}" class="btn btn-warning btn-sm">Edit</a>
                                                                                                    @endcan
                                                                                                    @can('delete.evidence.audit.smp.admin')
                                                                                                    <form
                                                                                                        id="delete-evidence-{{ $evidence['id'] }}"
                                                                                                        action="{{ route('admin.evidence.audit-smp.delete',['audit' => $kriteria['id'],'evidence' => $evidence['id']]) }}"
                                                                                                        method="POST"
                                                                                                        class="d-inline"
                                                                                                    >
                                                                                                        @csrf
                                                                                                        @method('DELETE')

                                                                                                        <button
                                                                                                            type="button"
                                                                                                            class="btn btn-danger btn-sm"
                                                                                                            onclick="confirmDelete(
                                                                                                                'delete-evidence-{{ $evidence['id'] }}',
                                                                                                                'Evidence akan dihapus.'
                                                                                                            )"
                                                                                                        >
                                                                                                            Hapus
                                                                                                        </button>
                                                                                                    </form>
                                                                                                    @endcan
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>  
                                                                        </td>
                                                                    </tr>
                                                                    @endcan
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>  
                                                </td>
                                            </tr>
                                            @endcan
                                        @endforeach                                        
                                    </tbody>
                                </table>
                                <h4 class="">Kriteria</h4>
                                <table class="table table-bordered text-center">
                                    <thead class="table-light">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($audit['kriteria'] as $kriteria)
                                            
                                            <tr>
                                                <td class="text-center">{{$loop->iteration}}</td>
                                                <td class="text-start">{{$kriteria['name']}}</td>
                                                <td class="">
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @can('create.criteria.audit.smp.admin')
                                                        <a href="{{route('admin.kpi-level.create',['sub_area'=> $kriteria['id']])}}" class="btn btn-success btn-sm">Tambah Kriteria</a>
                                                        @endcan
                                                        @can('edit.element.audit.smp.admin')
                                                        <a href="{{route('admin.element.audit-smp.edit',['audit'=>$audit['id'],'element'=>$pernyataan['id']])}}" class="btn btn-warning btn-sm">Edit</a>
                                                        @endcan
                                                        @can('delete.element.audit.smp.admin')
                                                        <form
                                                            id="delete-kpi-subarea-{{ $pernyataan['id'] }}"
                                                            action="{{ route('admin.element.audit-smp.delete',['audit'=>$audit['id'],'element'=>$pernyataan['id']]) }}"
                                                            method="POST"
                                                            class="d-inline"
                                                        >
                                                            @csrf
                                                            @method('DELETE')

                                                            <button
                                                                type="button"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="confirmDelete(
                                                                    'delete-element-{{ $pernyataan['id'] }}',
                                                                    'Pernyataan akan dihapus.'
                                                                )"
                                                            >
                                                                Hapus
                                                            </button>
                                                        </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                                
                                            </tr>
                                            {{-- @can('view.kpi.level')
                                            <tr id="accordionRow{{$subArea->id}}" class="collapse accordion-content">
                                                <td colspan="6">
                                                    <table class="table table-bordered text-center">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="text-center align-middle">No</th>
                                                                <th class="text-center align-middle">Level</th>
                                                                <th class="text-center align-middle">Deskripsi</th>
                                                                <th class="text-center align-middle">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (isset($subArea->levels))
                                                                
                                                                @foreach ($subArea->levels as $level)   
                                                                    <tr>
                                                                        <td>{{$loop->iteration}}</td>
                                                                        <td>Level {{$level->level}}</td>
                                                                        <td class="text-start">{{$level->description}}</td>
                                                                        <td class="">
                                                                            <div class="d-flex flex-wrap gap-2">
                                                                                @can('create.kpi.note')
                                                                                <a href="{{route('admin.kpi-note.create',['level'=> $level->id])}}" class="btn btn-success btn-sm">Tambah Note</a>
                                                                                @endcan
                                                                                @can('edit.kpi.level')
                                                                                <a href="{{route('admin.kpi-level.edit',['level'=> $level->id,'sub_area' => $subArea->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                                                                @endcan
                                                                                @can('delete.kpi.level')
                                                                                <form
                                                                                    id="delete-kpi-level-{{ $level->id }}"
                                                                                    action="{{ route('admin.kpi-level.destroy',['level'=> $level->id,'sub_area' => $subArea->id]) }}"
                                                                                    method="POST"
                                                                                    class="d-inline"
                                                                                >
                                                                                    @csrf
                                                                                    @method('DELETE')

                                                                                    <button
                                                                                        type="button"
                                                                                        class="btn btn-danger btn-sm"
                                                                                        onclick="confirmDelete(
                                                                                            'delete-kpi-level-{{ $level->id }}',
                                                                                            'Level akan dihapus.'
                                                                                        )"
                                                                                    >
                                                                                        Hapus
                                                                                    </button>
                                                                                </form>
                                                                                @endcan
                                                                                @can('view.kpi.note')
                                                                                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#noteRow{{$level->id}}" aria-expanded="false" aria-controls="noteRow{{$level->id}}">
                                                                                    Lihat Detail Note
                                                                                </button>
                                                                                @endcan
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    @can('view.kpi.note')
                                                                    <tr id="noteRow{{$level->id ?? ''}}" class="collapse accordion-content">
                                                                        <td colspan="6">
                                                                            <table class="table table-bordered text-center">
                                                                                <thead class="table-light">
                                                                                    <tr>
                                                                                        <th class="text-center align-middle">No</th>
                                                                                        <th class="text-center align-middle">Note</th>
                                                                                        <th class="text-center align-middle">Action</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($level->notes as $note)   
                                                                                        <tr>
                                                                                            <td>{{$loop->iteration}}</td>
                                                                                            <td>{{$note->note}}</td>
                                                                                            <td class="">
                                                                                                <div class="d-flex justify-content-end gap-2">
                                                                                                    @can('edit.kpi.note')
                                                                                                    <a href="{{route('admin.kpi-note.edit',['note' => $note->id,'level'=> $level->id])}}" class="btn btn-warning btn-sm">Edit</a>
                                                                                                    @endcan
                                                                                                    @can('delete.kpi.note')
                                                                                                    <form
                                                                                                        id="delete-kpi-note-{{ $note->id }}"
                                                                                                        action="{{ route('admin.kpi-note.destroy',['note' => $note->id,'level'=> $level->id]) }}"
                                                                                                        method="POST"
                                                                                                        class="d-inline"
                                                                                                    >
                                                                                                        @csrf
                                                                                                        @method('DELETE')

                                                                                                        <button
                                                                                                            type="button"
                                                                                                            class="btn btn-danger btn-sm"
                                                                                                            onclick="confirmDelete(
                                                                                                                'delete-kpi-note-{{ $note->id }}',
                                                                                                                'Catatan akan dihapus.'
                                                                                                            )"
                                                                                                        >
                                                                                                            Hapus
                                                                                                        </button>
                                                                                                    </form>
                                                                                                    @endcan
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>  
                                                                        </td>
                                                                    </tr>
                                                                    @endcan
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>  
                                                </td>
                                            </tr>
                                            @endcan --}}
                                        @endforeach                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endcan
                        @endforeach
                    </div>
                </div>
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

