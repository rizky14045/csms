@extends('layout.app')

@section('styles')
    <style>
        #map {
            height: 500px;
            width: 100%;
            border-radius: 10px;
        }

        .legend {
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            line-height: 18px;
        }

        .legend i {
            width: 15px;
            height: 15px;
            float: left;
            margin-right: 8px;
            opacity: 0.9;
        }
    </style>
@stop

@section('content')

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Dashboard</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            {{-- FILTER --}}
            <div class="card">
                <div class="card-body">
                    <form method="GET">
                        <label class="form-label">Filter Data</label>

                        <div class="d-flex gap-3">

                            {{-- BULAN --}}
                            <div class="col-md-3">
                                <select name="month" class="form-select">
                                    <option value="">Pilih Bulan</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            {{-- TAHUN --}}
                            <div class="col-md-3">
                                <select name="year" class="form-select">
                                    <option value="">Pilih Tahun</option>
                                    @for ($y = 2020; $y <= date('Y'); $y++)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-primary">Cari</button>
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

    {{-- LEAFLET --}}
    <script src="{{ asset('assets/libs/leaflet/leaflet.js') }}"></script>

    <script>
        // ==============================
        // INIT MAP (FULL INDONESIA)
        // ==============================
        var map = L.map('map', {
            minZoom: 5
        }).setView([-2.5, 118], 5);

       L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

        @if($isCentral)

        // ==============================
        // DATA UNIT DARI LARAVEL (Admin/Pusat)
        // ==============================
        let mapUnits = @json($mapUnits);

        // ==============================
        // RENDER MARKER PER UNIT
        // ==============================
        mapUnits.forEach(unit => {

            let lat = parseFloat(unit.latitude);
            let lng = parseFloat(unit.longitude);

            // skip kalau invalid
            if (isNaN(lat) || isNaN(lng)) return;

            let marker = L.circleMarker([lat, lng], {
                radius: 8,
                color: '#fff',
                weight: 2,
                fillColor: '#0d6efd',
                fillOpacity: 1
            }).addTo(map);

            let kerawananBadge = unit.total_kerawanan > 0 ?
                `<span class="badge" style="background:#dc3545;">${unit.total_kerawanan}</span>` :
                `<span class="badge" style="background:#28a745;">0</span>`;

            let vendorName = unit.active_vendor_names ? unit.active_vendor_names : '-';

            let content = `
                <b>${unit.name}</b><br>
                BUJP / Vendor Aktif: ${vendorName}<br>
                Jumlah Karyawan: ${unit.total_karyawan}<br>
                Jumlah Satpam: ${unit.total_satpam}<br>
                Jumlah Kerawanan: ${kerawananBadge}
            `;

            marker.bindPopup(content);
        });

        // ==============================
        // LEGEND (KETERANGAN)
        // ==============================
        var legend = L.control({
            position: 'bottomright'
        });

        legend.onAdd = function() {
            var div = L.DomUtil.create('div', 'legend');

            div.innerHTML += '<i style="background:#28a745"></i> Tidak Ada Kerawanan<br>';
            div.innerHTML += '<i style="background:#dc3545"></i> Ada Kerawanan<br>';

            return div;
        };

        legend.addTo(map);

        @else

        // ==============================
        // DATA DARI LARAVEL
        // ==============================
        let users = @json($users);

        // ==============================
        // RENDER MARKER
        // ==============================
        users.forEach(user => {

            let lat = parseFloat(user.latitude);
            let lng = parseFloat(user.longitude);

            // skip kalau invalid
            if (isNaN(lat) || isNaN(lng)) return;

            // warna
            let color = user.status_report == 1 ? '#28a745' : '#dc3545';

            let marker = L.circleMarker([lat, lng], {
                radius: 8,
                color: '#fff',
                weight: 2,
                fillColor: color,
                fillOpacity: 1
            }).addTo(map);

            let content = user.status_report == 1 ?
                `<b>${user.name}</b><br>Total Satpam: ${user.satpam}` :
                `<b>${user.name}</b><br>Belum ada data`;

            marker.bindPopup(content);

            // hover effect
            marker.on('mouseover', function() {
                this.setStyle({
                    radius: 12
                });
                this.openPopup();
            });

            marker.on('mouseout', function() {
                this.setStyle({
                    radius: 8
                });
                this.closePopup();
            });

        });

        // ==============================
        // LEGEND (KETERANGAN)
        // ==============================
        var legend = L.control({
            position: 'bottomright'
        });

        legend.onAdd = function() {
            var div = L.DomUtil.create('div', 'legend');

            div.innerHTML += '<i style="background:#28a745"></i> Sudah Lapor<br>';
            div.innerHTML += '<i style="background:#dc3545"></i> Belum Lapor<br>';

            return div;
        };

        legend.addTo(map);

        @endif
    </script>

@endsection
