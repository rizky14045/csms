// Drag & drop ordering. Container: [data-reorder-url][data-entity][data-item][data-token]
(function () {
    function init() {
        if (typeof Sortable === 'undefined') return;

        document.querySelectorAll('[data-reorder-url]').forEach(function (container) {
            if (container.dataset.sortableInit) return;
            container.dataset.sortableInit = '1';

            var itemSelector = container.dataset.item || '.sortable-item';

            Sortable.create(container, {
                handle: '.drag-handle',
                draggable: itemSelector,
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: function (evt) {
                    if (evt.oldIndex === evt.newIndex) return;

                    var items = Array.prototype.slice.call(container.children)
                        .filter(function (el) { return el.matches(itemSelector); });

                    var ids = items.map(function (el) { return parseInt(el.dataset.id, 10); });

                    // Level menentukan urutan: perbarui label "Level N" di layar
                    // begitu urutan berubah, tanpa menunggu respons server.
                    if (container.dataset.entity === 'assesment_level') {
                        items.forEach(function (el, index) {
                            var badge = el.querySelector('.level-badge');
                            if (badge) badge.textContent = 'Level ' + (index + 1);
                        });
                    }

                    fetch(container.dataset.reorderUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': container.dataset.token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            entity: container.dataset.entity,
                            type: container.dataset.type || '',
                            ids: ids
                        })
                    }).then(function (res) {
                        return res.json().then(function (body) { return { ok: res.ok, body: body }; });
                    }).then(function (r) {
                        if (!r.ok) {
                            alert(r.body.message || 'Gagal menyimpan urutan');
                            window.location.reload();
                        }
                    }).catch(function () {
                        alert('Gagal menyimpan urutan');
                        window.location.reload();
                    });
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
