<!-- Vendor -->
<script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>
<script src="{{asset('assets/libs/waypoints/lib/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('assets/libs/jquery.counterup/jquery.counterup.min.js')}}"></script>
<script src="{{asset('assets/libs/feather-icons/feather.min.js')}}"></script>

<!-- Apexcharts JS -->
<script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>

<!-- for basic area chart -->
<script src="https://apexcharts.com/samples/assets/stock-prices.js"></script>

<!-- Widgets Init Js -->
<script src="{{asset('assets/js/pages/analytics-dashboard.init.js')}}"></script>

<!-- App js-->
<script src="{{asset('assets/js/app.js')}}"></script>

<script src="{{ asset('assets/libs/leaflet/leaflet.js') }}"></script>
<script src="{{ asset('assets/libs/chartjs/chart.umd.min.js') }}"></script>

{{-- Sweet Alert FE --}}
<script src="{{ asset('assets/swal/sweetalert2.all.min.js') }}"></script>
<script>
    // ===============================
    // SweetAlert Global Helper
    // ===============================

    /**
     * Konfirmasi simpan data (create / update)
     * @param {string} formId
     * @param {string} message
     */
    function confirmSave(formId, message = 'Pastikan data sudah benar.') {
        event.preventDefault();

        Swal.fire({
            title: 'Simpan Data?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });

        return false; // extra safety
    }

    function confirmAction(formId, title, message, confirmText = 'Ya, Lanjutkan') {
        Swal.fire({
            title: title,
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: confirmText,
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });

        return false;
    }

    function postAction(url, fields) {
        const f = document.createElement('form');
        f.method = 'POST';
        f.action = url;
        let html = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        Object.entries(fields || {}).forEach(([k, v]) => {
            html += '<input type="hidden" name="' + k + '" value="' + v + '">';
        });
        f.innerHTML = html;
        document.body.appendChild(f);
        f.submit();
    }

    function confirmPostAction(url, title, message) {
        Swal.fire({
            title: title, text: message, icon: 'question',
            showCancelButton: true, confirmButtonText: 'Ya, Lanjutkan', cancelButtonText: 'Batal', reverseButtons: true
        }).then((r) => { if (r.isConfirmed) postAction(url); });
    }

    function exportAssesment(url) {
        Swal.fire({
            title: 'Export Excel Assesment',
            html:
                '<div class="text-start">' +
                '<label class="form-label mb-1">Penanda Tangan 1</label>' +
                '<input id="swal-signer1" class="swal2-input mt-0 mb-3" style="width:100%;margin:0 0 12px 0;" maxlength="100" placeholder="Nama penanda tangan 1">' +
                '<label class="form-label mb-1">Penanda Tangan 2</label>' +
                '<input id="swal-signer2" class="swal2-input mt-0" style="width:100%;margin:0;" maxlength="100" placeholder="Nama penanda tangan 2">' +
                '</div>',
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Export',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            preConfirm: () => {
                const s1 = document.getElementById('swal-signer1').value.trim();
                const s2 = document.getElementById('swal-signer2').value.trim();
                if (!s1 || !s2) {
                    Swal.showValidationMessage('Nama penanda tangan 1 dan 2 wajib diisi');
                    return false;
                }
                return { s1, s2 };
            }
        }).then((r) => {
            if (r.isConfirmed) {
                window.location = url + '?signer1=' + encodeURIComponent(r.value.s1) + '&signer2=' + encodeURIComponent(r.value.s2);
            }
        });
    }

    function confirmRowDelete(url) {
        Swal.fire({
            title: 'Hapus dari laporan?',
            text: 'Data akan dihapus dari laporan bulanan ini.',
            icon: 'warning',
            input: 'checkbox',
            inputValue: 0,
            inputPlaceholder: 'Hapus juga dari master data',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((r) => {
            if (r.isConfirmed) postAction(url, { _method: 'DELETE', delete_master: r.value ? 1 : 0 });
        });
    }

    function confirmDeleteOnly(url) {
        Swal.fire({
            title: 'Hapus dari laporan?',
            text: 'Data akan dihapus dari laporan bulanan ini.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((r) => {
            if (r.isConfirmed) postAction(url, { _method: 'DELETE' });
        });
    }

    function confirmSaveAjax(form, message, callback) {
        Swal.fire({
            title: 'Simpan Data?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) callback();
        });
    }


    /**
     * Konfirmasi hapus data
     * @param {string} formId
     * @param {string} message
     */
    function confirmDelete(formId, message = 'Data yang dihapus tidak dapat dikembalikan!') {
        Swal.fire({
            title: 'Hapus Data?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }

    /**
     * Notifikasi sukses
     * @param {string} message
     */
    function confirmLogout(formId, message = 'Anda akan keluar dari sistem.') {
        Swal.fire({
            title: 'Logout?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }

</script>
<script>
    // Fungsi untuk mengatur event listener pada semua elemen input picker
    function enablePickerOnFocus() {
        // Ambil semua elemen input dengan tipe date, month, dan datetime-local di halaman
        const pickerInputs = document.querySelectorAll('input[type="date"], input[type="month"], input[type="datetime-local"]');

        // Iterasi setiap elemen input dan tambahkan event listener
        pickerInputs.forEach(input => {
            input.addEventListener('focus', () => {
                // Buka picker saat area input diklik
                input.showPicker();
            });
        });
    }

    // Panggil fungsi saat halaman selesai di-load
    window.addEventListener('DOMContentLoaded', enablePickerOnFocus);
</script>

<script src="{{ asset('assets/libs/jquery/jquery-3.6.0.min.js') }}"></script>

<script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>

<script>
    $('#togglePassword').on('click', function() {

        const password = $('#password');
        const icon = $('#eyeIcon');

        if (password.attr('type') === 'password') {
            password.attr('type', 'text');
            icon.attr('data-feather', 'eye-off');
        } else {
            password.attr('type', 'password');
            icon.attr('data-feather', 'eye');
        }

        feather.replace();
    });

    $('#togglePasswordConfirmation').on('click', function() {

        const password = $('#password_confirmation');
        const icon = $('#eyeIconConfirmation');

        if (password.attr('type') === 'password') {
            password.attr('type', 'text');
            icon.attr('data-feather', 'eye-off');
        } else {
            password.attr('type', 'password');
            icon.attr('data-feather', 'eye');
        }

        feather.replace();
    });

    feather.replace();
</script>
<script>
    /**
     * Cek kekuatan password secara real-time.
     * @param {string} inputId      - ID elemen input password
     * @param {string} feedbackId   - ID elemen container feedback checklist
     * @param {string} [matchInputId]  - (opsional) ID input konfirmasi password
     * @param {string} [matchFeedbackId] - (opsional) ID elemen feedback kecocokan
     */
    function checkPasswordStrength(inputId, feedbackId, matchInputId = null, matchFeedbackId = null) {
        const input = document.getElementById(inputId);
        if (!input) return;

        const rules = [
            { id: 'rule-min-' + inputId,    label: 'Minimal 12 karakter',                  test: v => v.length >= 12 },
            { id: 'rule-upper-' + inputId,  label: 'Huruf besar dan kecil (A-Z, a-z)',      test: v => /[A-Z]/.test(v) && /[a-z]/.test(v) },
            { id: 'rule-number-' + inputId, label: 'Mengandung angka (0-9)',                test: v => /[0-9]/.test(v) },
            { id: 'rule-symbol-' + inputId, label: 'Mengandung simbol (!@#$%^&*...)',       test: v => /[^A-Za-z0-9]/.test(v) },
        ];

        const container = document.getElementById(feedbackId);
        if (!container) return;

        // Render checklist satu kali
        if (!container.dataset.rendered) {
            container.innerHTML = rules.map(r =>
                `<div id="${r.id}" class="d-flex align-items-center gap-1 small text-muted">
                    <i class="ri-checkbox-blank-circle-line"></i> ${r.label}
                </div>`
            ).join('');
            container.dataset.rendered = '1';
        }

        input.addEventListener('input', function () {
            const val = this.value;

            rules.forEach(r => {
                const el = document.getElementById(r.id);
                if (!el) return;
                const pass = r.test(val);
                el.className = 'd-flex align-items-center gap-1 small ' + (pass ? 'text-success' : 'text-danger');
                el.querySelector('i').className = pass ? 'ri-checkbox-circle-line' : 'ri-close-circle-line';
            });

            // Update match feedback jika ada
            if (matchInputId && matchFeedbackId) {
                const matchVal = document.getElementById(matchInputId)?.value ?? '';
                updatePasswordMatch(matchInputId, matchFeedbackId, val, matchVal);
            }
        });

        // Jika ini adalah input konfirmasi, pantau juga dari sisi konfirmasi
        if (matchInputId && matchFeedbackId) {
            const matchInput = document.getElementById(matchInputId);
            if (matchInput) {
                matchInput.addEventListener('input', function () {
                    updatePasswordMatch(matchInputId, matchFeedbackId, input.value, this.value);
                });
            }
        }
    }

    function updatePasswordMatch(matchInputId, matchFeedbackId, password, confirmation) {
        const el = document.getElementById(matchFeedbackId);
        if (!el) return;
        if (confirmation === '') {
            el.innerHTML = '';
            return;
        }
        const match = password === confirmation;
        el.innerHTML = `<div class="d-flex align-items-center gap-1 small ${match ? 'text-success' : 'text-danger'}">
            <i class="${match ? 'ri-checkbox-circle-line' : 'ri-close-circle-line'}"></i>
            ${match ? 'Password cocok' : 'Password tidak cocok'}
        </div>`;
    }
</script>
<script src="{{ asset('assets/libs/sortablejs/Sortable.min.js') }}"></script>
<script src="{{ asset('assets/js/reorder.js') }}"></script>
<script src="{{ asset('assets/js/rupiah-input.js') }}"></script>
