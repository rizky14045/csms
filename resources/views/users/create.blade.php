@extends('layout.app')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet"href="https://unpkg.com/leaflet.fullscreen@1.6.0/Control.FullScreen.css" />
    <style>
        #map {
            height: 400px;
            border-radius: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="py-3 d-flex justify-content-between">
        <h4>User Management - Create</h4>
    </div>

    <div class="card">
        <div class="card-body">

            <form id="form-user" action="{{ route('users.store') }}" method="POST">
                @csrf

                {{-- Nama --}}
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                {{-- Role --}}
                <div class="mb-3">
                    <label>Role</label>
                    <select id="roleSelect" name="role" class="form-select" required>
                        <option value="">Pilih Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Password --}}
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                {{-- Confirm --}}
                <div class="mb-3">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <div class="map-location d-none" id="mapSection">
                    <hr>
                    <h5 class="text-muted">Jika ingin membuat unit maka wajib untuk mengisi titik lokasi</h5>
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

                    {{-- MAP --}}
                    <div class="mb-3">
                        <label>Pilih Lokasi</label>
                        <div id="map"></div>
                    </div>

                    {{-- LAT LONG --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Latitude</label>
                            <input type="text" id="latitude" name="latitude" class="form-control" readonly required>
                        </div>
                        <div class="col-md-6">
                            <label>Longitude</label>
                            <input type="text" id="longitude" name="longitude" class="form-control" readonly required>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button class="btn btn-success">Simpan</button>
                </div>

            </form>

        </div>
    </div>
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
