{{-- resources/views/dashboard/dashboard.blade.php --}}

@extends('layout.app')

@section('styles')

    {{-- LEAFLET CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        #map {
            height: 600px;
            width: 100%;
            border-radius: 12px;
        }

        .legend {
            background: #fff;
            padding: 12px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0, 0, 0, .15);
            line-height: 22px;
            font-size: 14px;
        }

        .legend i {
            width: 18px;
            height: 18px;
            float: left;
            margin-right: 8px;
            opacity: .9;
            border-radius: 50%;
        }
    </style>
@stop


@section('content')

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">
                Dashboard Fasum
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            {{-- FILTER --}}
            <div class="card">
                <div class="card-body">

                    <form method="GET">
                        <label class="form-label fw-semibold">
                            Filter Unit
                        </label>

                        <div class="row align-items-end">

                            <div class="col-md-4">
                                <select name="unit_id" class="form-select">
                                    <option value="">
                                        Semua Unit
                                    </option>

                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" {{ $unitId == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    Cari
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>

            {{-- MAP --}}
            <div class="card">
                <div class="card-body">
                    <div id="map"></div>
                </div>
            </div>

        </div>
    </div>

@endsection


@section('scripts')

    {{-- LEAFLET JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // ======================================
        // DATA FROM LARAVEL
        // ======================================
        let fasum = @json($fasum);
        let selectedUnit = @json($unitId);
        let fasumTypes = @json($fasumTypes);


        // ======================================
        // INIT MAP
        // ======================================
        var map = L.map('map', {
            minZoom: 5
        });

        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                attribution: '&copy; OpenStreetMap'
            }
        ).addTo(map);


        // ======================================
        // DEFAULT VIEW INDONESIA
        // ======================================
        function setDefaultIndonesiaView() {
            map.setView([-2.5, 118], 5);
        }

        // default pertama kali
        setDefaultIndonesiaView();


        // ======================================
        // GET COLOR
        // ======================================
        function getColor(item) {
            return item.type?.color_code ?? '#6c757d';
        }


        // ======================================
        // LEGEND DINAMIS DARI MASTER TYPE
        // ======================================
        let totalPerType = {};
        let totalFasum = fasum.length;

        // ambil semua master type dulu
        fasumTypes.forEach(type => {
            totalPerType[type.name] = {
                total: 0,
                color: type.color_code ?? '#6c757d'
            };
        });

        // hitung total dari data fasum
        fasum.forEach(item => {
            let typeName = item.type?.name ?? null;

            if (typeName && totalPerType[typeName]) {
                totalPerType[typeName].total++;
            }
        });


        // ======================================
        // RENDER MARKER
        // ======================================
        let bounds = [];

        fasum.forEach(item => {

            let lat = parseFloat(item.latitude);
            let lng = parseFloat(item.longitude);

            // skip jika koordinat invalid
            if (isNaN(lat) || isNaN(lng)) return;

            let color = getColor(item);

            let marker = L.circleMarker([lat, lng], {
                radius: 8,
                color: '#ffffff',
                weight: 2,
                fillColor: color,
                fillOpacity: 1
            }).addTo(map);

            let popupContent = `
                <div style="min-width:240px;">
                    <b>${item.name ?? '-'}</b><br><br>

                    <b>Tipe:</b><br>
                    ${item.type?.name ?? '-'}<br><br>

                    <b>Unit:</b><br>
                    ${item.unit?.name ?? '-'}<br><br>

                    <b>Alamat:</b><br>
                    ${item.address ?? '-'}
                </div>
            `;

            marker.bindPopup(popupContent);

            marker.on('mouseover', function () {
                this.openPopup();
                this.setRadius(12);
            });

            marker.on('mouseout', function () {
                this.closePopup();
                this.setRadius(8);
            });

            bounds.push([lat, lng]);
        });


        // ======================================
        // ZOOM CONDITION
        // ======================================
        /*
            CASE:
            1. Tidak ada filter unit
               -> tampil full Indonesia

            2. Ada filter unit + ada marker
               -> zoom ke marker unit tsb

            3. Ada filter unit + tidak ada marker
               -> tetap tampil full Indonesia
        */

        if (selectedUnit) {
            if (bounds.length > 0) {
                map.fitBounds(bounds);
            } else {
                setDefaultIndonesiaView();
            }
        } else {
            setDefaultIndonesiaView();
        }


        // ======================================
        // LEGEND
        // ======================================
        let legend = L.control({
            position: 'bottomright'
        });

        legend.onAdd = function () {
            let div = L.DomUtil.create('div', 'legend');

            let legendItems = '';

            Object.keys(totalPerType).forEach(typeName => {
                let item = totalPerType[typeName];

                legendItems += `
                    <div>
                        <span style="
                            display:inline-block;
                            width:14px;
                            height:14px;
                            border-radius:50%;
                            background:${item.color};
                            margin-right:8px;
                        "></span>
                        ${typeName} (${item.total})
                    </div>
                `;
            });

            div.innerHTML = `
                <div style="
                    background: #fff;
                    padding: 14px;
                    border-radius: 12px;
                    line-height: 28px;
                    min-width: 260px;
                ">

                    <div style="
                        font-weight: 700;
                        margin-bottom: 10px;
                        border-bottom: 1px solid #eee;
                        padding-bottom: 8px;
                    ">
                        Total Fasum: ${totalFasum}
                    </div>

                    ${legendItems}

                </div>
            `;

            return div;
        };

        legend.addTo(map);

    </script>

@endsection
