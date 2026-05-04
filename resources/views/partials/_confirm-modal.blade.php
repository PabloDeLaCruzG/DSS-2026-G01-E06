{{-- Modal de confirmación global. Activar con data-confirm="mensaje" en el <form>. --}}
<div id="confirmModal"
     class="fixed inset-0 z-[999] hidden items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
     role="dialog" aria-modal="true" aria-labelledby="confirmModalTitle">
    <div class="w-full max-w-sm bg-surface border border-border rounded-xl shadow-2xl p-6">

        {{-- Icono de advertencia --}}
        <div class="flex justify-center mb-4">
            <div class="w-12 h-12 rounded-full bg-red-500/10 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
        </div>

        <h3 id="confirmModalTitle" class="text-base font-semibold text-text-main text-center mb-2">
            ¿Estás seguro?
        </h3>
        <p id="confirmModalMessage" class="text-sm text-text-muted text-center mb-6 leading-relaxed"></p>

        <div class="flex gap-3">
            <button id="confirmModalCancel"
                    class="flex-1 px-4 py-2 text-sm rounded-lg bg-surface border border-border text-text-muted hover:text-text-main transition">
                Cancelar
            </button>
            <button id="confirmModalAccept"
                    class="flex-1 px-4 py-2 text-sm rounded-lg bg-red-500 hover:bg-red-600 text-white font-medium transition">
                Confirmar
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    const modal   = document.getElementById('confirmModal');
    const message = document.getElementById('confirmModalMessage');
    const cancel  = document.getElementById('confirmModalCancel');
    const accept  = document.getElementById('confirmModalAccept');
    let pendingForm = null;

    function open(text, form) {
        pendingForm = form;
        message.textContent = text;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        cancel.focus();
    }

    function close() {
        pendingForm = null;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Interceptar submit de formularios con data-confirm
    document.addEventListener('submit', function (e) {
        const form = e.target.closest('form[data-confirm]');
        if (!form) return;
        e.preventDefault();
        open(form.dataset.confirm, form);
    }, true);

    accept.addEventListener('click', function () {
        if (!pendingForm) return;
        const form = pendingForm;
        close();
        // Eliminar el atributo para que no vuelva a interceptarse
        form.removeAttribute('data-confirm');
        form.submit();
    });

    cancel.addEventListener('click', close);

    // Cerrar con Escape o click fuera del card
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) close();
    });

    modal.addEventListener('click', function (e) {
        if (e.target === modal) close();
    });
})();
</script>
