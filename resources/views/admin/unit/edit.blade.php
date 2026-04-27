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
            <h4 class="fs-18 fw-semibold m-0">Unit</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Edit Data Unit</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.unit.update', ['unit' => $unit->id]) }}" class="my-4" method="POST"
                        id="form-unit" onsubmit="confirmSave('form-unit', 'Data unit akan disimpan')">
                        @csrf
                        @method('PATCH')
                        <!-- Formulir Pendaftaran -->
                        <div class="col-xl-12">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Nama Unit</label>
                                <input class="form-control" type="text" id="name" required=""
                                    placeholder="Masukan nama unit" name="name" value="{{ old('name', $unit->name) }}">
                                @if ($errors->has('name'))
                                    <div class="error text-danger">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <label for="unit_code" class="form-label">Kode Unit</label>
                                <input class="form-control" type="text" id="unit_code" required=""
                                    placeholder="Masukan kode unit" name="unit_code"
                                    value="{{ old('unit_code', $unit->unit_code) }}">
                                @if ($errors->has('unit_code'))
                                    <div class="error text-danger">{{ $errors->first('unit_code') }}</div>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <label for="type" class="form-label">Tipe Unit</label>
                                <select name="type" id="type" class="form-select">
                                    <option value="">Pilih Tipe Unit</option>
                                    <option value="Pusat" {{ old('type', $unit->type) == 'Pusat' ? 'selected' : '' }}>Pusat
                                    </option>
                                    <option value="Unit" {{ old('type', $unit->type) == 'Unit' ? 'selected' : '' }}>Unit
                                    </option>
                                </select>
                                @if ($errors->has('type'))
                                    <div class="error text-danger">{{ $errors->first('type') }}</div>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea class="form-control" id="address" rows="5" spellcheck="false" name="address">{{ old('address', $unit->address) }}</textarea>
                                @if ($errors->has('address'))
                                    <div class="error text-danger">{{ $errors->first('address') }}</div>
                                @endif
                            </div>
                            {{-- MAP WRAPPER --}}
                            <div id="mapWrapper">

                                {{-- Province & City --}}
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label>Provinsi</label>
                                        <select id="province" class="form-select" name="province_id">
                                            <option value="">Pilih Provinsi</option>
                                            @foreach ($provinces as $prov)
                                                <option value="{{ $prov->id }}"
                                                    {{ $unit && $unit->province_id == $prov->id ? 'selected' : '' }}>
                                                    {{ $prov->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Kota</label>
                                        <select id="city" class="form-select" name="city_id">
                                            <option value="">Pilih Kota</option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}"
                                                    {{ $unit->city_id == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- LAT LNG --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Latitude</label>
                                        <input type="text" id="latitude" name="latitude"
                                            value="{{ $unit->latitude ?? '' }}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Longitude</label>
                                        <input type="text" id="longitude" name="longitude"
                                            value="{{ $unit->longitude ?? '' }}" class="form-control">
                                    </div>
                                </div>

                                {{-- MAP --}}
                                <div class="mb-3">
                                    <label>Pilih Lokasi</label>
                                    <div id="map"></div>
                                </div>



                            </div>

                            <div class="form-group row mt-2">
                                <div class="col-12">
                                    <div class="d-flex gap-3 justify-content-end">

                                        <a href="{{ route('admin.unit.index') }}" class="btn btn-danger"> Kembali</a>
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
    <script>
        const defaultLat = "{{ $unit->latitude ?? '' }}";
        const defaultLng = "{{ $unit->longitude ?? '' }}";

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

                        cityLayer = L.geoJSON(geo, {
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
