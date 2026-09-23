<div class="topbar-custom">
    <div class="container-xxxl px-2">
        <div class="d-flex justify-content-between">
            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                <li>
                    <button class="button-toggle-menu nav-link ps-0">
                        <i data-feather="menu" class="noti-icon"></i>
                    </button>
                </li>
            </ul>

            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">

                <li class="d-none d-sm-flex">
                    <button type="button" class="btn nav-link" data-toggle="fullscreen">
                        <i data-feather="maximize" class="align-middle fullscreen noti-icon"></i>
                    </button>
                </li>

                @auth
                    @php
                        $unreadNotifications = auth()->user()->unreadNotifications()->latest()->take(5)->get();
                        $unreadCount = auth()->user()->unreadNotifications()->count();
                    @endphp

                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle nav-user me-0 position-relative" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i data-feather="bell" class="noti-icon"></i>
                            @if($unreadCount > 0)
                                <span class="badge bg-danger rounded-pill" style="position:absolute; top:0; right:0; font-size:10px;">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg" style="min-width:320px;">
                            <div class="dropdown-item noti-title px-3 py-2 border-bottom">
                                <h6 class="m-0">
                                    Notifikasi
                                    @if($unreadCount > 0)
                                        <span class="badge bg-danger">{{ $unreadCount }} baru</span>
                                    @endif
                                </h6>
                            </div>

                            @forelse($unreadNotifications as $notification)
                                <a href="{{ $notification->data['url'] ?? route('user.notifications.index') }}" class="dropdown-item notify-item">
                                    <p class="notify-details mb-0" style="white-space:normal;">
                                        <strong>{{ $notification->data['title'] ?? 'Notifikasi' }}</strong><br>
                                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($notification->data['message'] ?? '', 80) }}</small>
                                    </p>
                                </a>
                            @empty
                                <div class="dropdown-item text-muted text-center py-3">
                                    Tidak ada notifikasi baru.
                                </div>
                            @endforelse

                            <a href="{{ route('user.notifications.index') }}" class="dropdown-item text-center text-primary border-top">
                                Lihat Semua Notifikasi
                            </a>
                        </div>
                    </li>
                @endauth

                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="pro-user-name ms-1">
                            {{Auth::user()->name ?? ''}} <i class="mdi mdi-chevron-down"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown ">

                        <!-- item-->
                        <a href="{{route('profile.edit')}}" class="dropdown-item notify-item">
                            <i class="mdi mdi-lock-outline fs-16 align-middle"></i>
                            <span>Change Password</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <form
                            id="form-logout"
                            action="{{ route('logout') }}"
                            method="POST"
                        >
                            @csrf

                            <button
                                type="button"
                                class="dropdown-item notify-item"
                                onclick="confirmLogout('form-logout')"
                            >
                                <i class="mdi mdi-location-exit fs-16 align-middle"></i>
                                <span>Logout</span>
                            </button>
                        </form>


                    </div>
                </li>

            </ul>
        </div>

    </div>
   
</div>