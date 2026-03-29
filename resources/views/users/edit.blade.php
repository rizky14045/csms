@extends('layout.app')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen@1.6.0/Control.FullScreen.css" />
    <style>
        #map {
            height: 400px;
            border-radius: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                </div>

                {{-- Role --}}
                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" id="roleSelect" class="form-select">
                        <option value="">Pilih Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role['id'] }}"
                                {{ old('role', $user->roles->first()->id ?? null) == $role['id'] ? 'selected' : '' }}>
                                {{ $role['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                
                {{-- MAP WRAPPER --}}
                <div id="mapWrapper" style="display:none;">
                    <hr>

                    {{-- Province & City --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Provinsi</label>
                            <select id="province" class="form-select" name="province_id">
                                <option value="">Pilih Provinsi</option>
                                @foreach ($provinces as $prov)
                                    @isset($profile)
                                        <option value="{{ $prov->id }}"
                                            {{ $profile && $profile->province_id == $prov->id ? 'selected' : '' }}>
                                            {{ $prov->name }}
                                        </option>
                                    @else
                                        <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                    @endisset
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Kota</label>
                            <select id="city" class="form-select" name="city_id">
                                <option value="">Pilih Kota</option>
                                @isset($profile)
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ $profile->city_id == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>

                    {{-- MAP --}}
                    <div class="mb-3">
                        <label>Pilih Lokasi</label>
                        <div id="map"></div>
                    </div>

                    {{-- LAT LNG --}}
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" id="latitude" name="latitude" value="{{ $profile->latitude ?? '' }}"
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="longitude" name="longitude" value="{{ $profile->longitude ?? '' }}"
                                class="form-control">
                        </div>
                    </div>

                </div>

                <div class="mt-3 text-end">
                    <button class="btn btn-success">Simpan</button>
                </div>

            </form>

        </div>
    </div>
@endsection


@section('scripts')
    <script>
        const currentRoleId = "{{ auth()->user()->role_id }}";
        const defaultLat = "{{ $profile->latitude ?? '' }}";
        const defaultLng = "{{ $profile->longitude ?? '' }}";

        const routeProvince = "{{ route('geo.province', ':id') }}";
        const routeCities = "{{ route('geo.cities', ':id') }}";
        const routeCity = "{{ route('geo.city', ':id') }}";
    </script>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.fullscreen@1.6.0/Control.FullScreen.js"></script>

    <script>
        let map;
        let marker = null;
        let provinceLayer = null;
        let cityLayer = null;

        document.addEventListener("DOMContentLoaded", function() {

            const roleSelect = document.getElementById('roleSelect');
            const mapWrapper = document.getElementById('mapWrapper');
            const provinceSelect = document.getElementById('province');
            const citySelect = document.getElementById('city');

            // INIT MAP
            map = L.map('map', {
                minZoom: 5,
                maxZoom: 18,
                fullscreenControl: true,
            }).setView([-2.5, 118], 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // MARKER EXISTING
            if (defaultLat && defaultLng) {
                let lat = parseFloat(defaultLat);
                let lng = parseFloat(defaultLng);

                marker = L.marker([lat, lng]).addTo(map);
                map.setView([lat, lng], 13);
            }

            // CLICK MAP
            map.on('click', function(e) {
                let lat = e.latlng.lat;
                let lng = e.latlng.lng;

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);
            });

            // TOGGLE MAP
            function toggleMap() {
                const selectedRole = roleSelect.value || currentRoleId;

                if (selectedRole == 3) {
                    mapWrapper.style.display = 'block';

                    setTimeout(() => {
                        map.invalidateSize();
                    }, 300);
                } else {
                    mapWrapper.style.display = 'none';
                }
            }

            toggleMap();
            roleSelect.addEventListener('change', toggleMap);

            // ======================
            // PROVINCE
            // ======================
            provinceSelect.addEventListener('change', function() {

                let id = this.value;
                if (!id) return;

                if (provinceLayer) map.removeLayer(provinceLayer);
                if (cityLayer) map.removeLayer(cityLayer);

                fetch(routeProvince.replace(':id', id))
                    .then(res => res.json())
                    .then(res => {

                        let geo = typeof res.geometry === 'string' ?
                            JSON.parse(res.geometry) :
                            res.geometry;

                        provinceLayer = L.geoJSON(geo).addTo(map);

                        let bounds = provinceLayer.getBounds();
                        map.fitBounds(bounds);

                        setTimeout(() => {
                            map.setZoom(map.getZoom() + 2);
                        }, 300);
                    });

                fetch(routeCities.replace(':id', id))
                    .then(res => res.json())
                    .then(data => {

                        citySelect.innerHTML = '<option value="">Pilih Kota</option>';

                        data.forEach(item => {
                            let opt = document.createElement('option');
                            opt.value = item.id;
                            opt.text = item.name;
                            citySelect.appendChild(opt);
                        });
                    });
            });

            // ======================
            // CITY
            // ======================
            citySelect.addEventListener('change', function() {

                let id = this.value;
                if (!id) return;

                if (cityLayer) map.removeLayer(cityLayer);

                fetch(routeCity.replace(':id', id))
                    .then(res => res.json())
                    .then(res => {

                        let geo = typeof res.geometry === 'string' ?
                            JSON.parse(res.geometry) :
                            res.geometry;

                        cityLayer = L.geoJSON(geo,{
                            style: {
                                color: 'red',
                                weight: 2,
                                fillOpacity: 0.2
                            }
                        }).addTo(map);

                        let bounds = cityLayer.getBounds();
                        map.fitBounds(bounds);

                        setTimeout(() => {
                            map.setZoom(map.getZoom() + 2);
                        }, 300);
                    });
            });

        });
    </script>
@endsection
