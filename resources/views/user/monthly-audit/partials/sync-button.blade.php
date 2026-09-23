<button type="button" class="btn btn-outline-primary btn-sm"
        title="Ambil data baru dari master data (data yang dibuat khusus di laporan ini tidak hilang)"
        onclick="confirmPostAction('{{ route('user.monthly-audit.sync', ['monthlyId' => $monthlyId, 'section' => $section]) }}', 'Sinkron dengan master data?', 'Data baru dari master data akan ditambahkan ke laporan ini. Data yang sudah ada tidak berubah.')">
    &#8635; Sinkron Master
</button>
