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

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-mail-settings-line fs-5 text-primary"></i>
                        <h6 class="mb-0 fw-semibold">Konfigurasi Email</h6>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.email-setting.update') }}" method="POST" id="form-unit"
                        onsubmit="confirmSave('form-unit', 'Data pengaturan email akan disimpan')">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="provider" class="form-label fw-semibold">Provider</label>
                            <input class="form-control" type="text" id="provider" required
                                placeholder="Masukan Provider" name="provider" value="{{ old('provider', $emailSetting['provider'] ?? '') }}">
                            @if ($errors->has('provider'))
                                <div class="text-danger small mt-1">{{ $errors->first('provider') }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="host" class="form-label fw-semibold">Host</label>
                            <input class="form-control" type="text" id="host" required
                                placeholder="Masukan Host" name="host" value="{{ old('host', $emailSetting['host'] ?? '') }}">
                            @if ($errors->has('host'))
                                <div class="text-danger small mt-1">{{ $errors->first('host') }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label fw-semibold">Username</label>
                            <input class="form-control" type="text" id="username"
                                placeholder="Kosongkan jika server tidak butuh autentikasi" name="username" value="{{ old('username', $emailSetting['username'] ?? '') }}">
                            <div class="form-text">Kosongkan bersama Password jika mail server mengizinkan relay tanpa autentikasi (mis. IP internal terpercaya).</div>
                            @if ($errors->has('username'))
                                <div class="text-danger small mt-1">{{ $errors->first('username') }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input class="form-control" type="password" id="password"
                                placeholder="Kosongkan jika server tidak butuh autentikasi" name="password" value="{{ old('password', $emailSetting['password'] ?? '') }}">
                            @if ($errors->has('password'))
                                <div class="text-danger small mt-1">{{ $errors->first('password') }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="port" class="form-label fw-semibold">Port</label>
                            <input class="form-control" type="number" id="port" required
                                placeholder="Masukan Port" name="port" value="{{ old('port', $emailSetting['port'] ?? '') }}">
                            @if ($errors->has('port'))
                                <div class="text-danger small mt-1">{{ $errors->first('port') }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="from" class="form-label fw-semibold">Mail From</label>
                            <input class="form-control" type="text" id="from" required
                                placeholder="Masukan Mail From" name="from" value="{{ old('from', $emailSetting['from'] ?? '') }}">
                            @if ($errors->has('from'))
                                <div class="text-danger small mt-1">{{ $errors->first('from') }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="alias" class="form-label fw-semibold">Mail Alias</label>
                            <input class="form-control" type="text" id="alias" required
                                placeholder="Masukan Mail Alias" name="alias" value="{{ old('alias', $emailSetting['alias'] ?? '') }}">
                            @if ($errors->has('alias'))
                                <div class="text-danger small mt-1">{{ $errors->first('alias') }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="timeout" class="form-label fw-semibold">Timeout</label>
                            <input class="form-control" type="number" id="timeout" required
                                placeholder="Masukkan Timeout dalam detik" name="timeout" value="{{ old('timeout', $emailSetting['timeout'] ?? '') }}">
                            @if ($errors->has('timeout'))
                                <div class="text-danger small mt-1">{{ $errors->first('timeout') }}</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="security" class="form-label fw-semibold">Security</label>
                            @php $securityValue = old('security', $emailSetting['security'] ?? ''); @endphp
                            <select class="form-control" id="security" required name="security">
                                <option value="" disabled {{ $securityValue === '' ? 'selected' : '' }}>Pilih tipe security</option>
                                <option value="tls" {{ $securityValue === 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ $securityValue === 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="none" {{ $securityValue === 'none' ? 'selected' : '' }}>None</option>
                            </select>
                            @if ($errors->has('security'))
                                <div class="text-danger small mt-1">{{ $errors->first('security') }}</div>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label for="email_test" class="form-label fw-semibold">Email Test</label>
                            <input class="form-control" type="email" id="email_test" required
                                placeholder="Masukkan email test" name="email_test" value="{{ old('email_test', $emailSetting['email_test'] ?? '') }}">
                            @if ($errors->has('email_test'))
                                <div class="text-danger small mt-1">{{ $errors->first('email_test') }}</div>
                            @endif
                        </div>

                        <div id="testEmailResult" class="mb-3"></div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" id="btnTestEmail" class="btn btn-outline-primary">
                                <i class="ri-send-plane-line me-1"></i>Kirim Email Test
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="ri-save-line me-1"></i>Simpan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        document.getElementById('btnTestEmail').addEventListener('click', function () {
            const btn = this;
            const resultBox = document.getElementById('testEmailResult');
            const form = document.getElementById('form-unit');

            const payload = {
                provider: form.provider.value,
                host: form.host.value,
                username: form.username.value,
                password: form.password.value,
                port: form.port.value,
                from: form.from.value,
                alias: form.alias.value,
                timeout: form.timeout.value,
                security: form.security.value,
                email_test: form.email_test.value,
            };

            btn.disabled = true;
            btn.innerHTML = '<i class="ri-loader-4-line me-1"></i>Mengirim...';
            resultBox.innerHTML = '';

            fetch(@json(route('admin.email-setting.test')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': form._token.value,
                },
                body: JSON.stringify(payload),
            })
                .then(response => response.json().then(data => ({ status: response.status, data })))
                .then(({ data }) => {
                    if (data.success) {
                        resultBox.innerHTML = `
                            <div class="alert alert-success mb-0">
                                <i class="ri-checkbox-circle-line me-1"></i>${data.message}
                            </div>`;
                    } else {
                        const errorDetail = typeof data.errors === 'string'
                            ? data.errors
                            : JSON.stringify(data.errors);
                        resultBox.innerHTML = `
                            <div class="alert alert-danger mb-0">
                                <div class="fw-semibold"><i class="ri-error-warning-line me-1"></i>${data.message}</div>
                                <div class="small mt-1" style="word-break:break-word;">${errorDetail ?? ''}</div>
                            </div>`;
                    }
                })
                .catch(err => {
                    resultBox.innerHTML = `
                        <div class="alert alert-danger mb-0">
                            <i class="ri-error-warning-line me-1"></i>Gagal menghubungi server: ${err.message}
                        </div>`;
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ri-send-plane-line me-1"></i>Kirim Email Test';
                });
        });
    </script>
@endsection
