<button type="button" class="btn btn-outline-primary btn-sm"
        title="Ambil data baru dari master data (data yang dibuat khusus di laporan ini tidak hilang)"
        onclick="confirmPostAction('{{ route('user.monthly-audit.sync', ['monthlyId' => $monthlyId, 'section' => $section]) }}', 'Sinkron dengan master data?', 'Data baru dari master data akan ditambahkan, dan data yang berubah di master (lebih baru dari perubahan di laporan ini) akan diperbarui. Perubahan yang Anda buat di laporan setelah perubahan master dipertahankan.')">
    &#8635; Sinkron Master
</button>
