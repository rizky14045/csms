@extends('bujp.layout.app')
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
            <li class="breadcrumb-item"><a href="{{route('bujp.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Assesment</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <a href="{{route('bujp.assesment.index')}}" class="btn btn-danger">Back</a>
                                <h4 class="text-center pb-4 fw-bold">Hasil Assesment {{$assesment->vendor->name}} pada Triwulan Ke {{$assesment->triwulan}} Tahun {{date('Y', strtotime($assesment->date))}}</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-bordered text-center align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col">No</th>
                                                    <th scope="col">Proses Bisnis</th>
                                                    <th scope="col">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($categories as $category)
                                                    
                                                    <tr>
                                                        <td>{{$loop->iteration}}</td>
                                                        <td>{{$category->category_name}}</td>
                                                        <td>{{$category->average}}</td>
                                                       
                                                    </tr>
                                                @endforeach
                                                <tr class="table-info">
                                                    <td>Skor Marturity</td>
                                                    <td colspan="2">{{number_format($categories->avg('average'),2)}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h4 class="fw-bold text-center">Assesment Marturity Level Pengelolaan Proses Bisnis Pengamanan</h4>
                                        <div class="mx-auto col-md-9">
                                            <canvas id="myChart"></canvas>
                                        </div>
                                        <span class="fw-bold me-5">TW - {{$assesment->triwulan}}</span>

                                    </div>
                                </div>
                         
                            </div> <!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div> <!-- end row -->
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection
@section('scripts')
<script>
    var chartData = {!! $chartJson !!};
    const ctx = document.getElementById('myChart');
  
    new Chart(ctx, {
      type: 'radar',
      data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Average Level',
                data: chartData.data,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
      options: {
        plugins: {
            legend: { display: false }, // Menghilangkan legend
        },
        scales: {
            y: { beginAtZero: true },
            r: {
                min: 0, // Paksa batas bawah ke 0
                max: 5, // Paksa batas atas ke 5 (tidak bisa lebih)
                ticks: {
                    stepSize: 1, // Angka hanya naik 1 per 1 (0,1,2,3,4,5)
                    callback: function(value) {
                        return value.toFixed(2); // Hanya tampilkan nilai dalam range
                    },
                    font: {
                        size: 12 // Atur ukuran font jika perlu
                    }
                },
            }
        }
      }
    });
  </script>
  
@endsection

