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
        <h4 class="fs-18 fw-semibold m-0">Marturity</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Marturity</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <a href="{{route('user.marturity.index')}}" class="btn btn-success mb-3"> Back</a>
                <table class="table table-bordered">
                    <thead class="table-light text-center text-nowrap">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Area</th>
                        <th scope="col">Sub Area</th>
                        <th scope="col">Level</th>
                        <th scope="col">Uraian</th>
                        <th scope="col">Catatan Assesment ( Eviden )</th>
                        <th scope="col">File</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center" rowspan="5">1</td>
                            <td class="text-nowrap" rowspan="5">PENGENDALIAN KOMITMEN DAN KEBIJAKAN</td>
                            <td class="" align="justify" rowspan="5">Komitmen dan Kebijakan Keamanan:
                                Deskripsi: Bentuk dukungan pimpinan puncak dengan jajarannya untuk menerapkan seluruh elemen SMP, yang dibuktikan dengan pernyataan kebijakan yang diikuti seluruh jajarannya mengenai strategi implementasi penerapan SMP terkait dengan alokasi, sumber daya, mekanisme evaluasi, dan perbaikan berkesinambungan untuk mencapai tujuan organisasi.
                                
                                Referensi: Perpol 07 Tahun 2019; </td>
                            <td rowspan="5"> 
                                <p><b>Fire Fighting (Melakukan tindakan setelah ada kejadian)</b></p>
                            </td>
                            <td rowspan="5">
                                <p align="justify">Terdapat dokumen kebijakan pengamanan yang bertanggal dan di tanda tangani pimpinan puncak organisasi, ditetapkan sesuai dengan perkiraan ancaman didasarkan pada sifat dan skala risiko keamanan, dan dievaluasi secara berkala serta adanya Struktur Organisasi Pengamanan termasuk tanggung jawab & wewenang juga terdapat alokasi anggaran dan/atau biaya pengamanan serta Sumber Daya Dan Infrastruktur terinventarisasi guna menunjang Penerapan SMP</p>
                            </td>
                            <td> 1. Kebijakan Sistem Manajemen Terintegrasi Direksi</td>
                            <td><input type="file" class="form-control"></td>
                            <td><button type="button" class="btn btn-primary">Upload</button></td>
                        </tr>
                        <tr>
                            <td> 2. Komitmen Implementasi Sistem Manajemen Pengamanan Unit</td>
                            <td><input type="file" class="form-control"></td>
                            <td><button type="button" class="btn btn-primary">Upload</button></td>
                        </tr>
                        <tr>
                            <td> 3. Perdir 0006.P/019/DIR/2022 tentang Peraturan Pelaksanaan Penerapan Manajemen Risiko Terintegrasi</td>
                            <td><input type="file" class="form-control"></td>
                            <td><button type="button" class="btn btn-primary">Upload</button></td>
                        </tr>
                        <tr>
                            <td> 4. Prosedur Penilaian Risiko Keamanan Unit</td>
                            <td><input type="file" class="form-control"></td>
                            <td><button type="button" class="btn btn-primary">Upload</button></td>
                        </tr>
                        <tr>
                            <td> 5. Hasil RTM (Daftar Hadir, Notulen)</td>
                            <td><input type="file" class="form-control"></td>
                            <td><button type="button" class="btn btn-primary">Upload</button></td>
                        </tr>

                        {{-- level2 --}}
                        <tr>
                            <td class="text-center" rowspan="4"></td>
                            <td class="text-nowrap" rowspan="4"></td>
                            <td class="" align="justify" rowspan="4"></td>
                            <td rowspan="4"> 
                                <p><b>Stabilizing (Bertindak sebatas merespon kejadian)</b></p>
                            </td>
                            <td rowspan="4">
                                <p align="justify"><b>Level 1 ditambah :</b><br>
                                    Kebijakan pengamanan dikomunikasikan kepada pihak internal organisasi (unsur manajemen dan pegawai/karyawan) dan pihak eksternal organisasi (masyarakat lingkungan sekitar) serta terdapat ketentuan untuk melaksanakan Audit SMP & mekanisme pelaksanaan Tinjauan manajemen  tentang penerapan SMP sesuai dengan ketentuan yang berlaku"</p>
                            </td>
                            <td> 1. Bukti Sosialiasi internal & eksternal  (Undangan, Daftar Hadir)</td>
                            <td><input type="file" class="form-control"></td>
                            <td><button type="button" class="btn btn-primary">Upload</button></td>
                        </tr>
                        <tr>
                            <td> 2. Prosedur Audit</td>
                            <td><input type="file" class="form-control"></td>
                            <td><button type="button" class="btn btn-primary">Upload</button></td>
                        </tr>
                        <tr>
                            <td> 3. Materi & Notulen RTM</td>
                            <td><input type="file" class="form-control"></td>
                            <td><button type="button" class="btn btn-primary">Upload</button></td>
                        </tr>
                        <tr>
                            <td> 4.Prosedur Tinjauan Manajemen</td>
                            <td><input type="file" class="form-control"></td>
                            <td><button type="button" class="btn btn-primary">Upload</button></td>
                        </tr>
                    </tbody>
                </table>
                
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

