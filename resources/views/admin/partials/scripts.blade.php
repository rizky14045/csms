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
@yield('scripts')