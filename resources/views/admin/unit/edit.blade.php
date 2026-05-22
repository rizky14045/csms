@extends('layout.app')
@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen@1.6.0/Control.FullScreen.css" />
    <style>
        .accordion-button::after { filter: invert(100%); }
        #map { height: 500px; width: 100%; }
    </style>
@stop
@section('content')

<div class="py-3 d-flex align-items-center gap-2">
    <a href="{{ route('admin.unit.index') }}" class="text-muted text-decoration-none">
        <i data-feather="arrow-left" style="width:18px;height:18px;"></i>
    </a>
    <h4 class="mb-0">Edit Data Unit</h4>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="card shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center gap-2">
                    <i data-feather="map-pin" style="width:18px;height:18px;color:#4361ee;"></i>
                    <h6 class="mb-0 fw-semibold">Edit Data Unit</h6>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.unit.update', ['unit' => $unit->id]) }}" method="POST" id="form-unit"
                    onsubmit="confirmSave('form-unit', 'Data unit akan disimpan')">
                    @csrf
                    @method('PATCH')

                    <div class="form-group mb-3">
                        <label for="name" class="form-label"><span class="text-danger">*</span> Nama Unit</label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="name"
                            required placeholder="Masukan nama unit" name="name" value="{{ old('name', $unit->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="unit_code" class="form-label"><span class="text-danger">*</span> Kode Unit</label>
                        <input class="form-control @error('unit_code') is-invalid @enderror" type="text" id="unit_code"
                            required placeholder="Masukan kode unit" name="unit_code" value="{{ old('unit_code', $unit->unit_code) }}">
                        @error('unit_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="type" class="form-label"><span class="text-danger">*</span> Tipe Unit</label>
                        <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
                            <option value="">Pilih Tipe Unit</option>
                            <option value="Pusat" {{ old('type', $unit->type) == 'Pusat' ? 'selected' : '' }}>Pusat</option>
                            <option value="Unit"  {{ old('type', $unit->type) == 'Unit'  ? 'selected' : '' }}>Unit</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address"
                            rows="3" name="address">{{ old('address', $unit->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Lokasi --}}
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <span class="fw-semibold small">Lokasi Unit</span>
                        </div>
                        <div class="card-body p-3">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Provinsi</label>
                                    <select id="province" class="form-select" name="province_id">
                                        <option value="">Pilih Provinsi</option>
                                        @foreach ($provinces as $prov)
                                            <option value="{{ $prov->id }}"
                                                {{ $unit->province_id == $prov->id ? 'selected' : '' }}>
                                                {{ $prov->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Kota</label>
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
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" id="latitude" name="latitude"
                                        value="{{ $unit->latitude ?? '' }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" id="longitude" name="longitude"
                                        value="{{ $unit->longitude ?? '' }}" class="form-control">
                                </div>
                            </div>
                            <label class="form-label">Pilih Lokasi pada Peta</label>
                            <div id="map"></div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('admin.unit.index') }}" class="btn btn-danger">Kembali</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        const defaultLat = "{{ $unit->latitude ?? '' }}";
        const defaultLng = "{{ $unit->longitude ?? '' }}";
        const routeProvince = "{{ route('geo.province', ':id') }}";
        const routeCities   = "{{ route('geo.cities', ':id') }}";
        const routeCity     = "{{ route('geo.city', ':id') }}";
    </script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.fullscreen@1.6.0/Control.FullScreen.js"></script>
    <script>
        let map, marker = null, provinceLayer = null, cityLayer = null;

        document.addEventListener("DOMContentLoaded", function () {
            map = L.map('map', {
                minZoom: 5, maxZoom: 18,
                fullscreenControl: true,
            }).setView([-2.5, 118], 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            if (defaultLat && defaultLng) {
                const lat = parseFloat(defaultLat), lng = parseFloat(defaultLng);
                marker = L.marker([lat, lng]).addTo(map);
                map.setView([lat, lng], 13);
            }

            map.on('click', function (e) {
                document.getElementById('latitude').value  = e.latlng.lat;
                document.getElementById('longitude').value = e.latlng.lng;
                if (marker) map.removeLayer(marker);
                marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
            });

            document.getElementById('province').addEventListener('change', function () {
                const id = this.value;
                if (!id) return;
                if (provinceLayer) map.removeLayer(provinceLayer);
                if (cityLayer) map.removeLayer(cityLayer);

                fetch(routeProvince.replace(':id', id))
                    .then(r => r.json()).then(res => {
                        const geo = typeof res.geometry === 'string' ? JSON.parse(res.geometry) : res.geometry;
                        provinceLayer = L.geoJSON(geo).addTo(map);
                        map.fitBounds(provinceLayer.getBounds());
                        setTimeout(() => map.setZoom(map.getZoom() + 2), 300);
                    });

                fetch(routeCities.replace(':id', id))
                    .then(r => r.json()).then(data => {
                        const city = document.getElementById('city');
                        city.innerHTML = '<option value="">Pilih Kota</option>';
                        data.forEach(item => {
                            const opt = document.createElement('option');
                            opt.value = item.id; opt.text = item.name;
                            city.appendChild(opt);
                        });
                    });
            });

            document.getElementById('city').addEventListener('change', function () {
                const id = this.value;
                if (!id) return;
                if (cityLayer) map.removeLayer(cityLayer);

                fetch(routeCity.replace(':id', id))
                    .then(r => r.json()).then(res => {
                        const geo = typeof res.geometry === 'string' ? JSON.parse(res.geometry) : res.geometry;
                        cityLayer = L.geoJSON(geo, { style: { color: 'red', weight: 2, fillOpacity: 0.2 } }).addTo(map);
                        map.fitBounds(cityLayer.getBounds());
                        setTimeout(() => map.setZoom(map.getZoom() + 2), 300);
                    });
            });
        });
    </script>
@endsection
