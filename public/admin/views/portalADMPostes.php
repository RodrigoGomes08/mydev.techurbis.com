<?php include __DIR__ . "/../../includes/header.php"; ?>

<main class="content-admin">
    <section id="postes" class="adm-page active">

        <!-- CABEÇALHO -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="mb-1">Postes de Iluminação</h2>
                <p class="text-muted mb-0" style="font-size:0.9rem;">
                    <i class="bi bi-lightbulb me-1"></i>
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-outline-danger btn-sm" id="btnPostesAvariados">
                    <i class="bi bi-exclamation-triangle me-1"></i>Só Avariados
                </button>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdicionarPoste">
                    <i class="bi bi-plus-circle me-1"></i>Adicionar Poste
                </button>
            </div>
        </div>

        <!-- KPIs -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="pkpi-card">
                    <div class="pkpi-icon" style="background:#e8edff;color:#435ebe;"><i class="bi bi-lightbulb-fill"></i></div>
                    <div>
                        <div class="pkpi-value"><?= count($postes) ?></div>
                        <div class="pkpi-label">Total de Postes</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="pkpi-card">
                    <div class="pkpi-icon" style="background:#e6f9ef;color:#27ae60;"><i class="bi bi-check-circle-fill"></i></div>
                    <div>
                        <div class="pkpi-value text-success">
                            <?= $numPostePorEstado['candeeiros_operacionais'] ?>
                        </div>
                        <div class="pkpi-label">Operacionais</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="pkpi-card">
                    <div class="pkpi-icon" style="background:#fde8e8;color:#e74c3c;"><i class="bi bi-x-circle-fill"></i></div>
                    <div>
                        <div class="pkpi-value text-danger"><?= $numPostePorEstado['candeeiros_avariados'] ?></div>
                        <div class="pkpi-label">Avariados</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="pkpi-card">
                    <div class="pkpi-icon" style="background:#fff4e0;color:#f39c12;"><i class="bi bi-tools"></i></div>
                    <div>
                        <div class="pkpi-value text-warning"><?= $numPostePorEstado['candeeiros_em_manutencao'] ?></div>
                        <div class="pkpi-label">Em Manutenção</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTRO -->
        <div class="d-flex gap-2 mb-4 flex-wrap">
            <div class="input-group" style="max-width:300px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" id="searchPoste" class="form-control border-start-0 ps-0" placeholder="Pesquisar poste...">
            </div>
            <select id="filtroPosteEstado" class="form-select" style="max-width:180px;">
                <option value="">Todos os estados</option>
                <option value="1">Operacional</option>
                <option value="2">Avariado</option>
                <option value="3">Manutenção</option>
            </select>
        </div>

        <!-- TABELA -->
        <div class="card-admin">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-admin table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Longitude</th>
                                <th>Latitude</th>
                                <th>Estado</th>
                                <th>Observações</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="listaPostes">
                        <?php if (empty($postes)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-lightbulb me-2"></i>Nenhum poste encontrado.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($postes as $poste):
                                $estadoId = $poste->getIdEstado();
                                if ($estadoId == 1) {
                                    $badgeCls = 'badge-status-ok'; $badgeIcon = 'bi-check-circle'; $badgeLabel = 'Operacional';
                                } elseif ($estadoId == 2) {
                                    $badgeCls = 'badge-status-error'; $badgeIcon = 'bi-x-circle'; $badgeLabel = 'Avariado';
                                } else {
                                    $badgeCls = 'badge-status-warning'; $badgeIcon = 'bi-exclamation-circle'; $badgeLabel = 'Manutenção';
                                }
                            ?>
                            <tr data-estado="<?= $estadoId ?>">
                                <td><strong><?= htmlspecialchars($poste->getId()) ?></strong></td>
                                <td><?= htmlspecialchars($poste->getLongitude()) ?></td>
                                <td><?= htmlspecialchars($poste->getLatitude()) ?></td>
                                <td>
                                    <span class="badge <?= $badgeCls ?>">
                                        <i class="bi <?= $badgeIcon ?>"></i> <?= $badgeLabel ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($poste->getObservacao()) ?></td>
                                <td class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary btn-editar-poste"
                                        data-id="<?= $poste->getId() ?>"
                                        data-id-cidade="<?= $poste->getIdCidade() ?>"
                                        data-id-estado="<?= $estadoId ?>"
                                        data-longitude="<?= htmlspecialchars($poste->getLongitude(), ENT_QUOTES) ?>"
                                        data-latitude="<?= htmlspecialchars($poste->getLatitude(), ENT_QUOTES) ?>"
                                        data-observacao="<?= htmlspecialchars($poste->getObservacao(), ENT_QUOTES) ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger btn-eliminar-poste"
                                        data-id="<?= $poste->getId() ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- PAGINAÇÃO -->
                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                    <div class="text-muted" style="font-size:0.875rem;" id="paginacaoInfo"></div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0" id="paginacaoControlos"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>
</main>
</div>

<button class="btn btn-dark position-fixed bottom-0 end-0 m-4" data-bs-toggle="offcanvas" data-bs-target="#menuExtra">
    <i class="bi bi-book"></i> Informações Diárias
</button>
<div class="offcanvas offcanvas-admin offcanvas-end" tabindex="-1" id="menuExtra">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title"><i class="bi bi-speedometer2"></i> Informações Diárias</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <div class="list-group">
            <div class="list-group-item d-flex align-items-center gap-3">
                <span style="background:#fde8e8;color:#e74c3c;border-radius:10px;padding:8px 10px;font-size:1.2rem;"><i class="bi bi-thermometer-half"></i></span>
                <div><div style="font-size:0.75rem;color:#999;">Temperatura</div><div class="fw-bold">22.5°C</div></div>
            </div>
            <div class="list-group-item d-flex align-items-center gap-3">
                <span style="background:#e8edff;color:#435ebe;border-radius:10px;padding:8px 10px;font-size:1.2rem;"><i class="bi bi-droplet"></i></span>
                <div><div style="font-size:0.75rem;color:#999;">Humidade</div><div class="fw-bold">65%</div></div>
            </div>
            <div class="list-group-item d-flex align-items-center gap-3">
                <span style="background:#e6f9ef;color:#27ae60;border-radius:10px;padding:8px 10px;font-size:1.2rem;"><i class="bi bi-wind"></i></span>
                <div><div style="font-size:0.75rem;color:#999;">Qualidade do Ar</div><div class="fw-bold">Bom</div></div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ADICIONAR POSTE -->
<div class="modal fade" id="modalAdicionarPoste" tabindex="-1" aria-labelledby="modalAdicionarPosteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/admin/create-poste" method="POST">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                    <h5 class="modal-title" id="modalAdicionarPosteLabel">Adicionar Poste</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">ID do Poste</label>
                        <input name="id" type="number" class="form-control" placeholder="Ex: 101" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Longitude</label>
                            <input name="longitude" type="number" step="any" class="form-control" placeholder="Ex: -8.6291" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Latitude</label>
                            <input name="latitude" type="number" step="any" class="form-control" placeholder="Ex: 41.1579" required>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">ID Cidade</label>
                            <input name="id_cidade" type="number" class="form-control" placeholder="Ex: 1" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Estado</label>
                            <select name="id_estado" class="form-select" required>
                                <option value="1">Operacional</option>
                                <option value="2">Avariado</option>
                                <option value="3">Manutenção</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Observações</label>
                        <textarea name="observacao" class="form-control" rows="3" placeholder="Descrição do estado ou ocorrência..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i>Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDITAR POSTE -->
<div class="modal fade" id="modalEditarPoste" tabindex="-1" aria-labelledby="modalEditarPosteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/admin/update-poste" method="POST">
                <input type="hidden" name="id" id="edit_poste_id">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                    <h5 class="modal-title" id="modalEditarPosteLabel"><i class="bi bi-pencil-square me-2"></i>Editar Poste</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Longitude</label>
                            <input name="longitude" id="edit_poste_longitude" type="number" step="any" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Latitude</label>
                            <input name="latitude" id="edit_poste_latitude" type="number" step="any" class="form-control" required>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">ID Cidade</label>
                            <input name="id_cidade" id="edit_poste_id_cidade" type="number" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Estado</label>
                            <select name="id_estado" id="edit_poste_id_estado" class="form-select">
                                <option value="1">Operacional</option>
                                <option value="2">Avariado</option>
                                <option value="3">Manutenção</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Observações</label>
                        <textarea name="observacao" id="edit_poste_observacao" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i>Guardar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL CONFIRMAR ELIMINAÇÃO -->
<div class="modal fade" id="modalEliminarPoste" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Confirmar Eliminação</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-lightbulb" style="font-size:3rem;color:#e74c3c;"></i>
                <p class="mt-3 mb-1">Tens a certeza que queres eliminar o poste</p>
                <p class="fw-bold fs-5" id="eliminar_poste_id_display"></p>
                <p class="text-muted" style="font-size:0.85rem;">Esta ação é irreversível.</p>
                <input type="hidden" id="eliminar_poste_id">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarEliminarPoste"><i class="bi bi-trash me-1"></i>Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const POR_PAGINA = 20;
    let paginaAtual = 1;

    function todasLinhas() {
        return Array.from(document.querySelectorAll('#listaPostes tr[data-estado]'));
    }

    // Linhas que passam nos filtros actuais (não estão com display:none pelo filtro)
    function linhasFiltradas() {
        return todasLinhas().filter(r => r.dataset.filtrado !== 'false');
    }

    // Aplica pesquisa + filtro de estado e marca cada linha
    function aplicarFiltros() {
        const termo    = (document.getElementById('searchPoste')?.value || '').toLowerCase();
        const estadoId = document.getElementById('filtroPosteEstado')?.value || '';

        todasLinhas().forEach(row => {
            const ok = (!termo    || row.innerText.toLowerCase().includes(termo))
                    && (!estadoId || row.dataset.estado === estadoId);
            row.dataset.filtrado = ok ? 'true' : 'false';
            if (!ok) row.classList.add('d-none');
        });

        paginaAtual = 1;
        aplicarPaginacao();
    }

    function aplicarPaginacao() {
        const linhas = linhasFiltradas();
        const total  = linhas.length;
        const totalPaginas = Math.max(1, Math.ceil(total / POR_PAGINA));

        if (paginaAtual > totalPaginas) paginaAtual = 1;

        const inicio = (paginaAtual - 1) * POR_PAGINA;
        const fim    = inicio + POR_PAGINA;

        // Esconde tudo primeiro, depois mostra só a página actual
        todasLinhas().forEach(r => r.classList.add('d-none'));
        linhas.forEach((r, i) => {
            r.classList.toggle('d-none', i < inicio || i >= fim);
        });

        // Info
        const info = document.getElementById('paginacaoInfo');
        if (info) {
            info.textContent = total === 0
                ? 'Sem resultados'
                : `A mostrar ${inicio + 1}–${Math.min(fim, total)} de ${total} postes`;
        }

        renderControlos(totalPaginas);
    }

    function renderControlos(totalPaginas) {
        const ul = document.getElementById('paginacaoControlos');
        if (!ul) return;
        ul.innerHTML = '';

        const btn = (pagina, conteudo, desativado = false, ativo = false) => `
            <li class="page-item ${desativado ? 'disabled' : ''} ${ativo ? 'active' : ''}">
                <button class="page-link" data-pagina="${pagina}" ${desativado ? 'tabindex="-1"' : ''}>${conteudo}</button>
            </li>`;

        ul.insertAdjacentHTML('beforeend', btn(paginaAtual - 1, '<i class="bi bi-chevron-left"></i>', paginaAtual === 1));

        const delta = 2;
        const ini = Math.max(1, paginaAtual - delta);
        const fim = Math.min(totalPaginas, paginaAtual + delta);

        if (ini > 1) {
            ul.insertAdjacentHTML('beforeend', btn(1, '1'));
            if (ini > 2) ul.insertAdjacentHTML('beforeend', `<li class="page-item disabled"><span class="page-link">…</span></li>`);
        }
        for (let p = ini; p <= fim; p++) {
            ul.insertAdjacentHTML('beforeend', btn(p, p, false, p === paginaAtual));
        }
        if (fim < totalPaginas) {
            if (fim < totalPaginas - 1) ul.insertAdjacentHTML('beforeend', `<li class="page-item disabled"><span class="page-link">…</span></li>`);
            ul.insertAdjacentHTML('beforeend', btn(totalPaginas, totalPaginas));
        }

        ul.insertAdjacentHTML('beforeend', btn(paginaAtual + 1, '<i class="bi bi-chevron-right"></i>', paginaAtual === totalPaginas));

        ul.querySelectorAll('button[data-pagina]').forEach(b => {
            b.addEventListener('click', function () {
                const p = parseInt(this.dataset.pagina);
                const tp = Math.ceil(linhasFiltradas().length / POR_PAGINA) || 1;
                if (p >= 1 && p <= tp) { paginaAtual = p; aplicarPaginacao(); }
            });
        });
    }

    // Listeners
    document.getElementById('searchPoste')?.addEventListener('input', aplicarFiltros);
    document.getElementById('filtroPosteEstado')?.addEventListener('change', aplicarFiltros);

    document.getElementById('btnPostesAvariados')?.addEventListener('click', function () {
        const filtro = document.getElementById('filtroPosteEstado');
        if (filtro.value === '2') {
            filtro.value = '';
            this.classList.remove('active', 'btn-danger');
            this.classList.add('btn-outline-danger');
        } else {
            filtro.value = '2';
            this.classList.add('active', 'btn-danger');
            this.classList.remove('btn-outline-danger');
        }
        aplicarFiltros();
    });

    // Inicializa — marca todas como filtrado=true
    todasLinhas().forEach(r => r.dataset.filtrado = 'true');
    aplicarPaginacao();
})();
</script>

<?php include __DIR__ . "/../../includes/footer.php"; ?>