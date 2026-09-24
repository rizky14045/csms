{{-- Tombol riwayat status (popup). Pemakaian: @include('components.status-history-button', ['type' => 'kpi', 'id' => $kpi->id]) --}}
<button type="button" class="btn btn-secondary btn-sm btn-status-history" style="min-width:80px;"
    data-url="{{ route('status-history.show', ['type' => $type, 'id' => $id]) }}">
    🕘 History
</button>

@once
<script>
document.addEventListener('click', async function (e) {
    const btn = e.target.closest('.btn-status-history');
    if (!btn) return;

    const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    btn.disabled = true;

    try {
        const res = await fetch(btn.dataset.url, {headers: {'Accept': 'application/json'}});
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const rows = (await res.json()).data || [];

        const body = rows.length
            ? '<table class="table table-bordered table-sm text-start mb-0"><thead class="table-light"><tr>'
              + '<th>Waktu</th><th>Oleh</th><th>Aktivitas</th></tr></thead><tbody>'
              + rows.map(r => '<tr><td style="white-space:nowrap;">' + esc(r.time) + '</td><td>' + esc(r.user)
                  + (r.role ? '<div class="text-muted small">' + esc(r.role) + '</div>' : '') + '</td><td>' + esc(r.label) + '</td></tr>').join('')
              + '</tbody></table>'
            : '<div class="text-muted">Belum ada riwayat tercatat.</div>';

        Swal.fire({title: 'History', html: body, width: 720, confirmButtonText: 'Tutup'});
    } catch (err) {
        Swal.fire('Gagal', 'Riwayat tidak dapat dimuat.', 'error');
    } finally {
        btn.disabled = false;
    }
});
</script>
@endonce
