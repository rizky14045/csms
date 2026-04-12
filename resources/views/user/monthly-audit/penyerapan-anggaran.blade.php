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
            <h4 class="fs-18 fw-semibold m-0">Audit Bulanan</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Tambah Data Detail Audit Bulanan</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-3">Detail Audit Bulanan</h5>
                    <a href="{{ route('user.monthly-audit.index') }}" class="btn btn-danger"> Back</a>
                </div><!-- end card header -->

                <div class="card-body">
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('user.monthly-audit.form-formulir.index', ['monthlyId' => $monthlyId]) }}">
                                <span class="d-block d-sm-none"><i class="mdi mdi-home-account"></i></span>
                                <span class="d-none d-sm-block">Form Formulir</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('user.monthly-audit.worker-sum.index', ['monthlyId' => $monthlyId]) }}">
                                <span class="d-block d-sm-none"><i class="mdi mdi-home-account"></i></span>
                                <span class="d-none d-sm-block">Jumlah Pekerja</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('user.monthly-audit.security-form.index', ['monthlyId' => $monthlyId]) }}">
                                <span class="d-block d-sm-none"><i class="mdi mdi-account-outline"></i></span>
                                <span class="d-none d-sm-block">Satuan Pengamanan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('user.monthly-audit.aght.index', ['monthlyId' => $monthlyId]) }}"
                                data-bs-toggle="tab" href="#navtabs2-aght" role="tab">
                                <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                                <span class="d-none d-sm-block">Data AGHT</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('user.monthly-audit.form-attribute.index', ['monthlyId' => $monthlyId]) }}">
                                <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                                <span class="d-none d-sm-block">Atribut Peralatan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('user.monthly-audit.form-foreign-worker.index', ['monthlyId' => $monthlyId]) }}">
                                <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                                <span class="d-none d-sm-block">Tenaga Kerja Asing</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('user.monthly-audit.security-program.index', ['monthlyId' => $monthlyId]) }}">
                                <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                                <span class="d-none d-sm-block">Program Keamanan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('user.monthly-audit.form-vulnerability-internal.index', ['monthlyId' => $monthlyId]) }}">
                                <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                                <span class="d-none d-sm-block">Kerawanan Internal</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('user.monthly-audit.form-vulnerability-external.index', ['monthlyId' => $monthlyId]) }}">
                                <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                                <span class="d-none d-sm-block">Kerawanan Eksternal</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#navtabs2-penyerapan-anggaran"
                                role="tab">
                                <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                                <span class="d-none d-sm-block">Penyerapan Anggaran</span>
                            </a>
                        </li>
                    </ul>


                    <div class="d-flex justify-content-end pt-5 px-3">
                        <a href="{{ route('user.monthly-audit.penyerapan-anggaran.create', ['monthlyId' => $monthlyId]) }}"
                            class="btn btn-success btn-sm">
                            Tambah Data
                        </a>
                    </div>
                    <div class="tab-content px-3 text-muted">
                        <div class="tab-pane active" id="navtabs2-penyerapan-anggaran" role="tabpanel">
                            <div class="cooperation mb-3">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="title">A. Biaya Pemeliharaan</span>

                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Kode Aktifitas</th>
                                                <th scope="col">Kode PRK</th>
                                                <th scope="col">Deskripsi Kegiatan</th>
                                                <th scope="col">Jumlah Anggaran</th>
                                                <th scope="col">Penyerapan Anggaran</th>
                                                <th scope="col">Prosentase Penyerapan</th>
                                                <th scope="col">Keterangan</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($administrasi as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->kode_aktifitas }}</td>
                                                    <td>{{ $item->kode_prk }}</td>
                                                    <td>{{ $item->deskripsi_kegiatan }}</td>
                                                    <td>{{ number_format($item->jumlah_anggaran, 2, ',', '.') }}</td>
                                                    <td>{{ number_format($item->penyerapan_anggaran, 2, ',', '.') }}</td>
                                                    <td>{{ number_format($item->prosentase_penyerapan, 2, ',', '.') }}%</td>
                                                    <td>{{ $item->keterangan }}</td>
                                                    <td>
                                                        <a href="{{ route('user.monthly-audit.penyerapan-anggaran.edit', ['monthlyId' => $monthlyId, 'anggaranId' => $item->id]) }}"
                                                            class="btn btn-sm btn-warning">edit</a>
                                                        <form
                                                            action="{{ route('user.monthly-audit.penyerapan-anggaran.destroy', ['monthlyId' => $monthlyId, 'anggaranId' => $item->id]) }}"
                                                            method="post" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm">Hapus</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="cooperation mb-3">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="title">B. Biaya Administrasi</span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Kode Aktifitas</th>
                                                <th scope="col">Kode PRK</th>
                                                <th scope="col">Deskripsi Kegiatan</th>
                                                <th scope="col">Jumlah Anggaran</th>
                                                <th scope="col">Penyerapan Anggaran</th>
                                                <th scope="col">Prosentase Penyerapan</th>
                                                <th scope="col">Keterangan</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pemeliharaan as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->kode_aktifitas }}</td>
                                                    <td>{{ $item->kode_prk }}</td>
                                                    <td>{{ $item->deskripsi_kegiatan }}</td>
                                                    <td>{{ number_format($item->jumlah_anggaran, 2, ',', '.') }}</td>
                                                    <td>{{ number_format($item->penyerapan_anggaran, 2, ',', '.') }}</td>
                                                    <td>{{ number_format($item->prosentase_penyerapan, 2, ',', '.') }}%</td>
                                                    <td>{{ $item->keterangan }}</td>
                                                    <td>
                                                        <a href="{{ route('user.monthly-audit.penyerapan-anggaran.edit', ['monthlyId' => $monthlyId, 'anggaranId' => $item->id]) }}"
                                                            class="btn btn-sm btn-warning">edit</a>
                                                        <form
                                                            action="{{ route('user.monthly-audit.penyerapan-anggaran.destroy', ['monthlyId' => $monthlyId, 'anggaranId' => $item->id]) }}"
                                                            method="post" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm">Hapus</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- end tab pane -->
                    </div>
                </div>
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
