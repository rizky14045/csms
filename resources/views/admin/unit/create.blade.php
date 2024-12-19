@extends('admin.layout.app')
@section('styles')
<style>
    .accordion-button::after {
        filter: invert(100%);
    }
    <style>
        #map { height: 500px; width: 100%; }
    </style>
</style>
@stop
@section('content')
    

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Unit</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('admin.unit.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Unit</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <form action="index.html" class="my-4">
                    <!-- Formulir Pendaftaran -->
                    <div class="col-xl-9">
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Nama Unit</label>
                            <input class="form-control" type="email" id="emailaddress" required="" placeholder="Masukan nama unit">
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Alamat</label>
                            <textarea class="form-control" id="example-textarea" rows="5" spellcheck="false"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Latitude</label>
                            <input class="form-control" type="text" id="latitude" required="" readonly placeholder="Masukan latitude">
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailaddress" class="form-label">Longitude</label>
                            <input class="form-control" type="text" id="longitude" required="" readonly placeholder="Masukan longitude">
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">

                                    <a href="{{route('admin.unit.index')}}" class="btn btn-success"> Back</a>
                                    <button class="btn btn-primary" type="submit"> Tambah</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
        <div class="card">
            <div class="card-body">
                <h5>Pilih titik lokasi unit</h5>
                <div id="map" style="width: 100%; height: 800px; margin-top: 20px;"></div>
            </div>
        </div>
    </div><!-- end col -->
</div> <!-- end row -->
@endsection
@section('scripts')
{{-- <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Inisialisasi peta
        const map = L.map('map').setView([-6.200000, 106.816666], 13); // Jakarta sebagai default

        // Tambahkan tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Tambahkan marker default
        let marker = L.marker([-6.200000, 106.816666], { draggable: true }).addTo(map);

        // Update input saat marker dipindahkan
        marker.on('moveend', (e) => {
            const { lat, lng } = e.target.getLatLng();
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });

        // Update marker jika peta diklik
        map.on('click', (e) => {
            const { lat, lng } = e.latlng;
            marker.setLatLng([lat, lng]);
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });
    });
</script> --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Inisialisasi peta dengan view seluruh Indonesia
        const map = L.map('map').setView([-2.548926, 118.0148634], 5); // Titik tengah Indonesia

        // Tambahkan tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Event saat peta diklik
        map.on('click', (e) => {
            const { lat, lng } = e.latlng;
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            // Tambahkan marker di lokasi yang diklik
            L.marker([lat, lng]).addTo(map);
        });
    });
</script>
@endsection

