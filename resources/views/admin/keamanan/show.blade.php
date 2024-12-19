@extends('admin.layout.app')
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
        <h4 class="fs-18 fw-semibold m-0">Keamanan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('admin.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Keamanan</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <a href="{{route('admin.keamanan.index')}}" class="btn btn-success mb-3"> Back</a>
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
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center" rowspan="5">1</td>
                            <td class="text-nowrap" rowspan="5">PENGENDALIAN KOMITMEN MANAJEMEN</td>
                            <td class="" align="justify" rowspan="5">
                                <p>Anggaran dan Program Kerja Keamanan:
                                    Deskripsi: Program kerja dan anggaran bidang keamanan di Unit Induk dan Unit Pelaksana tertuang dalam RKAP dan ditetapkan melalui SKKO. 
                                    Referensi: Perpol 07 Tahun 2019;
                                </p>

                            </td>
                            <td> 
                                <p><b>Fire Fighting (Melakukan tindakan setelah ada kejadian)</b></p>
                            </td>
                            <td>
                                <p align="justify">Unit memiliki rencana Program Kerja Bidang Keamanan yang tertuang dalam usulan RKAP tahun berjalan.
                                </p>
                            </td>
                            <td>Dokumen RKAP yang berisikan program kerja bidang keamanan Unit.</td>
                            <td><button type="button" class="btn btn-primary">Download</button></td>
                        </tr>
                        <tr>
                            <td> 
                                <p><b>Stabilizing (Bertindak sebatas merespon kejadian)</b></p>
                            </td>
                            <td>
                                <p align="justify">Unit memiliki Program Kerja dan menyediakan anggaran bidang Keamanan yang tertuang dalam SKKO sesuai prioritas berdasarkan mitigasi risiko.
                                </p>
                            </td>
                            <td>level 1 + SKKO yang ditandatangani + Prioritas program berdasarkan RKAP bidang keamanan berbasis risiko.</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td> 
                                <p><b>Preventing (Ada usaha untuk melakukan pencegahan)</b></p>
                            </td>
                            <td>
                                <p align="justify">Unit memiliki Program Kerja dan menyediakan anggaran bidang Keamanan yang tertuang dalam SKKO sesuai prioritas berdasarkan mitigasi risiko, memiliki target rencana realisasi dan monitoring penyerapan anggaran.
                                </p>
                            </td>
                            <td>Level 2 + target rencana realisasi + monitoring penyerapan anggaran.</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td> 
                                <p><b>Optimizing (Adanya usaha optimasi sumberdaya & improvement)</b></p>
                            </td>
                            <td>
                                <p align="justify">Unit memiliki Program Kerja dan menyediakan anggaran bidang Keamanan yang tertuang dalam SKKO sesuai prioritas berdasarkan mitigasi risiko, memiliki target rencana realisasi dan monitoring penyerapan anggaran serta evaluasi OFI AFI terhadap program kerja yang belum dapat dilaksanakan.
                                </p>
                            </td>
                            <td>Level 3 + OFI AFI terhadap program kerja yang belum dapat dilaksanakan.</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td> 
                                <p><b>Excellence (Mencapai keunggulan optimasi sumberdaya & continuous improvement)</b></p>
                            </td>
                            <td>
                                <p align="justify">Unit memiliki Program Kerja dan menyediakan anggaran bidang Keamanan yang tertuang dalam SKKO sesuai prioritas berdasarkan mitigasi risiko, memiliki target rencana realisasi, monitoring penyerapan anggaran dan evaluasi OFI AFI terhadap program kerja yang belum dapat dilaksanakan serta memiliki roadmap jangka panjang bidang keamanan (5 tahun).
                                </p>
                            </td>
                            <td>Level 4 + Roadmap Jangka Panjang bidang keamanan Unit </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

