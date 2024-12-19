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
        <h4 class="fs-18 fw-semibold m-0">Audit Bulanan</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('admin.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Detail Audit Bulanan</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-3">Detail Audit Bulanan</h5>
                <a href="{{route('admin.monthly-audit.index')}}" class="btn btn-success"> Back</a>
            </div><!-- end card header -->

            <div class="card-body">
                <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#navtabs2-form" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-home-account"></i></span>
                            <span class="d-none d-sm-block">Form Formulir</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#navtabs2-home" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-home-account"></i></span>
                            <span class="d-none d-sm-block">Jumlah Pekerja</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#navtabs2-security" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-account-outline"></i></span>
                            <span class="d-none d-sm-block">Satuan Pengamanan</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#navtabs2-aght" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                            <span class="d-none d-sm-block">Data AGHT</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#navtabs2-atribut" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                            <span class="d-none d-sm-block">Atribut Peralatan</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#navtabs2-tka" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Tenaga Kerja Asing</span>    
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#navtabs2-kerawanan" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-cog"></i></span>
                            <span class="d-none d-sm-block">Kerawanan Internal</span>    
                        </a>
                    </li>
                </ul>

                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active" id="navtabs2-form" role="tabpanel">
                        <div class="security mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title fw-bold">Data Penanggung Jawab Keamanan</span>
                            </div>
                            <div class="container my-4">
                                <h5>BULAN : ...................</h5>
                                <!-- Section 1 -->
                                <div class="mb-3">
                                  <h6 class="fw-bold">1. JUMLAH PEGAWAI PT PLN NUSANTARA POWER</h6>
                                  <div class="row">
                                    <div class="col-12">
                                      <div class="table-responsive">
                                        <table class="table table-bordered text-center align-middle">
                                            <thead class="table-light">
                                            <tr>
                                              <th>Personil</th>
                                              <th>Total Orang</th>
                                              <th>Pria</th>
                                              <th>Wanita</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <tr>
                                              <td>1.1 Pegawai Tetap</td>
                                              <td> Orang</td>
                                              <td> 0 Orang</td>
                                              <td> 0 Orang</td>
                                            </tr>
                                            <tr>
                                              <td>1.2 Siswa OJT</td>
                                              <td>0 Orang</td>
                                              <td> 0 Orang</td>
                                              <td> 0 Orang</td>
                                            </tr>
                                            <tr class="fw-bold">
                                              <td>Jumlah</td>
                                              <td>0 Orang</td>
                                              <td>0 Orang</td>
                                              <td>0 Orang</td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              
                                <!-- Section 2 -->
                                <div class="mb-3">
                                  <h6 class="fw-bold">2. JUMLAH KARYAWAN OUTSOURCING</h6>
                                  <div class="table-responsive">
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light">
                                            <tr>
                                              <th>Personil</th>
                                              <th>Total Orang</th>
                                              <th>Pria</th>
                                              <th>Wanita</th>
                                            </tr>
                                          </thead>
                                      <tbody>
                                        <tr>
                                          <td>2.1 Karyawan PT</td>
                                          <td>0 Orang</td>
                                          <td> 0 Orang</td>
                                          <td> 0 Orang</td>
                                        </tr>
                                        <tr>
                                          <td>2.2 Karyawan PT</td>
                                          <td>0 Orang</td>
                                          <td> 0 Orang</td>
                                          <td> 0 Orang</td>
                                        </tr>
                                        <tr>
                                          <td>2.3 Karyawan KOPERASI</td>
                                          <td>0 Orang</td>
                                          <td> 0 Orang</td>
                                          <td> 0 Orang</td>
                                        </tr>
                                        <tr>
                                          <td>2.4 Karyawan IKPLN</td>
                                          <td>0 Orang</td>
                                          <td> 0 Orang</td>
                                          <td> 0 Orang</td>
                                        </tr>
                                        <tr class="fw-bold">
                                          <td>Jumlah</td><td>0 Orang</td><td>0 Orang</td><td>0 Orang</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              
                                <!-- Section 3 -->
                                <div class="mb-3">
                                  <h6 class="fw-bold">3. JUMLAH PERSONIL SATPAM PT</h6>
                                  <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                          <th>Personil</th>
                                          <th>Total Orang</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <tr><td>3.1 Komandan Regu</td><td>0 Orang</td></tr>
                                      <tr><td>3.2 Anggota Satuan</td><td>0 Orang</td></tr>
                                      <tr><td>3.3 Chief Satpam</td><td>0 Orang</td></tr>
                                      <tr class="fw-bold"><td>Jumlah</td><td>0 Orang</td></tr>
                                    </tbody>
                                  </table>
                                </div>
                              
                                <!-- Section 4 -->
                                <div class="mb-3">
                                  <h6 class="fw-bold">4. JASA PENGAMANAN</h6>
                                  <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                          <th>Personil</th>
                                          <th>Total Orang</th>
                                        </tr>
                                      </thead>
                                    <tbody>
                                      <tr><td>4.1 PAM OBVIT</td><td>0 Orang</td></tr>
                                      <tr><td>4.2 TNI</td><td>0 Orang</td></tr>
                                      <tr class="fw-bold"><td>Jumlah</td><td>0 Orang</td></tr>
                                    </tbody>
                                  </table>
                                </div>
                              
                                <!-- Section 5 -->
                                <div class="mb-3">
                                  <h6 class="fw-bold">5. JUMLAH TENAGA KERJA ASING</h6>
                                  <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                          <th>Personil</th>
                                          <th>Total Orang</th>
                                        </tr>
                                      </thead>
                                    <tbody>
                                      <tr><td>5.1 Tenaga Ahli</td><td>0 Orang</td></tr>
                                      <tr><td>5.2 Staff</td><td>0 Orang</td></tr>
                                      <tr class="fw-bold"><td>Jumlah</td><td>0 Orang</td></tr>
                                    </tbody>
                                  </table>
                                </div>
                              
                                <!-- Section 6 -->
                                <div class="mb-3">
                                  <h6 class="fw-bold">6. GANGGUAN YANG TERJADI</h6>
                                  <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                          <th>Jenis Gangguan</th>
                                          <th>Total Orang</th>
                                        </tr>
                                      </thead>
                                    <tbody>
                                      <tr><td>6.1 Bersifat Kriminal</td><td>0 Kali</td></tr>
                                      <tr><td>6.2 Bersifat Politis</td><td>0 Kali</td></tr>
                                      <tr><td>6.3 Kebakaran</td><td>0 Kali</td></tr>
                                      <tr><td>6.4 Bencana Alam</td><td>0 Kali</td></tr>
                                      <tr><td>6.5 Lain-lain</td><td>0 Kali</td></tr>
                                    </tbody>
                                  </table>
                                </div>
                              
                                <!-- Final Total -->
                                <div class="fw-bold">
                                  <p>JUMLAH TOTAL (1+2+3+4): <span>0 Orang</span> (Pria: <span>0 Orang</span>) (Wanita: <span>0 Orang</span>)</p>
                                </div>
                              </div>

                        </div>
                    </div><!-- end tab pane -->
                    <div class="tab-pane" id="navtabs2-home" role="tabpanel">
                        <div class="security mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title fw-bold">Data Penanggung Jawab Keamanan</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="text-nowrap" rowspan="3">No</th>
                                            <th scope="col" class="text-nowrap" rowspan="3">Nama</th>
                                            <th scope="col" class="text-nowrap" rowspan="3">Jabatan</th>
                                            <th scope="col" class="text-nowrap" rowspan="3">Unit Kerja</th>
                                            <th scope="col" class="text-nowrap" colspan="7">Pelatihan Unit Pengamanan</th>
                                            <th scope="col" class="text-nowrap" rowspan="3">Keterangan</th>
                                            <th scope="col" class="text-nowrap" rowspan="3">Action</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowrap" colspan="7">Kualifikasi</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowrap">Pelatihan SMP</th>
                                            <th scope="col" class="text-nowrap">Auditor SMP</th>
                                            <th scope="col" class="text-nowrap">Utama</th>
                                            <th scope="col" class="text-nowrap">Investigasi</th>
                                            <th scope="col" class="text-nowrap">Mansirik</th>
                                            <th scope="col" class="text-nowrap">Stackholder Management</th>
                                            <th scope="col" class="text-nowrap">Pendidikan Terakhir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- <tr>
                                            <td>1</td>
                                            <td>DITAMA NASTARI GEMILANG. CV (LOLOS CSMS)</td>
                                            <td>
                                                <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="security-personil mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold">Data Personil Keamanan Eksternal</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Instansi</th>
                                            <th scope="col">Satuan Wilayah</th>
                                            <th scope="col">Nomor Surat Perintah</th>
                                            <th scope="col">Keterangan</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- <tr>
                                            <td>1</td>
                                            <td>DITAMA NASTARI GEMILANG. CV (LOLOS CSMS)</td>
                                            <td>
                                                <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="cooperation mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title fw-bold">Data Perjanjian Kerjasama Eksternal</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Instansi</th>
                                            <th scope="col">Nama / Satuan Wilayah</th>
                                            <th scope="col">Nomor PKT</th>
                                            <th scope="col">Judul PKT</th>
                                            <th scope="col">Masa Berlaku</th>
                                            <th scope="col">Keterangan</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- <tr>
                                            <td>1</td>
                                            <td>DITAMA NASTARI GEMILANG. CV (LOLOS CSMS)</td>
                                            <td>
                                                <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- end tab pane -->

                    <div class="tab-pane" id="navtabs2-security" role="tabpanel">
                        <div class="cooperation mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title">Data Personil Satuan Pengamanan</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" rowspan="2">No</th>
                                            <th scope="col" rowspan="2">Nama Anggota</th>
                                            <th scope="col" rowspan="2">Unit Kerja</th>
                                            <th scope="col" rowspan="2">NID</th>
                                            <th scope="col" rowspan="2">No Registrasi KTA</th>
                                            <th scope="col" rowspan="2">KTA Berlaku</th>
                                            <th scope="col" rowspan="2">Jabatan</th>
                                            <th scope="col" rowspan="2">Tempat, Tanggal Lahir</th>
                                            <th scope="col" rowspan="2">Umur</th>
                                            <th scope="col" colspan="3">Kualifikasi</th>
                                            <th scope="col" rowspan="2">Pendidikan Umum Terakhir</th>
                                            <th scope="col" rowspan="2">Keterangan</th>
                                            <th scope="col" rowspan="2">Action</th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Pratama</th>
                                            <th scope="col">Madya</th>
                                            <th scope="col">Utama</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- <tr>
                                            <td>1</td>
                                            <td>DITAMA NASTARI GEMILANG. CV (LOLOS CSMS)</td>
                                            <td>
                                                <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- end tab pane -->
                    <div class="tab-pane" id="navtabs2-aght" role="tabpanel">
                        <div class="cooperation mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title">Data AGHT</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" rowspan="2">No</th>
                                            <th scope="col" rowspan="2">Uraian Kegiatan</th>
                                            <th scope="col" colspan="3">Waktu Kejadian Perkara</th>
                                            <th scope="col" rowspan="2">Kerugian Akibat Yang Ditimbulkan</th>
                                            <th scope="col" rowspan="2">Tindakan Pengamanan Setelah Kejadian</th>
                                            <th scope="col" rowspan="2">Aparat Yang Dihubungi</th>
                                            <th scope="col" rowspan="2">Keterangan</th>
                                            <th scope="col" rowspan="2">Action</th>
                                        </tr>
                                        <tr>
                                            <th scope="col">Tanggal</th>
                                            <th scope="col">Pukul</th>
                                            <th scope="col">Lokasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- <tr>
                                            <td>1</td>
                                            <td>DITAMA NASTARI GEMILANG. CV (LOLOS CSMS)</td>
                                            <td>
                                                <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- end tab pane -->
                    <div class="tab-pane" id="navtabs2-atribut" role="tabpanel">
                        <div class="atribut mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title fw-bold">Atribut & Peralatan</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="text-nowrap" rowspan="2">No</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Uraian</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Status Kepemilikan</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Satuan</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Jumlah Standar Kontrak</th>
                                            <th scope="col" class="text-nowrap" colspan="2">Kondisi</th>
                                            <th scope="col" class="text-nowrap" colspan="2">Masa Berlaku</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Keterangan</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Action</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowrap">Ada</th>
                                            <th scope="col" class="text-nowrap">Tidak</th>
                                            <th scope="col" class="text-nowrap">Operasi / Berlaku</th>
                                            <th scope="col" class="text-nowrap">Rusak / Kadaluarsa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- <tr>
                                            <td>1</td>
                                            <td>DITAMA NASTARI GEMILANG. CV (LOLOS CSMS)</td>
                                            <td>
                                                <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="administrasi mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title fw-bold">Administrasi</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="text-nowrap" rowspan="2">No</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Uraian</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Status Kepemilikan</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Satuan</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Jumlah Standar Kontrak</th>
                                            <th scope="col" class="text-nowrap" colspan="2">Kondisi</th>
                                            <th scope="col" class="text-nowrap" colspan="2">Masa Berlaku</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Keterangan</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Action</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowrap">Ada</th>
                                            <th scope="col" class="text-nowrap">Tidak</th>
                                            <th scope="col" class="text-nowrap">Operasi / Berlaku</th>
                                            <th scope="col" class="text-nowrap">Rusak / Kadaluarsa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- <tr>
                                            <td>1</td>
                                            <td>DITAMA NASTARI GEMILANG. CV (LOLOS CSMS)</td>
                                            <td>
                                                <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="sarana mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title fw-bold">Sarana & Prasarana</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="text-nowrap" rowspan="2">No</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Uraian</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Status Kepemilikan</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Satuan</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Jumlah Standar Kontrak</th>
                                            <th scope="col" class="text-nowrap" colspan="2">Kondisi</th>
                                            <th scope="col" class="text-nowrap" colspan="2">Masa Berlaku</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Keterangan</th>
                                            <th scope="col" class="text-nowrap" rowspan="2">Action</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowrap">Ada</th>
                                            <th scope="col" class="text-nowrap">Tidak</th>
                                            <th scope="col" class="text-nowrap">Operasi / Berlaku</th>
                                            <th scope="col" class="text-nowrap">Rusak / Kadaluarsa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- <tr>
                                            <td>1</td>
                                            <td>DITAMA NASTARI GEMILANG. CV (LOLOS CSMS)</td>
                                            <td>
                                                <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- end tab pane -->
                    <div class="tab-pane" id="navtabs2-tka" role="tabpanel">
                        <div class="cooperation mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title">Data Tenaga Kerja Asing</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="text-nowarp" rowspan="3">No</th>
                                            <th scope="col" class="text-nowarp" rowspan="3">Nama</th>
                                            <th scope="col" class="text-nowarp" rowspan="3">Kebangsaan</th>
                                            <th scope="col" class="text-nowarp" rowspan="3">Perusahaan</th>
                                            <th scope="col" class="text-nowarp" rowspan="3">Jabatan / Keahlian</th>
                                            <th scope="col" class="text-nowarp" colspan="6">Kategori</th>
                                            <th scope="col" class="text-nowarp" rowspan="2" colspan="2">Tanggal</th>
                                            <th scope="col" class="text-nowarp" rowspan="3">Keterangan</th>
                                            <th scope="col" class="text-nowarp" rowspan="3">Action</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowarp" colspan="2">Tamu</th>
                                            <th scope="col" class="text-nowarp" colspan="4">Pekerja</th>  
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-nowarp">Paspor</th>
                                            <th scope="col" class="text-nowarp">Visa Kunjungan</th>
                                            <th scope="col" class="text-nowarp">Paspor</th>
                                            <th scope="col" class="text-nowarp">Vitas</th>
                                            <th scope="col" class="text-nowarp">Kitas</th>
                                            <th scope="col" class="text-nowarp">RPTKA</th>
                                            <th scope="col" class="text-nowarp">Datang</th>
                                            <th scope="col" class="text-nowarp">Kembali</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- <tr>
                                            <td>1</td>
                                            <td>DITAMA NASTARI GEMILANG. CV (LOLOS CSMS)</td>
                                            <td>
                                                <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- end tab pane -->
                    <div class="tab-pane" id="navtabs2-kerawanan" role="tabpanel">
                        <div class="cooperation mb-3">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle">
                                  <thead class="table-light">
                                    <tr>
                                      <th>No.</th>
                                      <th>JENIS KERAWANAN</th>
                                      <th>JUMLAH KEJADIAN</th>
                                      <th colspan="2">TINDAK LANJUT</th>
                                      <th colspan="2">JUMLAH TINDAK LANJUT</th>
                                    </tr>
                                    <tr>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th>DILAPORKAN</th>
                                      <th>TIDAK DILAPORKAN</th>
                                      <th>SELESAI</th>
                                      <th>ON PROGRESS</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <!-- Baris 1 - 17 -->
                                    <tr>
                                      <td>1</td>
                                      <td>Pembunuhan</td>
                                      <td>Nihil</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                    </tr>
                                    <tr>
                                      <td>2</td>
                                      <td>Penganiaayaan</td>
                                      <td>Nihil</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                    </tr>
                                    <tr>
                                      <td>3</td>
                                      <td>Penculikan</td>
                                      <td>Nihil</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                    </tr>
                                    <tr>
                                      <td>4</td>
                                      <td>Pencurian dengan Kekerasan</td>
                                      <td>Nihil</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                    </tr>
                                    <tr>
                                      <td>5</td>
                                      <td>Pencurian dengan Pemberatan</td>
                                      <td>Nihil</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                    </tr>
                                    <tr>
                                      <td>6</td>
                                      <td>Curanmor</td>
                                      <td>Nihil</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                    </tr>
                                    <tr>
                                      <td>7</td>
                                      <td>Perkosaan</td>
                                      <td>Nihil</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                    </tr>
                                    <tr>
                                      <td>8</td>
                                      <td>Pemerasan</td>
                                      <td>Nihil</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                    </tr>
                                    <tr>
                                      <td>9</td>
                                      <td>Perjudian</td>
                                      <td>Nihil</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                    </tr>
                                    <tr>
                                      <td>10</td>
                                      <td>Penipuan</td>
                                      <td>Nihil</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                    </tr>
                                    <tr>
                                      <td>11</td>
                                      <td>Penggelapan</td>
                                      <td>Nihil</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                    </tr>
                                    <tr>
                                      <td>12</td>
                                      <td>Pembakaran</td>
                                      <td>Nihil</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                    </tr>
                                    <tr>
                                      <td>13</td>
                                      <td>Pengrusakan</td>
                                      <td>Nihil</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                    </tr>
                                    <tr>
                                      <td>14</td>
                                      <td>Uang Palsu</td>
                                      <td>Nihil</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                    </tr>
                                    <tr>
                                      <td>15</td>
                                      <td>Pencurian dalam keluarga</td>
                                      <td>Nihil</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                    </tr>
                                    <tr>
                                      <td>16</td>
                                      <td>Demonstrasi</td>
                                      <td>Nihil</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                    </tr>
                                    <tr>
                                      <td>17</td>
                                      <td>Lain-lain Kejahatan/Pelanggaran</td>
                                      <td>Nihil</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                      <td>-</td>
                                    </tr>
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

