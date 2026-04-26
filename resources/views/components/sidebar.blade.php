<div class="app-sidebar-menu">

    <style>
        .vendor-fixed {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 260px;
            background: #1e293b;
            z-index: 999;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .vendor-header {
            padding: 10px 15px;
            font-size: 12px;
            color: #94a3b8;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .vendor-list {
            max-height: 200px;
            overflow-y: auto;
        }

        .vendor-item {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            transition: 0.2s;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .vendor-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .vendor-item.active {
            background: #3b82f6;
        }

        .vendor-name {
            font-size: 13px;
            color: #fff;
            font-weight: 600;
        }

        .vendor-contract {
            font-size: 11px;
            color: #cbd5f5;
        }

        /* supaya sidebar utama tidak ketutup */
        [data-simplebar] {
            padding-bottom: 220px;
        }
    </style>

    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                <a href="{{ route('dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('logo.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('logo.png') }}" alt="" height="45">
                    </span>
                </a>
                <a href="{{ route('dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('logo.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('logo.png') }}" alt="" height="45">
                    </span>
                </a>
            </div>

            <ul id="side-menu">
                <li>
                    <a href="{{ route('dashboard') }}" class="tp-link">
                        <i data-feather="home"></i>
                        <span> Home </span>
                    </a>
                </li>
                @if (Auth::user()->type == 'user')
                    <li>
                        <a href="{{ route('user.monthly-audit.index') }}" class="tp-link">
                            <i data-feather="user"></i>
                            <span> Laporan Bulanan </span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->type == 'admin')
                    <li>
                        <a href="{{ route('admin.monthly-audit.index') }}" class="tp-link">
                            <i data-feather="user"></i>
                            <span> Laporan Bulanan </span>
                        </a>
                    </li>
                @endif
                @can('view.assesment.bujp.admin')
                    <li>
                        <a href="{{ route('admin.assesment.index') }}" class="tp-link">
                            <i data-feather="user"></i>
                            <span> Assesment BUJP </span>
                        </a>
                    </li>
                @endcan
                @can('view.fasum.dashboard')
                    <li>
                        <a href="{{ route('fasum.dashboard') }}" class="tp-link">
                            <i data-feather="circle"></i>
                            <span> Fasilitas Umum </span>
                        </a>
                    </li>
                @endcan
                @can('view.assesment.bujp')
                    <li>
                        <a href="{{ route('bujp.assesment.index') }}@if (request('unit')) ?unit={{ request('unit') }} @endif"
                            class="tp-link">
                            <i data-feather="user"></i>
                            <span> Assesment BUJP </span>
                        </a>
                    </li>
                @endcan
                @can('view.assesment.bujp.unit')
                    <li>
                        <a href="{{ route('user.assesment.index') }}" class="tp-link">
                            <i data-feather="user"></i>
                            <span> Assesment BUJP </span>
                        </a>
                    </li>
                @endcan
                @can('view.audit.smp.score.admin')
                    <li>
                        <a href="{{ route('admin.audit-smp-score.index') }}" class="tp-link">
                            <i data-feather="user"></i>
                            <span> Audit SMP </span>
                        </a>
                    </li>
                @endcan
                @can('view.audit.smp.score.auditor')
                    <li>
                        <a href="{{ route('auditor.audit-smp-score.index') }}" class="tp-link">
                            <i data-feather="user"></i>
                            <span> Audit SMP </span>
                        </a>
                    </li>
                @endcan
                @can('view.audit.smp.score.unit')
                    <li>
                        <a href="{{ route('user.audit-smp-score.index') }}" class="tp-link">
                            <i data-feather="user"></i>
                            <span> Audit SMP </span>
                        </a>
                    </li>
                @endcan
                @canany(['view.marturity.admin'])
                    <li>
                        <a href="#sidebarBulanan" data-bs-toggle="collapse">
                            <i data-feather="briefcase"></i>
                            <span> Sistem Keamanan </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarBulanan">

                            <ul class="nav-second-level">
                                <li>
                                    <a href="{{ route('admin.marturity.index') }}" class="tp-link">Maturity Level</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.keamanan.index') }}" class="tp-link">Keamanan KPI</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcanany
                @canany(['view.marturity.unit'])
                    <li>
                        <a href="#sidebarBulanan" data-bs-toggle="collapse">
                            <i data-feather="briefcase"></i>
                            <span> Sistem Keamanan </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarBulanan">

                            <ul class="nav-second-level">
                                @can('view.marturity.unit')
                                    <li>
                                        <a href="{{ route('user.marturity.index') }}" class="tp-link">Maturity Level</a>
                                    </li>
                                @endcan
                                <li>
                                    <a href="{{ route('user.keamanan.index') }}" class="tp-link">Keamanan KPI</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcanany
                @canany(['view.vulnerability', 'view.attribute', 'view.category.assesment', 'view.marturity.area',
                    'view.kpi.area', 'view.audit.smp.admin', 'view.attribute.unit', 'view.security.unit',
                    'view.workersum.unit', 'view.security.program.unit', 'view.fasum.user', 'view.fasumtype.admin'])
                    <li>
                        <a href="#sidebarMasterData" data-bs-toggle="collapse">
                            <i data-feather="database"></i>
                            <span> Master Data </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarMasterData">
                            <ul class="nav-second-level">
                                @can('view.vulnerability')
                                    <li>
                                        <a href="{{ route('admin.vulnerability.index') }}" class="tp-link">Kerawanan</a>
                                    </li>
                                @endcan
                                @can('view.attribute')
                                    <li>
                                        <a href="{{ route('admin.attribute.index') }}" class="tp-link">Attribute</a>
                                    </li>
                                @endcan
                                @can('view.category.assesment')
                                    <li>
                                        <a href="{{ route('admin.category-assesment.index') }}" class="tp-link">Assesment</a>
                                    </li>
                                @endcan
                                @can('view.marturity.area')
                                    <li>
                                        <a href="{{ route('admin.marturity-area.index') }}" class="tp-link">Marturity</a>
                                    </li>
                                @endcan
                                @can('view.kpi.area')
                                    <li>
                                        <a href="{{ route('admin.kpi-area.index') }}" class="tp-link">KPI</a>
                                    </li>
                                @endcan
                                @can('view.audit.smp.admin')
                                    <li>
                                        <a href="{{ route('admin.audit-smp.index') }}" class="tp-link">Audit SMP</a>
                                    </li>
                                @endcan
                                @can('view.unit.admin')
                                    <li>
                                        <a href="{{ route('admin.unit.index') }}" class="tp-link">Unit</a>
                                    </li>
                                @endcan
                                @can('view.securepedia.admin')
                                    <li>
                                        <a href="{{ route('admin.securepedia.index') }}" class="tp-link">Securepedia</a>
                                    </li>
                                @endcan
                                @can('view.attribute.unit')
                                    <li>
                                        <a href="{{ route('user.attribute.index') }}" class="tp-link">Attribute</a>
                                    </li>
                                @endcan
                                @can('view.security.unit')
                                    <li>
                                        <a href="{{ route('user.security.index') }}" class="tp-link">Satuan Pengaman</a>
                                    </li>
                                @endcan
                                @can('view.workersum.unit')
                                    <li>
                                        <a href="{{ route('user.worker-sum.index') }}" class="tp-link">Jumlah Pekerja</a>
                                    </li>
                                @endcan
                                @can('view.security.program.unit')
                                    <li>
                                        <a href="{{ route('user.security-program.index') }}" class="tp-link">Program
                                            Keamanan</a>
                                    </li>
                                @endcan
                                @can('view.fasum.user')
                                    <li>
                                        <a href="{{ route('user.fasum.index') }}" class="tp-link">Fasilitas Umum</a>
                                    </li>
                                @endcan
                                @can('view.fasumtype.admin')
                                    <li>
                                        <a href="{{ route('admin.fasum-type.index') }}" class="tp-link">Tipe Fasilitas
                                            Umum</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
                @can('view.user.vendor')
                    <li>
                        <a href="{{ route('user.vendor.index') }}" class="tp-link">
                            <i data-feather="user"></i>
                            <span> Vendor / BUJP </span>
                        </a>
                    </li>
                @endcan
                @canany(['view.user', 'view.role', 'view.permission'])
                    <li>
                        <a href="#sidebarSetting" data-bs-toggle="collapse">
                            <i data-feather="settings"></i>
                            <span> Setting </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarSetting">
                            <ul class="nav-second-level">
                                @can('view.user')
                                    <li>
                                        <a href="{{ route('users.index') }}" class="tp-link">User Management</a>
                                    </li>
                                @endcan
                                @can('view.role')
                                    <li>
                                        <a href="{{ route('roles.index') }}" class="tp-link">Role Management</a>
                                    </li>
                                @endcan
                                @can('view.permission')
                                    <li>
                                        <a href="{{ route('permissions.index') }}" class="tp-link">Permission Management</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcanany
            </ul>
        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>


        @if (isset($units) && count($units) > 0)

            @php
                $selectedUnit = null;

                if (request('unit')) {
                    try {
                        $selectedUnit = \Illuminate\Support\Facades\Crypt::decryptString(request('unit'));
                    } catch (\Exception $e) {
                        $selectedUnit = null;
                    }
                }
            @endphp

            <div class="vendor-fixed">

                <div class="vendor-header">
                    <span style="font-size: 14px">Pilih Unit</span>
                </div>

                <div class="vendor-list">

                    @foreach ($units as $unit)
                        <a href="{{ route('bujp.assesment.index', ['unit' => $unit['vendor_id_encrypted']]) }}"
                            class="vendor-item {{ $selectedUnit == $unit['vendor_id'] ? 'active' : '' }}">

                            <div class="vendor-name">
                                {{ $unit['name'] }}
                            </div>

                            <div class="vendor-contract">
                                {{ $unit['contract_number'] }}
                            </div>

                        </a>
                    @endforeach

                </div>

            </div>

        @endif
    </div>
</div>
