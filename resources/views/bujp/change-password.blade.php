@extends('bujp.layout.app')
@section('styles')
    <style>
        .accordion-button::after {
            filter: invert(100%);
        }
    </style>
@stop
@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Ubah Password</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('bujp.home.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Ubah Password</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                {{ $error }} <br>
                            @endforeach
                        </div>
                    @endif
                    <form action="{{ route('bujp.updatePassword') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="form-group mb-3">
                                    <label>Password Lama</label>
                                    <div class="input-group">
                                        <input type="password" id="old_password" class="form-control" name="old_password">

                                        <button class="btn btn-outline-primary" type="button"
                                            onclick="togglePassword('old_password', this)">
                                            <i data-feather="eye" class="eye-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" id="new_password" class="form-control" name="new_password">

                                        <button class="btn btn-outline-primary" type="button"
                                            onclick="togglePassword('new_password', this)">
                                            <i data-feather="eye" class="eye-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label>Ulangi Password Baru</label>
                                    <div class="input-group">
                                        <input type="password" id="repeat_password" class="form-control"
                                            name="repeat_password">

                                        <button class="btn btn-outline-primary" type="button"
                                            onclick="togglePassword('repeat_password', this)">
                                            <i data-feather="eye" class="eye-icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center pb-3">
                            <a href="{{ route('admin.home.index') }}" class="btn btn-danger">Back</a>

                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>

                </div> <!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div> <!-- end row -->
@endsection
@section('scripts')
    <script>
        function togglePassword(id, el) {
            const input = document.getElementById(id);
            const icon = el.querySelector('.eye-icon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-feather', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-feather', 'eye');
            }
            feather.replace();
        }
    </script>
@endsection
