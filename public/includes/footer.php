<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/scriptsPortal.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let parqueIdParaEliminar = null;

    // Ao clicar no botão de lixo de cada card — abre o modal e guarda o ID
    document.querySelectorAll('.btn-eliminar-parque').forEach(function (btn) {
        btn.addEventListener('click', function () {
            parqueIdParaEliminar = this.dataset.id;
            document.getElementById('eliminar_parque_nome').textContent = this.dataset.nome;

            const modal = new bootstrap.Modal(document.getElementById('modalEliminarParque'));
            modal.show();
        });
    });

    // Ao confirmar a eliminação no modal — faz o fetch e remove o card
    document.getElementById('btnConfirmarEliminarParque').addEventListener('click', function () {
        if (!parqueIdParaEliminar) return;

        fetch('/admin/delete-parque/' + parqueIdParaEliminar, {
            method: 'DELETE',  // ou 'POST', dependendo da tua rota
        })
        .then(res => res.json())
        .then(function (data) {
            bootstrap.Modal.getInstance(document.getElementById('modalEliminarParque')).hide();

            if (data.success) {
                // Remove o card do DOM sem recarregar a página
                const card = document.querySelector('[data-id="' + parqueIdParaEliminar + '"]');
                if (card) card.remove();

                showToast(data.message, 'success');
            } else {
                showToast(data.message, 'error');
            }

            parqueIdParaEliminar = null;
        })
        .catch(function () {
            showToast('Erro de ligação ao servidor.', 'error');
        });
    });

});
</script>