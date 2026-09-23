@extends('layout.app')

@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Notifikasi</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Notifikasi</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center p-3">
                <span class="text-muted">
                    {{ $notifications->total() }} notifikasi,
                    {{ auth()->user()->unreadNotifications->count() }} belum dibaca
                </span>

                @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('user.notifications.read-all') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            Tandai Semua Dibaca
                        </button>
                    </form>
                @endif
            </div>

            <div class="card-body pt-0">

                @if($notifications->count() == 0)
                    <div class="text-center text-muted py-4">
                        Tidak ada notifikasi.
                    </div>
                @else
                    <div class="list-group">
                        @foreach($notifications as $notification)
                            <div class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            @if(!$notification->read_at)
                                                <span class="badge bg-danger">Baru</span>
                                            @endif
                                            <strong>{{ $notification->data['title'] ?? 'Notifikasi' }}</strong>
                                        </div>
                                        <div class="text-muted mt-1">
                                            {{ $notification->data['message'] ?? '-' }}
                                        </div>
                                        <div class="text-muted small mt-1">
                                            {{ $notification->created_at->format('d-m-Y H:i') }}
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column gap-2 text-nowrap">
                                        @if(!empty($notification->data['url']))
                                            <a href="{{ $notification->data['url'] }}" class="btn btn-primary btn-sm">
                                                Lihat
                                            </a>
                                        @endif

                                        @if(!$notification->read_at)
                                            <form action="{{ route('user.notifications.read', ['id' => $notification->id]) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                                    Tandai Dibaca
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        {{ $notifications->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection
