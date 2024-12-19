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
        <h4 class="fs-18 fw-semibold m-0">Assesment</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Edit Data Assesment</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <!-- Komitmen Management -->
                <div class="accordion" id="formAccordion">
                    <form action="" method="post">

                        <!-- Section A: Komitmen Management -->
                        <div class="accordion-item">
                        <h2 class="accordion-header bg-light" id="headingA">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseA" aria-expanded="true" aria-controls="collapseA">
                            A. MAN
                            </button>
                        </h2>
                        <div id="collapseA" class="accordion-collapse collapse show" aria-labelledby="headingA" data-bs-parent="#formAccordion">
                            <div class="accordion-body">
                                <table class="table table-bordered">
                                  <thead class="table-light">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Indikator</th>
                                        <th scope="col">Level</th>
                                        <th scope="col">Level Penilian</th>
                                        <th scope="col">Nilai</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td class="text-nowrap">Kesesuaian jumlah tenaga kerja dibanding kontrak</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Jumlah tenaga kerja < 80% yang dipersyaratkan di kontrak</li>
                                                    <li>Jumlah tenaga kerja < 90% yang dipersyaratkan di kontrak</li>
                                                    <li>Jumlah tenaga kerja sudah 100% yang dipersyaratkan di kontrak</li>
                                                    <li>Jumlah tenaga kerja sesuai dengan yang diperyaratkan dalam kontrak dan terdapat perwakilan tenaga kerja</li>
                                                    <li>Jumlah tenaga kerja sesuai dengan yang diperyaratkan dalam kontrak dan terdapat perwakilan tenaga kerja serta memiliki cadangan jika ada tenaga kerja yang cuti/sakit</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">2</td>
                                            <td class="text-nowrap">Pemberian hak normatif pegawai antara lain : Penghasilan Take Home Pay (Upah Tetap)</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Penghasilan dibawah UMK</li>
                                                    <li>Penghasilan sama dengan UMK</li>
                                                    <li>Penghasilan diberikan sesuai dengan kontrak  (UMK+Tunjangan)</li>
                                                    <li>Hak normatif yang diterima sesuai dengan penghasilan dan diberikan reward bagi yang berprestasi</li>
                                                    <li>Hak normatif yang diterima sesuai dengan penghasilan dan diberikan reward bagi yang berprestasi dan apresiasi jabatan</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">3</td>
                                            <td class="text-nowrap">Pemberian hak normatif pegawai antara lain : pendaftaran seluruh tenaga kerja ke BPJS Kesehatan dan Ketenagakerjaan</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Tenaga kerja yang didaftarkan BPJS < 80% dari jumlah</li>
                                                    <li>Tenaga kerja yang didaftarkan BPJS < 90% dari jumlah</li>
                                                    <li>Tenaga kerja yang didaftarkan BPJS sudah 100% dari jumlah tapi premi belum sesuai</li>
                                                    <li>Tenaga kerja yang didaftarkan BPJS sudah 100% dari jumlah dan premi telah sesuai dengan ketentuannya</li>
                                                    <li>Tenaga kerja yang didaftarkan BPJS sudah 100% dari jumlah dan premi telah sesuai dengan ketentuannya ditambah asuransi lain</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">4</td>
                                            <td class="text-nowrap">Pemberian hak normatif pegawai antara lain : Pemberian THR 
                                                </td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Pemberian THR masih < 80% dari upah</li>
                                                    <li>Pemberian THR masih < 90% dari upah</li>
                                                    <li>Pemberian THR sudah 100% dari upah</li>
                                                    <li>Pemberian THR sudah 100% dari upah dan tambahan lainnya (bingkisan)</li>
                                                    <li>Pemberian THR sudah > 110% dari upah dan tambahan lainnya (bingkisan) </li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">5</td>
                                            <td class="text-nowrap">Pemberian hak normatif pegawai antara lain : Pendaftaran DPLK / Pesangon</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Tenaga kerja yang didaftarkan DPLK / Pesangon < 80% dari jumlah</li>
                                                    <li>Tenaga kerja yang didaftarkan DPLK / Pesangon < 90% dari jumlah</li>
                                                    <li>Tenaga kerja yang didaftarkan DPLK / Pesangon sudah 100% dari jumlah tapi premi belum sesuai ketentuan</li>
                                                    <li>Tenaga kerja yang didaftarkan DPLK / Pesangon sudah 100% dari jumlah dan premi telah sesuai dengan ketentuannya</li>
                                                    <li>Tenaga kerja yang didaftarkan DPLK / Pesangon sudah 100% dari jumlah dan premi telah melebihi ketentuannya </li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">6</td>
                                            <td class="text-nowrap">Pemberian hak normatif pegawai antara lain : Pemberian seragam </td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Seragam kerja tidak diberikan</li>
                                                    <li>Seragam kerja diberikan 1 x 1 tahun</li>
                                                    <li>Seragam kerja diberikan 2 x 1 tahun</li>
                                                    <li>Seragam kerja diberikan 2 x 1 tahun ditambah pakaian kerja lainnya</li>
                                                    <li>Seragam kerja diberikan lebih dari 2 x 1 tahun dan ditambah seragam kerja lainnya</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">7</td>
                                            <td class="text-nowrap">Melakukan kegiatan pelatihan untuk meningkatkan kompetensi</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Tidak pernah diadakan pelatihan pegawai</li>
                                                    <li>Pernah diadakan pelatihan pegawai, tetapi tidak ada evaluasi untuk mengetahui tingkat keahlian pegawai</li>
                                                    <li>Pernah diadakan pelatihan pegawai dan evaluasi tetapi tidak ada peningkatan kualitas keahlian pegawai</li>
                                                    <li>Pelatihan diadakan rutin setiap tahun dan adanya peningkatan kualitas keahlian pegawai</li>
                                                    <li>Pelatihan diadakan rutin setiap tahun dengan variasi pembelajaran, adanya peningkatan kualitas keahlian pegawai dan dilakukan evaluasi kesesuaian antara pengetahuan pegawai dengan tingkat keahlian</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">7</td>
                                            <td class="text-nowrap">Melakukan kegiatan pelatihan untuk meningkatkan kompetensi</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Tidak pernah diadakan pelatihan pegawai</li>
                                                    <li>Pernah diadakan pelatihan pegawai, tetapi tidak ada evaluasi untuk mengetahui tingkat keahlian pegawai</li>
                                                    <li>Pernah diadakan pelatihan pegawai dan evaluasi tetapi tidak ada peningkatan kualitas keahlian pegawai</li>
                                                    <li>Pelatihan diadakan rutin setiap tahun dan adanya peningkatan kualitas keahlian pegawai</li>
                                                    <li>Pelatihan diadakan rutin setiap tahun dengan variasi pembelajaran, adanya peningkatan kualitas keahlian pegawai dan dilakukan evaluasi kesesuaian antara pengetahuan pegawai dengan tingkat keahlian</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">8</td>
                                            <td class="text-nowrap">Sertifikat kompetensi gada pratama, gada madya, gada utama</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Tidak seluruhnya memiliki sertifikat kompetensi</li>
                                                    <li>Memiliki sertifikat kompetensi ditemukan lebih dari 25 %  masa berlaku kadaluarsa</li>
                                                    <li>Memiliki sertifikat kompetensi ditemukan lebih dari 10 %  masa berlaku kadaluarsa</li>
                                                    <li>Sertifikat kompetensi sudah 100% dari jumlah pekerja dan masih berlaku</li>
                                                    <li>Memiliki sertifikat kompetensi, dan sertifikat pelatihan yang masih berlaku</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">9</td>
                                            <td class="text-nowrap">Pendaftaran SPK ke Disnaker</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Tidak dilakukan pendaftaran ke Disnaker</li>
                                                    <li>Didaftarkan dalam jangka waktu lebih dari 6 bulan setelah  tanggal kontrak</li>
                                                    <li>Didaftarkan dalam jangka waktu 1 bulan </li>
                                                    <li>Didaftarkan dalam jangka waktu kurang dari 1 bulan</li>
                                                    <li>Didaftarkan dan telah dilaporkan ke pihak PLN  dalam jangka waktu kurang dari 2 Minggu</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">10</td>
                                            <td class="text-nowrap">Budaya,perilaku dan komitmen menjaga citra perusahaan</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Tidak adanya pedoman perilaku dari Manajemen</li>
                                                    <li>Pedoman perilaku belum disharing ke semua personil satuan pengamanan</li>
                                                    <li>Pedoman perilaku sudah disharing ke semua personil satuan pengamanan tetapi hanya 50% dari  personil yang menerapkan</li>
                                                    <li>Pedoman perilaku sudah disharing ke semua personil satuan pengamanan dan telah diterapkan 100% oleh seluruh pegawai</li>
                                                    <li>Pedoman perilaku sudah disharing ke semua personil satuan pengamanan, selalu diingatkan setiap pergantian shift dan diterapkan 100% oleh seluruh personil satuan pengamanan</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </div>
                
                        <!-- Section B: Pembinaan -->
                        <div class="accordion-item">
                        <h2 class="accordion-header bg-light" id="headingB">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseB" aria-expanded="false" aria-controls="collapseB">
                            B. METHODE
                            </button>
                        </h2>
                        <div id="collapseB" class="accordion-collapse collapse" aria-labelledby="headingB" data-bs-parent="#formAccordion">
                            <div class="accordion-body">
                                <table class="table table-bordered">
                                  <thead class="table-light">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Indikator</th>
                                        <th scope="col">Level</th>
                                        <th scope="col">Level Penilian</th>
                                        <th scope="col">Nilai</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td class="text-nowrap">Melaksanakan kegiatan dan monitoring pengamanan harian, mingguan, dan bulanan</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Hanya melaksanakan kegiatan pengamanan rutin harian, mingguan dan bulanan</li>
                                                    <li>Melaksanakan kegiatan pengamanan  rutin dan melakukan monitoring bulanan</li>
                                                    <li>Melaksanakan kegiatan pengamanan  rutin dan melakukan monitoring mingguan</li>
                                                    <li>Melaksanakan kegiatan pengamanan  rutin dan melakukan monitoring harian</li>
                                                    <li>Melaksanakan kegiatan pengamanan  rutin dan melakukan monitoring harian serta melakukan evaluasi</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">2</td>
                                            <td class="text-nowrap">Memahami dan melaksanakan pengamanan sesuai SOP dan Intruksi Kerja</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>25% personil satuan pengamanan sudah memahami dan melaksanakan SOP dan IK</li>
                                                    <li>50% personil satuan pengamanan sudah memahami dan melaksanakan SOP dan IK</li>
                                                    <li>75% personil satuan pengamanan sudah memahami dan melaksanakan SOP dan IK</li>
                                                    <li>100% personil satuan pengamanan sudah memahami dan melaksanakan SOP dan IK</li>
                                                    <li>100% personil satuan pengamanan sudah memahami dan melaksanakan SOP dan IK, evaluasi serta mengusulkan updating SOP dan IK </li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">3</td>
                                            <td class="text-nowrap">Menjaga integritas dalam menjalankan tugas</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Belum ada komitmen menejemen BUJP terkait SMAP</li>
                                                    <li>Sudah ada komitmen menejemen BUJP terkait SMAP tetapi belum disosialisasikan ke seluruh satuan pengamanan</li>
                                                    <li>Sudah ada komitmen menajemen BUJP terkait SMAP dan sudah disosialisasikan ke seluruh satuan pengamanan </li>
                                                    <li>Sudah ada komitmen menejemen BUJP terkait SMAP dan sudah disosialisasikan ke seluruh satuan pengamanan serta 50% sudah menandatangani peryataan pakta integritas</li>
                                                    <li>Sudah ada komitmen menejemen BUJP terkait SMAP dan sudah disosialisasikan ke seluruh satuan pengamanan serta 100% sudah menandatangani peryataan pakta integritas</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">4</td>
                                            <td class="text-nowrap">Meningkatkan Kepemimpinan,kekompakan,semangat dan loyalitas</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Serah Terima Tugas Harian dilaksanakan dan sesuai SOP/IK serta tidak melaksanakan  ke SAMAPTAAN satu kali sebulan </li>
                                                    <li>Serah Terima Tugas Harian dilaksanakan dan sesuai SOP/IK serta melaksanakan  ke SAMAPTAAN satu kali sebulan 50% diikuti oleh personil satuan pengamanan</li>
                                                    <li>Serah Terima Tugas Harian dilaksanakan dan sesuai SOP/IK serta melaksanakan  ke SAMAPTAAN satu kali sebulan 100% diikuti oleh personil satuan pengamanan</li>
                                                    <li>Serah Terima Tugas Harian dilaksanakan dan sesuai SOP/IK serta melaksanakan  ke SAMAPTAAN lebih dari satu kali sebulan 100% diikuti oleh personil satuan pengamanan</li>
                                                    <li>Serah Terima Tugas Harian dilaksanakan dan sesuai SOP/IK serta melaksanakan  ke SAMAPTAAN lebih dari satu kali sebulan 100% diikuti oleh personil satuan pengamanan dan melaksanakan capacity building/emgath 1 tahun sekali</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">5</td>
                                            <td class="text-nowrap">Melakukan performance dialog dari hasil evaluasi dan menetapkan rencana tindakan perbaikan secara periodik, harian, mingguan, bulanan.</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Tidak adanya pertemuan rutin (apel , COC, Safety briefing, knowledge sharing, dll)</li>
                                                    <li>Adanya pertemuan rutin antara petugas lapangan untuk menindaklanjuti hasil evaluasi namun output rencana tindakannya belum ada</li>
                                                    <li>Adanya pertemuan antara petugas lapangan dan komandan regu untuk menindaklanjuti hasil evaluasi beserta rencana tindakan perbaikan, namun pelaksanaannya tidak dimonitor</li>
                                                    <li>Adanya pertemuan antara komandan regu/ kepala satpam dengan manajemen untuk menindaklanjuti hasil evaluasi beserta rencana tindakan perbaikan, dan dimonitor setiap bulan</li>
                                                    <li>Adanya pertemuan antara  komandan regu/ kepala satpam dengan manajemen untuk menindaklanjuti hasil evaluasi beserta rencana tindakan perbaikan, dan dimonitor setiap hari</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        </div>
                
                        <!-- Section C: Prosedur -->
                        <div class="accordion-item">
                            <h2 class="accordion-header bg-light" id="headingC">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseC" aria-expanded="false" aria-controls="collapseC">
                                C. MATERIAL
                                </button>
                            </h2>
                        <div id="collapseC" class="accordion-collapse collapse" aria-labelledby="headingC" data-bs-parent="#formAccordion">
                            <div class="accordion-body">
                                <table class="table table-bordered">
                                  <thead class="table-light">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Indikator</th>
                                        <th scope="col">Level</th>
                                        <th scope="col">Level Penilian</th>
                                        <th scope="col">Nilai</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td class="text-nowrap">Laporan penggunaan radio komunikasi (HT)</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Tidak ada monitoring</li>
                                                    <li>Sudah ada monitoring ketersediaan alat komunikasi tapi tidak ada laporan serah terima secara tertulis</li>
                                                    <li>Dilakukan monitoring ketersediaan alat komunikasi setiap pergantian shift secara konsisten</li>
                                                    <li>Dilakukan monitoring ketersediaan alat komunikasi dan serah terima secara tertulis setiap pergantian shift dengan konsisten</li>
                                                    <li>Dilakukan monitoring serah terima ketersediaan alat komunikasi setiap pergantian shift  di buatkan Berita Acara dan dilakukan pemeliharaan </li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">2</td>
                                            <td class="text-nowrap">Menggunakan perangkat patrol security guard</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Tidak ada monitoring</li>
                                                    <li>Sudah ada monitoring ketersediaan perangkat patrol tapi tidak ada laporan serah terima secara tertulis</li>
                                                    <li>Dilakukan monitoring ketersediaan perangkat patrol dan serah terima secara tertulis setiap pergantian shift belum konsisten</li>
                                                    <li>Dilakukan monitoring ketersediaan perangkat patrol dan serah terima secara tertulis setiap pergantian shift dengan konsisten</li>
                                                    <li>Dilakukan monitoring ketersediaan perangkat patrol dan serah terima secara tertulis setiap pergantian shift dengan konsisten dan telah melakukan pemeliharaan secara terjadwal</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">2</td>
                                            <td class="text-nowrap">Menggunakan perangkat pengamanan (Metal Detector, Under vehicle inspection sistem, Mirror Detector, Lampu Lalin , Visitor Manajemen Sistem, CCTV, dan Portal)</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Tidak disediakan perangkat pengamanan</li>
                                                    <li>Perangkat pengamanan tersedia digunakan tidak ada  laporan serah terima secara tertulis</li>
                                                    <li>Perangkat pengamanan tersedia digunakan dan ada laporan serah terima secara tertulis</li>
                                                    <li>Perangkat pengamanan tersedia digunakan dan ada laporan serah terima secara tertulis serta dilakukan monitoring</li>
                                                    <li>Dilakukan monitoring  perangkat pengamanan dan serah terima secara tertulis setiap pergantian shift dengan konsisten dan telah melakukan pemeliharaan secara terjadwal</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>
                        <!-- Section C: Prosedur -->
                        <div class="accordion-item">
                            <h2 class="accordion-header bg-light" id="headingD">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseD" aria-expanded="false" aria-controls="collapseD">
                                D. MACHINE
                                </button>
                            </h2>
                        <div id="collapseD" class="accordion-collapse collapse" aria-labelledby="headingD" data-bs-parent="#formAccordion">
                            <div class="accordion-body">
                                <table class="table table-bordered">
                                  <thead class="table-light">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Indikator</th>
                                        <th scope="col">Level</th>
                                        <th scope="col">Level Penilian</th>
                                        <th scope="col">Nilai</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td class="text-nowrap">Menyediakan sarana kerja (Kendaraan Roda 4/kendaraan roda 2/sepeda/bbm) yang sesuai standar</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Kondisi tidak standar (tidak layak)</li>
                                                    <li>Tidak sesuai spesifikasi yang dibutuhkan</li>
                                                    <li>Sesuai spesifikasi dan lengkap (sesuai dengan kontrak)</li>
                                                    <li>Sesuai spesifikasi dan lengkap (sesuai dengan kontrak) dan mendapatkan perawatan secara rutin</li>
                                                    <li>Mempunyai sarana cadangan jika terjadi kerusakan pada salah satu sarana</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">2</td>
                                            <td class="text-nowrap">Menyediakan peralatan kerja sesuai standar</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Peralatan kerja tidak lengkap</li>
                                                    <li>Peralatan kerja ada tapi tidak sesuai spesifikasi</li>
                                                    <li>Peralatan lengkap dan sesuai standar</li>
                                                    <li>Peralatan lengkap dan spesifikasinya sudah melebihi standar yang ditentukan dalam kontrak</li>
                                                    <li>Mempunyai peralatan kerja cadangan (peralatan kerja utama)</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header bg-light" id="headingE">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseE" aria-expanded="false" aria-controls="collapseE">
                                E. MONEY
                                </button>
                            </h2>
                        <div id="collapseE" class="accordion-collapse collapse" aria-labelledby="headingE" data-bs-parent="#formAccordion">
                            <div class="accordion-body">
                                <table class="table table-bordered">
                                  <thead class="table-light">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Indikator</th>
                                        <th scope="col">Level</th>
                                        <th scope="col">Level Penilian</th>
                                        <th scope="col">Nilai</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td class="text-nowrap">Ketepatan pembayaran upah satuan pengamanan</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Pembayaran upah dilakukan diatas tanggal 15 bulan berikutnya</li>
                                                    <li>Pembayaran upah dilakukan diatas tanggal 5 bulan berikutnya</li>
                                                    <li>Pembayan upah dibayarkan antara tanggal 1 - 5</li>
                                                    <li>Pembayaran upah dilakukan kurang dari tanggal 1</li>
                                                    <li>Pembayaran upah, BPJS dan DPLK dilakukan sebelum tanggal 1 bulan berikutnya</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">2</td>
                                            <td class="text-nowrap">Proses penagihan berkas</td>
                                            <td class="text-nowrap">
                                                <ol class="list-unstyled">
                                                    <li>Level 1</li>
                                                    <li>Level 2</li>
                                                    <li>Level 3</li>
                                                    <li>Level 4</li>
                                                    <li>Level 5</li>
                                                </ol>
                                            </td>
                                            <td> 
                                                <ol>
                                                    <li>Berkas tagihan masuk setelah tanggal 15 bulan berikutnya, lampiran tidak lengkap</li>
                                                    <li>Berkas tagihan masuk antara tanggal 10-15 bulan berikutnya, lampiran tidak lengkap</li>
                                                    <li>Berkas tagihan masuk antara tanggal 10-15 bulan berikutnya, lampiran lengkap</li>
                                                    <li>Berkas tagihan masuk antara tanggal 5-10 bulan berikutnya, lampiran lengkap</li>
                                                    <li>Berkas tagihan masuk antara tanggal 1-5 bulan berikutnya, lampiran lengkap</li>
                                                </ol>
                                            </td>
                                            <td>
                                                <select class="form-select" aria-label="Default select example" required>
                                                    <option value="">Pilih Level</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>
                        <!-- Add more sections as needed -->
                        <div class="form-group row mt-5">
                            <div class="col-12">
                                <div class="d-flex gap-1 justify-content-end">

                                    <a href="{{route('user.monthly-audit.index')}}" class="btn btn-danger"> Back</a>
                                    <button class="btn btn-success" type="submit"> Kirim</button>
                                    <button class="btn btn-primary" type="submit"> Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

