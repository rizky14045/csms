@if(!empty($canAllocate))
<div class="form-group mb-3">
    <label class="form-label">Pembagian Jumlah (Induk + UL)</label>
    <div class="border rounded p-3">
        @foreach($groupUnits as $u)
            <div class="row align-items-center mb-2">
                <div class="col-md-7">
                    {{ $u->name }}
                    @if($u->id == auth()->user()->unit_id)
                        <span class="badge bg-primary">Induk</span>
                    @else
                        <span class="badge bg-secondary">UL</span>
                    @endif
                </div>
                <div class="col-md-5">
                    <input type="number" min="0" step="1" class="form-control alloc-input"
                        name="alloc[{{ $u->id }}]" value="{{ old('alloc.' . $u->id, $allocations[$u->id] ?? 0) }}">
                </div>
            </div>
        @endforeach
        <div class="small mt-2">Total pembagian: <strong id="alloc-sum">0</strong> dari jumlah standar kontrak <strong id="alloc-total">0</strong></div>
    </div>
    @error('alloc')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
    <div class="form-text text-muted">Jumlah induk + seluruh UL harus sama dengan jumlah standar kontrak. UL hanya dapat melihat bagiannya.</div>
</div>
<script>
    (function () {
        var total = document.getElementById('standard_contract');
        var inputs = document.querySelectorAll('.alloc-input');
        function refresh() {
            var sum = 0;
            inputs.forEach(function (i) { sum += parseInt(i.value || 0, 10) || 0; });
            var t = parseInt(total.value || 0, 10) || 0;
            document.getElementById('alloc-sum').textContent = sum;
            document.getElementById('alloc-total').textContent = t;
            document.getElementById('alloc-sum').className = sum === t ? 'text-success' : 'text-danger';
        }
        inputs.forEach(function (i) { i.addEventListener('input', refresh); });
        total.addEventListener('input', refresh);
        refresh();
    })();
</script>
@endif
