/*
 * Input rupiah: tampil "1.234.567,5" saat mengetik, tetapi yang dikirim ke server
 * adalah angka murni ("1234567.5") lewat input hidden ber-name yang sama.
 * Pakai: <input class="rupiah-input" name="..." value="1234567.50">
 */
(function () {
    function format(digits, decimals, hasComma) {
        digits = digits.replace(/^0+(?=\d)/, '');
        var out = digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        if (hasComma) out += ',' + decimals;
        return out;
    }

    // "1.234.567,5" (tampilan) -> {int, dec, comma}
    function parseDisplay(text) {
        var idx = text.indexOf(',');
        var intPart = (idx >= 0 ? text.slice(0, idx) : text).replace(/\D/g, '');
        var dec = idx >= 0 ? text.slice(idx + 1).replace(/\D/g, '').slice(0, 2) : '';
        return { int: intPart, dec: dec, comma: idx >= 0 };
    }

    // nilai dari DB / old() ("1234567.50" atau "1234567") -> tampilan
    function fromNumber(value) {
        var m = String(value == null ? '' : value).trim().match(/^(\d+)(?:\.(\d+))?$/);
        if (!m) return '';
        var dec = (m[2] || '').replace(/0+$/, '').slice(0, 2);
        return format(m[1], dec, dec !== '');
    }

    function toNumber(text) {
        var p = parseDisplay(text);
        if (p.int === '' && p.dec === '') return '';
        return (p.int || '0') + (p.dec ? '.' + p.dec : '');
    }

    function init(input) {
        if (input.dataset.rupiahReady) return;
        input.dataset.rupiahReady = '1';

        var hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = input.name;
        input.removeAttribute('name');
        input.parentNode.insertBefore(hidden, input.nextSibling);

        input.setAttribute('inputmode', 'decimal');
        input.setAttribute('autocomplete', 'off');
        input.value = fromNumber(input.value);
        hidden.value = toNumber(input.value);

        input.addEventListener('input', function () {
            var before = input.value;
            var caret = input.selectionStart;
            var digitsBeforeCaret = before.slice(0, caret).replace(/[^\d,]/g, '').length;

            var p = parseDisplay(before);
            var formatted = (p.int === '' && !p.comma && p.dec === '') ? '' : format(p.int || '0', p.dec, p.comma);
            input.value = formatted;

            // pertahankan posisi kursor berdasarkan jumlah digit/koma sebelum kursor
            var pos = 0, seen = 0;
            while (pos < formatted.length && seen < digitsBeforeCaret) {
                if (/[\d,]/.test(formatted[pos])) seen++;
                pos++;
            }
            input.setSelectionRange(pos, pos);

            hidden.value = toNumber(formatted);
        });

        input.addEventListener('blur', function () {
            var n = toNumber(input.value);
            input.value = fromNumber(n);
            hidden.value = n;
        });
    }

    function initAll() {
        document.querySelectorAll('.rupiah-input').forEach(init);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }
})();
