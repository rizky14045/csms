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
                        <a class="nav-link active" data-bs-toggle="tab" href="#navtabs2-form" role="tab">
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
                        <a class="nav-link" href="{{route('user.monthly-audit.form-attribute.index',['monthlyId' => $monthlyId])}}">
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
                    <div class="tab-pane active" id="navtabs2-form" role="tabpanel">
                        <div class="security mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="title fw-bold">Data Penanggung Jawab Keamanan</span>
                            </div>
                            <div class="container my-4">
                                <h5>BULAN : {{$monthlyReport->report_date}}<h5>
                                <!-- Section 1 -->
                                <form action="{{route('user.monthly-audit.form-formulir.saveFormulir',['monthlyId'=>$monthlyId])}}" method="POST">
                                  @csrf
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
                                              @php
                                                $total = $employee->employee_man + $employee->employee_woman + $employee->student_man + $employee->student_woman;
                                                $totalEmployee = $employee->employee_man + $employee->employee_woman;
                                                $totalStudent = $employee->student_man + $employee->student_woman;
                                                $totalMan = $employee->employee_man + $employee->student_man;
                                                $totalWoman =$employee->employee_woman  + $employee->student_woman;
                                            @endphp
                                              <tr>
                                                <td>1.1 Pegawai Tetap</td>
                                                <td>{{number_format($totalEmployee)}} Orang</td>
                                                <td><input type="number" required min="0" class="form-control w-25 d-inline" value="{{$employee->employee_man}}" name="employee_man"> Orang</td>
                                                <td><input type="number" required min="0" class="form-control w-25 d-inline" value="{{$employee->employee_woman}}" name="employee_woman"> Orang</td>
                                              </tr>
                                              <tr>
                                                <td>1.2 Siswa OJT</td>
                                                <td>{{number_format($totalStudent)}} Orang</td>
                                                <td><input type="number" required min="0" class="form-control w-25 d-inline" value="{{$employee->student_man}}" name="student_man"> Orang</td>
                                                <td><input type="number" required min="0" class="form-control w-25 d-inline" value="{{$employee->student_woman}}" name="student_woman"> Orang</td>
                                              </tr>
                                              <tr class="fw-bold">

                                                <td>Jumlah</td>
                                                <td>{{number_format($total)}} Orang</td>
                                                <td>{{number_format($totalMan)}} Orang</td>
                                                <td>{{number_format($totalWoman)}} Orang</td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                
                                  <!-- Section 2 -->
                                  <div class="mb-3">
                                    <div class="d-flex justify-content-between pb-3">
                                      <h6 class="fw-bold">2. JUMLAH KARYAWAN OUTSOURCING</h6>
                                      <button class="btn btn-success btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#outSourceModal">Tambah Data</button>
                                    </div>
                                    <div class="table-responsive">
                                      <table class="table table-bordered text-center align-middle">
                                          <thead class="table-light">
                                              <tr>
                                                <th>Personil</th>
                                                <th>Total Orang</th>
                                                <th>Pria</th>
                                                <th>Wanita</th>
                                                <th>Action</th>
                                              </tr>
                                            </thead>
                                        <tbody class="outsource-table">
                                          @foreach ($outsources as $outsource)
                                            <tr class="outsource-row">
                                                <td><input type="text" class="form-control w-100 d-inline" name="name[]" value="{{$outsource->name}}"></td>
                                                <td><input type="number" class="form-control w-25 d-inline" name="total[]" value="{{$outsource->total}}" min="0"> Orang</td>
                                                <td><input type="number" class="form-control w-25 d-inline" name="man[]" value="{{$outsource->man}}" min="0"> Orang</td>
                                                <td><input type="number" class="form-control w-25 d-inline" name="woman[]" value="{{$outsource->woman}}" min="0"> Orang</td>
                                                <td>
                                                    <button type="button" class="btn btn-danger" onclick="deleteRow(this)">
                                                        <span class="mdi mdi-trash-can"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                          @endforeach

                                          <tr class="fw-bold sum-outsource">
                                            <td>Jumlah</td>
                                            <td>{{number_format($outsources->sum('total'))}} Orang</td>
                                            <td>{{number_format($outsources->sum('man'))}} Orang</td>
                                            <td colspan="2">{{number_format($outsources->sum('woman'))}} Orang</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                      <button type="submit" class="btn btn-primary float-end">Simpan Form</button>
                                    </div>
                                  </div>
                              </form>

                              
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
                </div>
            </div>
        </div> <!-- end card-->
    </div> <!-- end col -->
</div> <!-- end row -->

<div class="modal fade" id="outSourceModal" tabindex="-1" aria-labelledby="outSourceLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="outSourceLabel">Tambah</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="form-group pb-3">
                <label class="form-label" for="email-id">Nama</label>
                <input type="text" class="form-control mb-0 outsource-name" id="name-employee" placeholder="Masukan Nama">
            </div>
            <div class="form-group pb-3">
                <label class="form-label" for="text-id">Total</label>
                <input type="number" value="0" min="0" class="form-control mb-0 outsource-total" id="total-employee">
            </div>
            <div class="form-group pb-3">
                <label class="form-label" for="text-id">Jumlah Pria</label>
                <input type="number" value="0" min="0" class="form-control mb-0 outsource-man" id="man-employee">
            </div>
            <div class="form-group pb-3">
                <label class="form-label" for="text-id">Jumlah Wanita</label>
                <input type="number" value="0" min="0" class="form-control mb-0 outsource-woman" id="woman-employee">
            </div>
        </div>
        <div class="modal-footer d-flex justify-content-center">
            <button type="button" class="btn btn-primary btn-outsource-add" id="btn-outsource-add">Submit</button>
        </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
  
  $(".btn-outsource-add").unbind('click').click(function () {
      // var getnumber = $('.tb-person').find('.tr-person').last().find('.tr-number').val()

      var name = $('#name-employee').val()
      var total = $('#total-employee').val()
      var man = $('#man-employee').val()
      var woman = $('#woman-employee').val()

      console.log(name,total,man,woman)
      // var number
      // if(getnumber === undefined){

      //     number = 1
      // }else{
      //     number = parseInt(getnumber) + 1
      // }

      $('.sum-outsource').before(
        `<tr class="outsource-row">
              <td><input type="text" class="form-control w-100 d-inline" name="name[]" value="${name}"></td>
              <td><input type="number" class="form-control w-25 d-inline" name="total[]" value="${total}" min="0"> Orang</td>
              <td><input type="number" class="form-control w-25 d-inline" name="man[]" value="${man}" min="0"> Orang</td>
              <td><input type="number" class="form-control w-25 d-inline" name="woman[]" value="${woman}" min="0"> Orang</td>
              <td>
                  <button type="button" class="btn btn-danger" onclick="deleteRow(this)">
                      <span class="mdi mdi-trash-can"></span>
                  </button>
              </td>
          </tr>`
      )
      $('#outSourceModal').modal('hide')
  });
  function deleteRow(e){
      Swal.fire({
              title: 'Hapus Data',
              text: "Apakah kamu ingin menghapus data ?",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Iya !'
          }).then((result) => {
              if (result.isConfirmed) {
                  $(e).parent().parent().remove()
              }
          })
  
  }
</script>
@endsection

