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
@yield('scripts')