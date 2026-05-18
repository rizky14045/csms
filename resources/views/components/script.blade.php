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

<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
@yield('scripts')