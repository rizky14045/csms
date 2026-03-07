@extends('layout.app')

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
         <li class="breadcrumb-item"><a href="{{ route('admin.home.index') }}">Dashboard</a></li>
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
               <a href="{{ route('admin.audit-smp.create') }}" class="btn btn-primary shadow-sm btn-sm px-3">
                  <i class="fe-plus me-1"></i> Tambah Audit Utama
               </a>
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

                        {{-- ================= PERNYATAAN ================= --}}
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
                                    {{-- KRITERIA DALAM PERNYATAAN --}}
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

                        {{-- ================= KRITERIA LANGSUNG ================= --}}
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
@endsection