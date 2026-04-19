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
            box-shadow: 0 0 8px rgba(0,0,0,.15);
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
                                    <option
                                        value="{{ $unit->id }}"
                                        {{ $unitId == $unit->id ? 'selected' : '' }}
                                    >
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >
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
        // INIT MAP
        // ======================================
        var map = L.map('map', {
            minZoom: 5
        }).setView([-2.5, 118], 5);

        L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            {
                attribution: '&copy; OpenStreetMap'
            }
        ).addTo(map);


        // ======================================
        // DATA FROM LARAVEL
        // ======================================
        let fasum = @json($fasum);


        // ======================================
        // COLOR BY TYPE
        // ======================================
        function getColor(type) {
            switch (type) {
                case 'Damkar':
                    return '#dc3545'; // merah

                case 'Rumah Sakit':
                    return '#28a745'; // hijau

                case 'Kantor Polisi':
                    return '#0d6efd'; // biru

                default:
                    return '#6c757d'; // abu
            }
        }


        // ======================================
        // HITUNG TOTAL PER TYPE
        // ======================================
        let totalDamkar = 0;
        let totalRumahSakit = 0;
        let totalPolisi = 0;
        let totalLainnya = 0;

        fasum.forEach(item => {
            switch (item.type) {
                case 'Damkar':
                    totalDamkar++;
                    break;

                case 'Rumah Sakit':
                    totalRumahSakit++;
                    break;

                case 'Kantor Polisi':
                    totalPolisi++;
                    break;

                default:
                    totalLainnya++;
                    break;
            }
        });

        let totalFasum =
            totalDamkar +
            totalRumahSakit +
            totalPolisi +
            totalLainnya;


        // ======================================
        // RENDER MARKER
        // ======================================
        let bounds = [];

        fasum.forEach(item => {

            let lat = parseFloat(item.latitude);
            let lng = parseFloat(item.longitude);

            if (isNaN(lat) || isNaN(lng)) return;

            let color = getColor(item.type);

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
                    ${item.type ?? '-'}<br><br>

                    <b>Unit:</b><br>
                    ${item.unit?.name ?? '-'}<br><br>

                    <b>Alamat:</b><br>
                    ${item.address ?? '-'}
                </div>
            `;

            marker.bindPopup(popupContent);

            // FIX hover
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
        // AUTO FIT MARKERS
        // ======================================
        if (bounds.length > 0) {
            map.fitBounds(bounds);
        }


        // ======================================
        // LEGEND + TOTAL
        // ======================================
        let legend = L.control({
            position: 'bottomright'
        });

        legend.onAdd = function () {
            let div = L.DomUtil.create('div', 'legend');

            div.innerHTML = `
                <div style="
                    background: #fff;
                    padding: 14px;
                    border-radius: 12px;
                    line-height: 28px;
                    min-width: 240px;
                ">

                    <div style="
                        font-weight: 700;
                        margin-bottom: 10px;
                        border-bottom: 1px solid #eee;
                        padding-bottom: 8px;
                    ">
                        Total Fasum: ${totalFasum}
                    </div>

                    <div>
                        <span style="
                            display:inline-block;
                            width:14px;
                            height:14px;
                            border-radius:50%;
                            background:#dc3545;
                            margin-right:8px;
                        "></span>
                        Damkar (${totalDamkar})
                    </div>

                    <div>
                        <span style="
                            display:inline-block;
                            width:14px;
                            height:14px;
                            border-radius:50%;
                            background:#28a745;
                            margin-right:8px;
                        "></span>
                        Rumah Sakit (${totalRumahSakit})
                    </div>

                    <div>
                        <span style="
                            display:inline-block;
                            width:14px;
                            height:14px;
                            border-radius:50%;
                            background:#0d6efd;
                            margin-right:8px;
                        "></span>
                        Kantor Polisi (${totalPolisi})
                    </div>
                </div>
            `;

            return div;
        };

        legend.addTo(map);

    </script>

@endsection