@extends('user.layout.app')
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
        <h4 class="fs-18 fw-semibold m-0">Audit Bulanan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Detail Audit Bulanan</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-3">Detail Audit Bulanan</h5>
                <a href="{{route('user.monthly-audit.index')}}" class="btn btn-danger"> Back</a>
            </div><!-- end card header -->

            <div class="card-body">
                <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.form-formulir.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-home-account"></i></span>
                            <span class="d-none d-sm-block">Form Formulir</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.worker-sum.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-home-account"></i></span>
                            <span class="d-none d-sm-block">Jumlah Pekerja</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.security-form.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-account-outline"></i></span>
                            <span class="d-none d-sm-block">Satuan Pengamanan</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.aght.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                            <span class="d-none d-sm-block">Data AGHT</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#navtabs2-atribut" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                            <span class="d-none d-sm-block">Atribut Peralatan</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.form-foreign-worker.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Tenaga Kerja Asing</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.security-program.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Program Keamanan</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.form-vulnerability-internal.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Kerawanan Internal</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('user.monthly-audit.form-vulnerability-external.index',['monthlyId' => $monthlyId])}}">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Kerawanan Eksternal</span>    
                        </a>
                    </li>
                </ul>

                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active" id="navtabs2-atribut" role="tabpanel">
                        <div class="atribut mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title fw-bold">Atribut & Peralatan</span>
                            </div>
                            <div class="table-responsive">
                                <form action="{{route('user.monthly-audit.form-attribute.saveAttribute',['monthlyId' => $monthlyId])}}" method="POST">
                                @csrf
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">No</th>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">Uraian</th>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">Status Kepemilikan</th>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">Satuan</th>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">Jumlah Standar Kontrak</th>
                                                <th scope="col" class="text-nowrap align-middle" colspan="2">Kondisi</th>
                                                <th scope="col" class="text-nowrap align-middle" colspan="2">Masa Berlaku</th>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">Keterangan</th>
                                            </tr>
                                            <tr>
                                                <th scope="col" class="text-nowrap">Ada</th>
                                                <th scope="col" class="text-nowrap">Tidak</th>
                                                <th scope="col" class="text-nowrap">Operasi / Berlaku</th>
                                                <th scope="col" class="text-nowrap">Rusak / Kadaluarsa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
                                            @foreach ($attributes as $attribute)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{$attribute->name}}</td>
                                                    <td>{{$attribute->status_ownership}}</td>
                                                    <td>{{$attribute->unit}}</td>
                                                    <td>{{$attribute->standard_contract}}</td>
                                                    <td>
                                                        <input type="hidden" value="{{$attribute->id}}" name="attribute[]">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="condition_{{$attribute->id}}" id="attribute_radioYes{{$loop->iteration}}" value="1" {{$attribute->condition == 1 ? 'checked' : ''}}>
                                                            <label class="form-check-label" for="attribute_radioYes{{$loop->iteration}}">
                                                            Ya
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="condition_{{$attribute->id}}" id="attribute_radiono{{$loop->iteration}}" value="0" {{$attribute->condition == 0 ? 'checked' : ''}}>
                                                            <label class="form-check-label" for="attribute_radiono{{$loop->iteration}}">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input ms-3" type="radio" name="status_item_{{$attribute->id}}" id="attribute_checkyes{{$loop->iteration}}" value="1" {{$attribute->status_item == 1 ? 'checked' : ''}}>
                                                            <label class="form-check-label" for="attribute_checkyes{{$loop->iteration}}">
                                                            Ya
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input ms-3" type="radio" name="status_item_{{$attribute->id}}" id="attribute_checkno{{$loop->iteration}}" value="0" {{$attribute->status_item == 0 ? 'checked' : ''}}>
                                                            <label class="form-check-label" for="attribute_checkno{{$loop->iteration}}">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="note_{{$attribute->id}}" value="{{$attribute->note}}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-sm btn-success float-end">Update</button>
                                </form>
                            </div>
                        </div>
                        <div class="administrasi mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title fw-bold">Administrasi</span>
                            </div>
                            <div class="table-responsive">
                                <form action="{{route('user.monthly-audit.form-attribute.saveAdministration',['monthlyId' => $monthlyId])}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">No</th>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">Uraian</th>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">Status Kepemilikan</th>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">Satuan</th>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">Jumlah Standar Kontrak</th>
                                                <th scope="col" class="text-nowrap align-middle" colspan="2">Kondisi</th>
                                                <th scope="col" class="text-nowrap align-middle" colspan="2">Masa Berlaku</th>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">Keterangan</th>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">File</th>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">File Download</th>
                                            </tr>
                                            <tr>
                                                <th scope="col" class="text-nowrap">Ada</th>
                                                <th scope="col" class="text-nowrap">Tidak</th>
                                                <th scope="col" class="text-nowrap">Operasi / Berlaku</th>
                                                <th scope="col" class="text-nowrap">Rusak / Kadaluarsa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($administrations as $administration)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{$administration->name}}</td>
                                                    <td>{{$administration->status_ownership}}</td>
                                                    <td>{{$administration->unit}}</td>
                                                    <td>{{$administration->standard_contract}}</td>
                                                    <td>
                                                        <input type="hidden" value="{{$administration->id}}" name="administration[]">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="condition_{{$administration->id}}" id="administration_radioYes{{$loop->iteration}}" value="1" {{$administration->condition == 1 ? 'checked' : ''}}>
                                                            <label class="form-check-label" for="administration_radioYes{{$loop->iteration}}">
                                                            Ya
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="condition_{{$administration->id}}" id="administration_radiono{{$loop->iteration}}" value="0" {{$administration->condition == 0 ? 'checked' : ''}}>
                                                            <label class="form-check-label" for="administration_radiono{{$loop->iteration}}">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input ms-3" type="radio" name="status_item_{{$administration->id}}" id="administration_checkyes{{$loop->iteration}}" value="1" {{$administration->status_item == 1 ? 'checked' : ''}}>
                                                            <label class="form-check-label" for="administration_checkyes{{$loop->iteration}}">
                                                            Ya
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input ms-3" type="radio" name="status_item_{{$administration->id}}" id="administration_checkno{{$loop->iteration}}" value="0" {{$administration->status_item == 0 ? 'checked' : ''}}>
                                                            <label class="form-check-label" for="administration_checkno{{$loop->iteration}}">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="note_{{$administration->id}}" value="{{$administration->note}}">
                                                    </td>
                                                    <td>
                                                        <input type="file" class="form-control mt-3" name="attachment_file_{{$administration->id}}" accept=".pdf">
                                                        <small class="float-start">Ekstensi file .pdf</small>
                                                    </td>
                                                    <td>
                                                        @if ($administration->attachment_file)
                                                            <a href="{{asset('uploads/attachment_file_form_attribute/'.$administration->attachment_file)}}" class="btn btn-sm btn-info" download="">Download</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-sm btn-success float-end">Update</button>
                                </form>
                            </div>
                        </div>
                        <div class="sarana mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title fw-bold">Sarana & Prasarana</span>
                            </div>
                            <div class="table-responsive">
                                <form action="{{route('user.monthly-audit.form-attribute.saveSarana',['monthlyId' => $monthlyId])}}" method="POST">
                                @csrf
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">No</th>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">Uraian</th>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">Status Kepemilikan</th>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">Satuan</th>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">Jumlah Standar Kontrak</th>
                                                <th scope="col" class="text-nowrap align-middle" colspan="2">Kondisi</th>
                                                <th scope="col" class="text-nowrap align-middle" colspan="2">Masa Berlaku</th>
                                                <th scope="col" class="text-nowrap align-middle" rowspan="2">Keterangan</th>
                                            </tr>
                                            <tr>
                                                <th scope="col" class="text-nowrap">Ada</th>
                                                <th scope="col" class="text-nowrap">Tidak</th>
                                                <th scope="col" class="text-nowrap">Operasi / Berlaku</th>
                                                <th scope="col" class="text-nowrap">Rusak / Kadaluarsa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($saranas as $sarana)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{$sarana->name}}</td>
                                                    <td>{{$sarana->status_ownership}}</td>
                                                    <td>{{$sarana->unit}}</td>
                                                    <td>{{$sarana->standard_contract}}</td>
                                                    <td>
                                                        <input type="hidden" value="{{$sarana->id}}" name="sarana[]">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="condition_{{$sarana->id}}" id="sarana_radioYes{{$loop->iteration}}" value="1" {{$sarana->condition == 1 ? 'checked' : ''}}>
                                                            <label class="form-check-label" for="sarana_radioYes{{$loop->iteration}}">
                                                            Ya
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="condition_{{$sarana->id}}" id="sarana_radiono{{$loop->iteration}}" value="0" {{$sarana->condition == 0 ? 'checked' : ''}}>
                                                            <label class="form-check-label" for="sarana_radiono{{$loop->iteration}}">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input ms-3" type="radio" name="status_item_{{$sarana->id}}" id="sarana_checkyes{{$loop->iteration}}" value="1" {{$sarana->status_item == 1 ? 'checked' : ''}}>
                                                            <label class="form-check-label" for="sarana_checkyes{{$loop->iteration}}">
                                                            Ya
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input ms-3" type="radio" name="status_item_{{$sarana->id}}" id="sarana_checkno{{$loop->iteration}}" value="0" {{$sarana->status_item == 0 ? 'checked' : ''}}>
                                                            <label class="form-check-label" for="sarana_checkno{{$loop->iteration}}">
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="note_{{$sarana->id}}" value="{{$sarana->note}}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-sm btn-success float-end">Update</button>
                                </form>   
                            </div>
                        </div>
                    </div><!-- end tab pane -->
                </div>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

