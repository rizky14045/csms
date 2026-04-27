@extends('layout.app')
@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet"href="https://unpkg.com/leaflet.fullscreen@1.6.0/Control.FullScreen.css" />
    <style>
        .accordion-button::after {
            filter: invert(100%);
        }

        #map {
            height: 500px;
            width: 100%;
        }
    </style>
@stop
@section('content')


    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Fasilitas Umum</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Tambah Data Fasilitas Umum</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('user.fasum.store') }}" class="my-4" method="POST" id="form-unit"
                        onsubmit="confirmSave('form-unit', 'Data unit akan disimpan')">
                        @csrf
                        <!-- Formulir Pendaftaran -->
                        <div class="col-xl-12">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Nama Fasilitas</label>
                                <input class="form-control" type="text" id="name" required=""
                                    placeholder="Masukan nama unit" name="name" value="{{ old('name') }}">
                                @if ($errors->has('name'))
                                    <div class="error text-danger">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <label for="type" class="form-label">Tipe</label>
                                <select name="type_id" id="type" class="form-select">
                                    <option value="">Pilih Tipe Fasilitas Umum</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('type') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('type'))
                                    <div class="error text-danger">{{ $errors->first('type') }}</div>
                                @endif
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea class="form-control" id="address" rows="5" spellcheck="false" name="address">{{ old('address') }}</textarea>
                                @if ($errors->has('address'))
                                    <div class="error text-danger">{{ $errors->first('address') }}</div>
                                @endif
                            </div>
                            <div class="map-location" id="mapSection">
                                {{-- Province & City --}}
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label>Provinsi</label>
                                        <select id="province" class="form-select" name="province_id">
                                            <option value="">Pilih Provinsi</option>
                                            @foreach ($provinces as $prov)
                                                <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Kota</label>
                                        <select id="city" class="form-select" name="city_id">
                                            <option value="">Pilih Kota</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- LAT LONG --}}
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label>Latitude</label>
                                        <input type="text" id="latitude" name="latitude" class="form-control" readonly
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Longitude</label>
                                        <input type="text" id="longitude" name="longitude" class="form-control" readonly
                                            required>
                                    </div>
                                </div>

                                {{-- MAP --}}
                                <div class="mb-3">
                                    <label>Pilih Lokasi</label>
                                    <div id="map"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12">
                                    <div class="d-flex gap-3 justify-content-end">

                                        <a href="{{ route('user.fasum.index') }}" class="btn btn-danger"> Kembali</a>
                                        <button type="submit" class="btn btn-success">
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div> <!-- end card body -->
            </div><!-- end card -->
            {{-- <div class="card">
            <div class="card-body">
                <h5>Pilih titik lokasi unit</h5>
                <div id="map" style="width: 100%; height: 800px; margin-top: 20px;"></div>
            </div>
        </div> --}}
        </div><!-- end col -->
    </div> <!-- end row -->
@endsection
@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.fullscreen@1.6.0/Control.FullScreen.js"></script>

    <script>
        let map;
        let provinceLayer = null;
        let cityLayer = null;
        let marker = null;

        // ROUTE NAME → JS
        const routeProvince = "{{ route('geo.province', ':id') }}";
        const routeCities = "{{ route('geo.cities', ':id') }}";
        const routeCity = "{{ route('geo.city', ':id') }}";

        document.addEventListener("DOMContentLoaded", function() {

            // BATAS INDONESIA
            let boundsIndonesia = [
                [-11.0, 95.0],
                [6.5, 141.0]
            ];

            map = L.map('map', {
                minZoom: 5,
                maxZoom: 18,
                maxBounds: boundsIndonesia,
                maxBoundsViscosity: 1.0,
                fullscreenControl: true,
            }).setView([-2.5, 118], 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);


            // CLICK MAP
            map.on('click', function(e) {
                let lat = e.latlng.lat;
                let lng = e.latlng.lng;

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);
            });

        });


        // PROVINCE CHANGE
        document.getElementById('province').addEventListener('change', function() {

            let id = this.value;
            if (!id) return;

            if (provinceLayer) map.removeLayer(provinceLayer);
            if (cityLayer) map.removeLayer(cityLayer);

            fetch(routeProvince.replace(':id', id))
                .then(res => res.json())
                .then(res => {

                    let geo = parseGeoJSON(res.geometry);

                    if (provinceLayer) map.removeLayer(provinceLayer);

                    provinceLayer = L.geoJSON(geo, {
                        style: {
                            color: 'blue',
                            weight: 2,
                            fillOpacity: 0.1
                        }
                    }).addTo(map);

                    let bounds = provinceLayer.getBounds();

                    map.fitBounds(bounds, {
                        padding: [20, 20]
                    });

                    // 🔥 zoom tambahan biar masuk
                    setTimeout(() => {
                        map.setZoom(map.getZoom() + 2.5);
                    }, 300);

                });

            fetch(routeCities.replace(':id', id))
                .then(res => res.json())
                .then(data => {

                    let city = document.getElementById('city');
                    city.innerHTML = '<option value="">Pilih Kota</option>';

                    data.forEach(item => {
                        let opt = document.createElement('option');
                        opt.value = item.id;
                        opt.text = item.name;
                        city.appendChild(opt);
                    });
                });

        });


        // CITY CHANGE
        document.getElementById('city').addEventListener('change', function() {

            let id = this.value;
            if (!id) return;

            if (cityLayer) map.removeLayer(cityLayer);

            fetch(routeCity.replace(':id', id))
                .then(res => res.json())
                .then(res => {

                    let geo = parseGeoJSON(res.geometry);

                    if (cityLayer) map.removeLayer(cityLayer);

                    cityLayer = L.geoJSON(geo, {
                        style: {
                            color: 'red',
                            weight: 2,
                            fillOpacity: 0.2
                        }
                    }).addTo(map);

                    let bounds = cityLayer.getBounds();

                    map.fitBounds(bounds, {
                        padding: [20, 20]
                    });

                    // 🔥 zoom lebih dalam lagi
                    setTimeout(() => {
                        map.setZoom(map.getZoom() + 2.5);
                    }, 300);

                });

        });

        function parseGeoJSON(geometry) {
            if (typeof geometry === "string") {
                return JSON.parse(geometry);
            }
            return geometry;
        }
        const roleSelect = document.getElementById('roleSelect');
        const mapSection = document.getElementById('mapSection');

        function toggleMap() {
            let role = roleSelect.value;

            if (role == 3) {
                mapSection.classList.remove('d-none');

                // 🔥 FIX: biar map ke-render ulang kalau sebelumnya hidden
                setTimeout(() => {
                    map.invalidateSize();
                }, 300);

            } else {
                mapSection.classList.add('d-none');

                // optional: reset lat long
                document.getElementById('latitude').value = '';
                document.getElementById('longitude').value = '';

                if (marker) {
                    map.removeLayer(marker);
                }
            }
        }

        // trigger saat change
        roleSelect.addEventListener('change', toggleMap);

        // trigger saat load (kalau ada old value)
        document.addEventListener("DOMContentLoaded", function() {
            toggleMap();
        });
    </script>
@endsection
