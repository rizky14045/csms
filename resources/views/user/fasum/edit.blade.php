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


    <div class="py-3 d-flex align-items-center gap-2">
        <a href="{{ route('user.fasum.index') }}" class="text-muted text-decoration-none">
            <i data-feather="arrow-left" style="width:18px;height:18px;"></i>
        </a>
        <h4 class="mb-0">Edit Fasilitas Umum</h4>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-xl-9">
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i data-feather="edit-2" style="width:18px;height:18px;color:#4361ee;"></i>
                        <h6 class="mb-0 fw-semibold">Edit Data Fasilitas Umum</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('user.fasum.update', ['fasum' => $fasum->id]) }}" method="POST"
                        id="form-unit" onsubmit="confirmSave('form-unit', 'Data fasilitas umum akan disimpan')">
                        @csrf
                        @method('PATCH')
                        <div class="col-xl-12">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Nama Fasilitas</label>
                                <input class="form-control" type="text" id="name" required=""
                                    placeholder="Masukan nama unit" name="name" value="{{ $fasum->name }}">
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
                                            {{ old('type_id', $fasum->type_id) == $type->id ? 'selected' : '' }}>
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
                                <textarea class="form-control" id="address" rows="5" spellcheck="false" name="address">{{ old('address', $fasum->address) }}</textarea>
                                @if ($errors->has('address'))
                                    <div class="error text-danger">{{ $errors->first('address') }}</div>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <label for="contact" class="form-label">Kontak <span class="text-muted small">(opsional)</span></label>
                                <input class="form-control" type="text" id="contact" name="contact"
                                    placeholder="Nomor HP, email, atau kontak lainnya"
                                    value="{{ old('contact', $fasum->contact) }}">
                                @if ($errors->has('contact'))
                                    <div class="error text-danger">{{ $errors->first('contact') }}</div>
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
                                                    {{ $fasum && $fasum->province_id == $prov->id ? 'selected' : '' }}>
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
                                                    {{ $fasum->city_id == $city->id ? 'selected' : '' }}>
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
                                        <input type="number" step="any" id="latitude" name="latitude"
                                            min="-11" max="6.5" placeholder="Contoh: -6.914744"
                                            value="{{ old('latitude', $fasum->latitude) }}"
                                            class="form-control @error('latitude') is-invalid @enderror" required>
                                        @error('latitude')
                                            <div class="error text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label>Longitude</label>
                                        <input type="number" step="any" id="longitude" name="longitude"
                                            min="95" max="141" placeholder="Contoh: 107.609810"
                                            value="{{ old('longitude', $fasum->longitude) }}"
                                            class="form-control @error('longitude') is-invalid @enderror" required>
                                        @error('longitude')
                                            <div class="error text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Bisa diisi manual, atau klik lokasi langsung di peta di bawah.</div>
                                </div>

                                {{-- MAP --}}
                                <div class="mb-3">
                                    <label>Pilih Lokasi</label>
                                    <div id="map"></div>
                                </div>



                            </div>

                            <div class="d-flex gap-2 justify-content-end mt-2">
                                <a href="{{ route('user.fasum.index') }}" class="btn btn-danger">
                                    <i data-feather="arrow-left" style="width:14px;height:14px;" class="me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i data-feather="save" style="width:14px;height:14px;" class="me-1"></i>Simpan
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        const defaultLat = "{{ $fasum->latitude ?? '' }}";
        const defaultLng = "{{ $fasum->longitude ?? '' }}";

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

            // INPUT MANUAL LAT/LONG -> SYNC KE MARKER PETA
            function syncMarkerFromInput() {
                let lat = parseFloat(document.getElementById('latitude').value);
                let lng = parseFloat(document.getElementById('longitude').value);

                if (isNaN(lat) || isNaN(lng)) return;

                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);
                map.setView([lat, lng], Math.max(map.getZoom(), 13));
            }

            document.getElementById('latitude').addEventListener('change', syncMarkerFromInput);
            document.getElementById('longitude').addEventListener('change', syncMarkerFromInput);

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

        feather.replace();
    </script>
@endsection
