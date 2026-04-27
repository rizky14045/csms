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
    <h4 class="fs-18 fw-semibold m-0">Assesment</h4>
  </div>

  <div class="text-end">
    <ol class="breadcrumb m-0 py-0">
      <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
      <li class="breadcrumb-item active">Assesment</li>
    </ol>
  </div>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card">

      <div class="card-body">

        {{-- FILTER --}}
        <div class="d-flex justify-content-between w-100 mb-3">
          <form action="{{ route('user.assesment.index') }}" class="d-flex align-items-end gap-2">
            <div>
              <label class="form-label mb-1">Tanggal</label>
              <input type="date" class="form-control" name="date" value="{{ request('date', '') }}">
            </div>
            <button type="submit" class="btn btn-primary">
              🔍 Cari
            </button>
          </form>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive" style="overflow-x:auto;">
          <table class="table table-bordered text-center align-middle">

            <thead class="table-light">
              <tr>
                <th style="min-width:60px;">No</th>
                <th style="min-width:150px;">NPWP</th>
                <th style="min-width:220px;">Perusahaan</th>
                <th style="min-width:160px;">Nomor Kontrak</th>
                <th style="min-width:100px;">Tahun</th>
                <th style="min-width:100px;">Triwulan</th>
                <th style="min-width:130px;">Tgl Buat</th>
                <th style="min-width:130px;">Kirim BUJP</th>
                <th style="min-width:130px;">Kirim Pusat</th>
                <th style="min-width:220px;">Status</th>
                <th style="min-width:240px;">Action</th>
              </tr>
            </thead>

            <tbody>
              @foreach ($assesments as $assesment)

              <tr>

                <td>{{$loop->iteration}}</td>

                <td>{{$assesment->bujp_profile->npwp}}</td>

                <td style="text-align:left;">
                  {{$assesment->vendor->name}}
                </td>

                <td>{{$assesment->contract}}</td>

                <td>{{$assesment->year}}</td>

                <td>{{$assesment->triwulan}}</td>

                <td>
                  {{ \Carbon\Carbon::parse($assesment->created_at)->format('d-m-Y') }}
                </td>

                <td>
                  {{ $assesment->send_date ? \Carbon\Carbon::parse($assesment->send_date)->format('d-m-Y') : '-' }}
                </td>

                <td>
                  {{ $assesment->send_date_pusat ? \Carbon\Carbon::parse($assesment->send_date_pusat)->format('d-m-Y') : '-' }}
                </td>

                {{-- STATUS --}}
                <td>
                  @php
                  $status = $assesment->send_status;

                  $label = '';
                  $bg = '';
                  $color = '#fff';

                  if ($status == 0) {
                  $label = 'Proses Input BUJP';
                  $bg = '#6c757d';
                  } elseif ($status == 1) {
                  $label = 'Pengecekan Unit';
                  $bg = '#0d6efd';
                  } elseif ($status == 2) {
                  $label = 'Diterima';
                  $bg = '#28a745';
                  } elseif ($status == 3) {
                  $label = 'Perlu Revisi';
                  $bg = '#dc3545';
                  }
                  @endphp

                  <span style="
                            display:inline-block;
                            padding:6px 10px;
                            font-size:12px;
                            border-radius:6px;
                            background:{{ $bg }};
                            color:{{ $color }};
                            font-weight:500;
                            white-space:nowrap;
                        ">
                    {{ $status == 3 ? '⚠ ' : '' }}{{ $label }}
                  </span>
                </td>

                {{-- ACTION --}}
                <td>
                  <div style="
                            display:flex;
                            flex-wrap:wrap;
                            gap:6px;
                            justify-content:left;
                            align-items:center;
                        ">

                    {{-- STATUS 1 --}}
                    @if ($assesment->send_status == 1)

                    @can('send.assesment.bujp.unit')

                    @if(count($assesment->get_invalid_items_question_by_unit) == 0)
                    <form action="{{route('user.assesment.send',['assesment'=>$assesment->id])}}" method="post" style="margin:0;" id="send-assesment-{{ $assesment->id }}" onsubmit="confirmSave('send-assesment-{{ $assesment->id }}', 'Kirim assesment?')">
                      @csrf
                      @method('PATCH')

                      <button type="submit" class="btn btn-success btn-sm" style="min-width:70px;">
                        📤 Kirim
                      </button>
                    </form>
                    @else
                    <button class="btn btn-secondary btn-sm" style="min-width:70px; opacity:0.6;background-color:gray" disabled>
                      📤 Kirim
                    </button>
                    @endif

                    @endcan

                    <a href="{{route('user.assesment.show',['assesment'=>$assesment->id])}}" class="btn btn-info btn-sm" style="min-width:70px;">
                      👁 Show
                    </a>

                    {{-- STATUS >= 2 --}}
                    @elseif($assesment->send_status >= 2)

                    <a href="{{route('user.assesment.preview',['assesment'=>$assesment->id])}}" class="btn btn-info btn-sm" style="min-width:70px;">
                      👁 Show
                    </a>

                    <a href="{{route('user.assesment.report',['assesment'=>$assesment->id])}}" class="btn btn-success btn-sm" style="min-width:80px;">
                      📄 Report
                    </a>

                    @endif

                  </div>
                </td>

              </tr>

              @endforeach
            </tbody>

          </table>
        </div>

      </div>
    </div>
  </div>
</div>

@endsection