@extends('layout.app')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: 520px;
            width: 100%;
            border-radius: 12px;
        }
        .stat-card {
            border-left: 4px solid;
            transition: transform .15s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .legend {
            background: #fff;
            padding: 12px 14px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,.15);
            font-size: 13px;
            line-height: 26px;
        }
    </style>
@stop

@section('content')

    {{-- HEADER --}}
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Dashboard Fasilitas Umum</h4>
            <p class="text-muted small mb-0">Sebaran dan rekap fasilitas umum</p>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Fasilitas Umum</li>
            </ol>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-3 mb-3" id="statRow"></div>

    <div class="row g-3">

        {{-- FILTER (hanya tampil jika bukan type user) --}}
        @if(auth()->user()->type !== 'user')
        <div class="col-12">
            <div class="card shadow-sm rounded-3">
                <div class="card-body py-3">
                    <form method="GET" class="d-flex align-items-end gap-3 flex-wrap">
                        <div>
                            <label class="form-label fw-semibold mb-1 small">Filter Unit</label>
                            <select name="unit_id" id="selectUnit" class="form-select form-select-sm" style="min-width:220px;">
                                <option value="">Semua Unit</option>
                                @foreach ($all_units as $unit)
                                    <option value="{{ $unit->id }}" {{ $unitId == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                            <i data-feather="search" style="width:14px;height:14px;"></i> Tampilkan
                        </button>
                        @if($unitId)
                            <a href="{{ route('fasum.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        @endif

        {{-- MAP --}}
        <div class="col-12">
            <div class="card shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                    <i data-feather="map-pin" class="text-primary" style="width:18px;height:18px;"></i>
                    <h6 class="mb-0 fw-semibold">Peta Sebaran Fasilitas Umum</h6>
                </div>
                <div class="card-body p-3">
                    <div id="map"></div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const fasum       = @json($fasum);
        const selectedUnit = @json($unitId);
        const fasumTypes  = @json($fasumTypes);

        // ======================================
        // STAT CARDS
        // ======================================
        (function buildStatCards() {
            const totalPerType = {};
            fasumTypes.forEach(t => {
                totalPerType[t.name] = { total: 0, color: t.color_code ?? '#6c757d' };
            });
            fasum.forEach(item => {
                const n = item.type?.name;
                if (n && totalPerType[n]) totalPerType[n].total++;
            });

            const row = document.getElementById('statRow');

            // Total card
            const totalCol = document.createElement('div');
            totalCol.className = 'col-6 col-md-3 col-xl-2';
            totalCol.innerHTML = `
                <div class="card shadow-sm rounded-3 stat-card h-100" style="border-left-color:#4361ee;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="stat-icon" style="background:#eef0fd;">
                            <i data-feather="layers" style="width:22px;height:22px;color:#4361ee;"></i>
                        </div>
                        <div>
                            <div class="fs-4 fw-bold">${fasum.length}</div>
                            <div class="text-muted small">Total Fasum</div>
                        </div>
                    </div>
                </div>`;
            row.appendChild(totalCol);

            // Per-type cards
            Object.keys(totalPerType).forEach(typeName => {
                const item = totalPerType[typeName];
                const col  = document.createElement('div');
                col.className = 'col-6 col-md-3 col-xl-2';
                col.innerHTML = `
                    <div class="card shadow-sm rounded-3 stat-card h-100" style="border-left-color:${item.color};">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="stat-icon" style="background:${item.color}22;">
                                <i data-feather="map-pin" style="width:22px;height:22px;color:${item.color};"></i>
                            </div>
                            <div>
                                <div class="fs-4 fw-bold">${item.total}</div>
                                <div class="text-muted" style="font-size:.75rem;">${typeName}</div>
                            </div>
                        </div>
                    </div>`;
                row.appendChild(col);
            });

            // Trigger feather icons pada elemen yang baru dibuat
            feather.replace();
        })();

        // ======================================
        // MAP
        // ======================================
        const map = L.map('map', { minZoom: 5 });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        function setDefaultView() { map.setView([-2.5, 118], 5); }
        setDefaultView();

        // RENDER MARKERS
        const bounds = [];
        fasum.forEach(item => {
            const lat = parseFloat(item.latitude);
            const lng = parseFloat(item.longitude);
            if (isNaN(lat) || isNaN(lng)) return;

            const color  = item.type?.color_code ?? '#6c757d';
            const marker = L.circleMarker([lat, lng], {
                radius: 8, color: '#fff', weight: 2,
                fillColor: color, fillOpacity: 1
            }).addTo(map);

            marker.bindPopup(`
                <div style="min-width:220px;font-size:13px;">
                    <div style="font-weight:700;font-size:14px;margin-bottom:8px;border-bottom:1px solid #eee;padding-bottom:6px;">
                        ${item.name ?? '-'}
                    </div>
                    <div style="display:flex;gap:6px;align-items:center;margin-bottom:4px;">
                        <span style="width:10px;height:10px;border-radius:50%;background:${color};display:inline-block;flex-shrink:0;"></span>
                        <span>${item.type?.name ?? '-'}</span>
                    </div>
                    <div style="color:#666;margin-bottom:2px;">&#x1F3E2; ${item.unit?.name ?? '-'}</div>
                    <div style="color:#666;">&#x1F4CD; ${item.address ?? '-'}</div>
                </div>
            `);

            marker.on('mouseover', function () { this.openPopup(); this.setRadius(12); });
            marker.on('mouseout',  function () { this.closePopup(); this.setRadius(8); });

            bounds.push([lat, lng]);
        });

        if (selectedUnit && bounds.length > 0) {
            map.fitBounds(bounds, { padding: [40, 40] });
        } else {
            setDefaultView();
        }

        // LEGEND
        const legend = L.control({ position: 'bottomright' });
        legend.onAdd = function () {
            const div    = L.DomUtil.create('div', 'legend');
            const counts = {};
            fasumTypes.forEach(t => { counts[t.name] = { total: 0, color: t.color_code ?? '#6c757d' }; });
            fasum.forEach(item => {
                const n = item.type?.name;
                if (n && counts[n]) counts[n].total++;
            });

            const items = Object.keys(counts).map(n => `
                <div style="display:flex;align-items:center;gap:8px;">
                    <span style="width:12px;height:12px;border-radius:50%;background:${counts[n].color};flex-shrink:0;display:inline-block;"></span>
                    <span>${n} <b>(${counts[n].total})</b></span>
                </div>`).join('');

            div.innerHTML = `
                <div style="font-weight:700;margin-bottom:8px;padding-bottom:6px;border-bottom:1px solid #eee;">
                    Total: <span style="color:#4361ee;">${fasum.length}</span> Fasum
                </div>
                ${items}`;
            return div;
        };
        legend.addTo(map);

        // Feather icons untuk elemen statis
        feather.replace();

        // Select2
        $('#selectUnit').select2({
            placeholder: 'Semua Unit',
            allowClear: true,
            width: '220px'
        });
    </script>
@endsection
