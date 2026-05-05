@extends('layout.app')
@section('styles')
@stop
@section('content')


    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Email Setting</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Email Setting</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.email-setting.update') }}" class="my-4" method="POST" id="form-unit"
                        onsubmit="confirmSave('form-unit', 'Data pengaturan email akan disimpan')">
                        @csrf
                        @method('PATCH')
                        <!-- Formulir Pendaftaran -->
                        <div class="col-xl-12">
                            <div class="">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="provider" class="form-label">Provider</label>
                                        <input class="form-control" type="text" id="provider" required=""
                                        placeholder="Masukan Provider" name="provider" value="{{ old('provider', $emailSetting['provider'] ?? '') }}">
                                        @if ($errors->has('provider'))
                                        <div class="error text-danger">{{ $errors->first('provider') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="host" class="form-label">Host</label>
                                        <input class="form-control" type="text" id="host" required=""
                                        placeholder="Masukan Host" name="host" value="{{ old('host', $emailSetting['host'] ?? '') }}">
                                        @if ($errors->has('host'))
                                        <div class="error text-danger">{{ $errors->first('host') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input class="form-control" type="text" id="username" required=""
                                        placeholder="Masukan Username" name="username" value="{{ old('username', $emailSetting['username'] ?? '') }}">
                                        @if ($errors->has('username'))
                                        <div class="error text-danger">{{ $errors->first('username') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input class="form-control" type="password" id="password" required=""
                                        placeholder="Masukan Password" name="password" value="{{ old('password', $emailSetting['password'] ?? '') }}">     
                                        @if ($errors->has('password'))
                                        <div class="error text-danger">{{ $errors->first('password') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="port" class="form-label">Port</label>
                                        <input class="form-control" type="number" id="port" required=""
                                        placeholder="Masukan Port" name="port" value="{{ old('port', $emailSetting['port'] ?? '') }}">     
                                        @if ($errors->has('port'))
                                        <div class="error text-danger">{{ $errors->first('port') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="from" class="form-label">Mail From</label>
                                        <input class="form-control" type="text" id="from" required=""
                                        placeholder="Masukan Mail From" name="from" value="{{ old('from', $emailSetting['from'] ?? '') }}">     
                                        @if ($errors->has('from'))
                                        <div class="error text-danger">{{ $errors->first('from') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="alias" class="form-label">Mail Alias</label>
                                        <input class="form-control" type="text" id="alias" required=""
                                        placeholder="Masukan Mail Alias" name="alias" value="{{ old('alias', $emailSetting['alias'] ?? '') }}">     
                                        @if ($errors->has('alias'))
                                        <div class="error text-danger">{{ $errors->first('alias') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="timeout" class="form-label">Timeout</label>
                                        <input class="form-control" type="number" id="timeout" required=""
                                        placeholder="Masukkan Timeout dalam detik" name="timeout" value="{{ old('timeout', $emailSetting['timeout'] ?? '') }}">     
                                        @if ($errors->has('timeout'))
                                        <div class="error text-danger">{{ $errors->first('timeout') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="security" class="form-label">Security</label>
                                        <input class="form-control" type="text" id="security" required=""
                                        placeholder="Masukkan security (true / false)" name="security" value="{{ old('security', $emailSetting['security'] ?? '') }}">     
                                        @if ($errors->has('security'))
                                        <div class="error text-danger">{{ $errors->first('security') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="email_test" class="form-label">Email Test</label>
                                        <input class="form-control" type="text" id="email_test" required=""
                                        placeholder="Masukkan email test" name="email_test" value="{{ old('email_test', $emailSetting['email_test'] ?? '') }}">     
                                        @if ($errors->has('email_test'))
                                        <div class="error text-danger">{{ $errors->first('email_test') }}</div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <div class="d-flex gap-3 justify-content-end">

                                        <button type="submit" class="btn btn-success">
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div> <!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div> <!-- end row -->
@endsection
@section('scripts')
    
@endsection
