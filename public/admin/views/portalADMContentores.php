<?php include __DIR__ . "/../../includes/header.php"; ?>
<?php
$totalContentores = count($contentores);
$totalCriticos = 0;
$totalAtencao = 0;
$totalNormal = 0;
$porCidade = [];

foreach ($contentores as $c) {
    if ($c->getIsFull() || $c->getIdEstado() === 3) {
        $estadoKey = 'critico';
        $totalCriticos++;
    } elseif ($c->getIdEstado() === 2) {
        $estadoKey = 'atencao';
        $totalAtencao++;
    } else {
        $estadoKey = 'normal';
        $totalNormal++;
    }
    $cidadeId = $c->getIdCidade();
    if (!isset($porCidade[$cidadeId])) {
        $porCidade[$cidadeId] = ['contentores' => [], 'criticos' => 0];
    }
    $porCidade[$cidadeId]['contentores'][] = ['obj' => $c, 'estado' => $estadoKey];
    if ($estadoKey === 'critico')
        $porCidade[$cidadeId]['criticos']++;
}
$lastSync = date('d/m/Y H:i:s');
?>
<main class="content-admin">
    <section id="contentores" class="adm-page active">

        <!-- CABEÇALHO -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="mb-1">Contentores de Lixo</h2>
                <p class="text-muted mb-0" style="font-size:0.9rem;">
                    <i class="bi bi-clock me-1"></i>Última sincronização:
                    <span><?= htmlspecialchars($lastSync) ?></span>
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-outline-danger btn-sm" id="btnContCriticos">
                    <i class="bi bi-exclamation-triangle me-1"></i>Só Críticos
                </button>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCriarContentor">
                    <i class="bi bi-plus-lg me-1"></i>Novo Contentor
                </button>
            </div>
        </div>

        <!-- KPIs -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="pkpi-card">
                    <div class="pkpi-icon" style="background:#e8edff;color:#435ebe;"><i class="bi bi-trash-fill"></i>
                    </div>
                    <div>
                        <div class="pkpi-value"><?= $totalContentores ?></div>
                        <div class="pkpi-label">Total de Contentores</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="pkpi-card">
                    <div class="pkpi-icon" style="background:#fde8e8;color:#e74c3c;"><i
                            class="bi bi-exclamation-triangle-fill"></i></div>
                    <div>
                        <div class="pkpi-value text-danger"><?= $totalCriticos ?></div>
                        <div class="pkpi-label">Críticos (Cheios)</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="pkpi-card">
                    <div class="pkpi-icon" style="background:#fff4e0;color:#f39c12;"><i
                            class="bi bi-dash-circle-fill"></i></div>
                    <div>
                        <div class="pkpi-value text-warning"><?= $totalAtencao ?></div>
                        <div class="pkpi-label">Em Atenção</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="pkpi-card">
                    <div class="pkpi-icon" style="background:#e6f9ef;color:#27ae60;"><i
                            class="bi bi-check-circle-fill"></i></div>
                    <div>
                        <div class="pkpi-value text-success"><?= $totalNormal ?></div>
                        <div class="pkpi-label">Normais</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTROS -->
        <div class="d-flex gap-2 mb-4 flex-wrap">
            <div class="input-group" style="max-width:300px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" id="searchContentor" class="form-control border-start-0 ps-0"
                    placeholder="Pesquisar contentor...">
            </div>
            <select id="filtroContentorEstado" class="form-select" style="max-width:200px;">
                <option value="">Todos os estados</option>
                <option value="critico">Crítico (Cheio)</option>
                <option value="atencao">Atenção</option>
                <option value="normal">Normal</option>
            </select>
        </div>

        <?php if (empty($contentores)): ?>
            <div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>Nenhum contentor encontrado.</div>
        <?php endif; ?>

        <!-- SECÇÕES POR CIDADE -->
        <?php foreach ($porCidade as $cidadeId => $cidade):
            $collapseId = 'collapseC' . $cidadeId;
            $numCriticos = $cidade['criticos'];
            $numTotal = count($cidade['contentores']);
            ?>
            <div class="mb-3 secao-cidade">
                <button
                    class="btn btn-outline-primary w-100 text-start d-flex justify-content-between align-items-center py-3 px-4"
                    style="border-radius:10px;font-weight:600;background:white;border-color:#e6e9f0;"
                    data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>">
                    <span><i class="bi bi-geo-alt me-2" style="color:#435ebe;"></i>Cidade
                        <?= htmlspecialchars($cidadeId) ?></span>
                    <span class="d-flex align-items-center gap-2">
                        <?php if ($numCriticos > 0): ?>
                            <span class="badge" style="background:#fde8e8;color:#e74c3c;font-size:0.75rem;"><?= $numCriticos ?>
                                crítico<?= $numCriticos > 1 ? 's' : '' ?></span>
                        <?php endif; ?>
                        <span class="badge" style="background:#e8edff;color:#435ebe;font-size:0.75rem;"><?= $numTotal ?>
                            contentor<?= $numTotal > 1 ? 'es' : '' ?></span>
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </button>

                <div class="collapse" id="<?= $collapseId ?>">
                    <?php if ($numCriticos > 0): ?>
                        <div class="parque-alerta">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <?= $numCriticos ?> contentor<?= $numCriticos > 1 ? 'es cheios' : ' cheio' ?> nesta área
                        </div>
                    <?php endif; ?>

                    <div class="row g-3 mt-1">
                        <?php foreach ($cidade['contentores'] as $item):
                            $c = $item['obj'];
                            $estado = $item['estado'];
                            $corPonto = match ($estado) { 'critico' => '#e74c3c', 'atencao' => '#f39c12', default => '#2ecc71'};
                            $labelEstado = match ($estado) { 'critico' => 'Cheio', 'atencao' => 'Atenção', default => 'Normal'};
                            $tagClass = match ($estado) { 'critico' => '', 'atencao' => 'mr', default => 'ev'};
                            $barPct = match ($estado) { 'critico' => 100, 'atencao' => 65, default => 30};
                            $mMapaId = 'modalMapa_' . $c->getId();
                            $mEditId = 'modalEdit_' . $c->getId();
                            $mDeleteId = 'modalDelete_' . $c->getId();
                            ?>
                            <div class="col-12 col-md-6 col-lg-3 card-contentor" data-estado="<?= $estado ?>"
                                data-label="<?= strtolower(htmlspecialchars($c->getIdentificacao())) ?>">
                                <div class="parque-card <?= $estado ?>">
                                    <div class="parque-card-header">
                                        <div class="parque-titulo">
                                            <span><i
                                                    class="bi bi-trash me-1"></i><?= htmlspecialchars($c->getIdentificacao()) ?></span>
                                            <span class="parque-tag <?= $tagClass ?>"
                                                style="font-size:0.7rem;"><?= $labelEstado ?></span>
                                        </div>
                                        <div class="parque-subtitulo">
                                            <i class="bi bi-circle-fill" style="color:<?= $corPonto ?>;font-size:0.5rem;"></i>
                                            <?= $labelEstado ?>
                                        </div>
                                    </div>
                                    <div class="parque-ocupacao-wrap">
                                        <div class="parque-pct-num <?= $estado ?>"><?= $labelEstado ?></div>
                                        <div class="parque-prog">
                                            <div class="parque-prog-bar <?= $estado ?>" style="width:<?= $barPct ?>%;"></div>
                                        </div>
                                        <div class="parque-lugares-label">
                                            Cap. máx.: <?= $c->getCapacidadeMax() ?>L &nbsp;·&nbsp;
                                            <?= htmlspecialchars($c->getTipo()) ?>
                                        </div>
                                    </div>
                                    <div class="parque-actions">
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#<?= $mMapaId ?>">
                                            <i class="bi bi-geo-alt me-1"></i>Mapa
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#<?= $mEditId ?>">
                                            <i class="bi bi-pencil me-1"></i>Editar
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#<?= $mDeleteId ?>">
                                            <i class="bi bi-trash me-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL MAPA -->
                            <div class="modal fade" id="<?= $mMapaId ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                                            <h5 class="modal-title"><i class="bi bi-geo-alt me-2"></i>Localização —
                                                <?= htmlspecialchars($c->getIdentificacao()) ?></h5>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-0">
                                            <div style="width:100%;height:420px;">
                                                <iframe
                                                    src="https://www.google.com/maps?q=<?= $c->getLatitude() ?>,<?= $c->getLongitude() ?>&z=15&output=embed"
                                                    width="100%" height="100%" style="border:0;" loading="lazy"></iframe>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <small class="text-muted me-auto">Lat: <?= $c->getLatitude() ?> | Lng:
                                                <?= $c->getLongitude() ?></small>
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                data-bs-dismiss="modal">Fechar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL EDITAR -->
                            <div class="modal fade" id="<?= $mEditId ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                                            <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Editar Contentor</h5>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="/admin/update-contentor">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $c->getId() ?>">
                                                <div class="row g-3">
                                                    <div class="col-6">
                                                        <label class="form-label fw-semibold">Identificação</label>
                                                        <input type="text" name="identificacao" class="form-control"
                                                            value="<?= htmlspecialchars($c->getIdentificacao()) ?>" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-semibold">Tipo</label>
                                                        <input type="text" name="tipo" class="form-control"
                                                            value="<?= htmlspecialchars($c->getTipo()) ?>" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-semibold">ID Cidade</label>
                                                        <input type="number" name="id_cidade" class="form-control"
                                                            value="<?= $c->getIdCidade() ?>" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-semibold">Estado</label>
                                                        <select name="id_estado" class="form-select" required>
                                                            <option value="1" <?= $c->getIdEstado() === 1 ? 'selected' : '' ?>>
                                                                Normal</option>
                                                            <option value="2" <?= $c->getIdEstado() === 2 ? 'selected' : '' ?>>
                                                                Atenção</option>
                                                            <option value="3" <?= $c->getIdEstado() === 3 ? 'selected' : '' ?>>
                                                                Crítico</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Capacidade Máxima (L)</label>
                                                        <input type="number" name="capacidade_max" class="form-control"
                                                            value="<?= $c->getCapacidadeMax() ?>" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-semibold">Latitude</label>
                                                        <input type="text" name="latitude" class="form-control"
                                                            value="<?= $c->getLatitude() ?>" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-semibold">Longitude</label>
                                                        <input type="text" name="longitude" class="form-control"
                                                            value="<?= $c->getLongitude() ?>" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-semibold">Cheio</label>
                                                        <select name="is_full" class="form-select">
                                                            <option value="0" <?= !$c->getIsFull() ? 'selected' : '' ?>>Não
                                                            </option>
                                                            <option value="1" <?= $c->getIsFull() ? 'selected' : '' ?>>Sim
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-semibold">Observação</label>
                                                        <textarea name="observacao" class="form-control"
                                                            rows="2"><?= htmlspecialchars($c->getObservacao()) ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary btn-sm"><i
                                                        class="bi bi-floppy me-1"></i>Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- MODAL ELIMINAR -->
                            <div class="modal fade" id="<?= $mDeleteId ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title text-danger"><i
                                                    class="bi bi-exclamation-triangle-fill me-2"></i>Eliminar</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-center pt-1">
                                            <p class="mb-1">Tem a certeza que deseja eliminar</p>
                                            <strong><?= htmlspecialchars($c->getIdentificacao()) ?></strong>?
                                            <p class="text-muted mt-2" style="font-size:0.82rem;">Esta ação é irreversível.</p>
                                        </div>
                                        <div class="modal-footer border-0 justify-content-center gap-2">
                                            <button type="button" class="btn btn-secondary btn-sm"
                                                data-bs-dismiss="modal">Cancelar</button>
                                            <button type="button" class="btn btn-danger btn-sm btn-confirmar-delete"
                                                data-id="<?= $c->getId() ?>" data-modal-id="<?= $mDeleteId ?>">
                                                <i class="bi bi-trash me-1"></i>Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </section>

    <!-- MODAL CRIAR -->
    <div class="modal fade" id="modalCriarContentor" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Novo Contentor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="/admin/create-contentor">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">ID</label>
                                <input type="number" name="id" class="form-control" placeholder="Ex: 101" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Identificação</label>
                                <input type="text" name="identificacao" class="form-control"
                                    placeholder="Ex: Loures 009" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Tipo</label>
                                <input type="text" name="tipo" class="form-control" placeholder="Ex: Indiferenciado"
                                    required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">ID Cidade</label>
                                <input type="number" name="id_cidade" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Estado</label>
                                <select name="id_estado" class="form-select" required>
                                    <option value="1">Normal</option>
                                    <option value="2">Atenção</option>
                                    <option value="3">Crítico</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Capacidade Máxima (L)</label>
                                <input type="number" name="capacidade_max" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Latitude</label>
                                <input type="text" name="latitude" class="form-control" placeholder="38.7523" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Longitude</label>
                                <input type="text" name="longitude" class="form-control" placeholder="-9.1549" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Observação</label>
                                <textarea name="observacao" class="form-control" rows="2"
                                    placeholder="Notas opcionais..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Criar
                            Contentor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TOAST -->
    <div class="position-fixed bottom-0 start-50 translate-middle-x p-3" style="z-index:9999;">
        <div id="toastFeedback" class="toast align-items-center border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body fw-semibold" id="toastMsg">—</div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

</main>

<!-- OFFCANVAS -->
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
                <span style="background:#fde8e8;color:#e74c3c;border-radius:10px;padding:8px 10px;font-size:1.2rem;"><i
                        class="bi bi-thermometer-half"></i></span>
                <div>
                    <div style="font-size:0.75rem;color:#999;">Temperatura</div>
                    <div class="fw-bold">22.5&deg;C</div>
                </div>
            </div>
            <div class="list-group-item d-flex align-items-center gap-3">
                <span style="background:#e8edff;color:#435ebe;border-radius:10px;padding:8px 10px;font-size:1.2rem;"><i
                        class="bi bi-droplet"></i></span>
                <div>
                    <div style="font-size:0.75rem;color:#999;">Humidade</div>
                    <div class="fw-bold">65%</div>
                </div>
            </div>
            <div class="list-group-item d-flex align-items-center gap-3">
                <span style="background:#e6f9ef;color:#27ae60;border-radius:10px;padding:8px 10px;font-size:1.2rem;"><i
                        class="bi bi-wind"></i></span>
                <div>
                    <div style="font-size:0.75rem;color:#999;">Qualidade do Ar</div>
                    <div class="fw-bold">Bom</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/../../includes/footer.php"; ?>

<script>
    function showToast(msg, type) {
        const el = document.getElementById('toastFeedback');
        const txt = document.getElementById('toastMsg');
        el.className = 'toast align-items-center border-0 text-white bg-' + (type === 'success' ? 'success' : 'danger');
        txt.textContent = msg;
        bootstrap.Toast.getOrCreateInstance(el, { delay: 3500 }).show();
    }

    document.querySelectorAll('.btn-confirmar-delete').forEach(btn => {
        btn.addEventListener('click', async function () {
            const id = this.dataset.id;
            const modalId = this.dataset.modalId;
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            try {
                const res = await fetch('/admin/delete-contentor/' + id, { method: 'POST' });
                const data = await res.json();
                bootstrap.Modal.getInstance(document.getElementById(modalId))?.hide();
                if (data.success) {
                    showToast(data.message, 'success');
                    document.querySelector('.btn-confirmar-delete[data-id="' + id + '"]')
                        ?.closest('.card-contentor')?.remove();
                } else {
                    showToast(data.message, 'error');
                }
            } catch (e) {
                showToast('Erro de comunicacao.', 'error');
            }
            this.disabled = false;
            this.innerHTML = '<i class="bi bi-trash me-1"></i>Eliminar';
        });
    });

    function aplicarFiltroContentores() {
        const texto = (document.getElementById('searchContentor')?.value || '').toLowerCase();
        const estado = document.getElementById('filtroContentorEstado')?.value || '';
        document.querySelectorAll('.card-contentor').forEach(col => {
            const matchT = !texto || (col.dataset.label || '').includes(texto);
            const matchE = !estado || (col.dataset.estado || '') === estado;
            col.style.display = (matchT && matchE) ? '' : 'none';
        });
    }

    document.getElementById('searchContentor')?.addEventListener('input', aplicarFiltroContentores);
    document.getElementById('filtroContentorEstado')?.addEventListener('change', aplicarFiltroContentores);

    document.getElementById('btnContCriticos')?.addEventListener('click', function () {
        const filtro = document.getElementById('filtroContentorEstado');
        if (filtro.value === 'critico') {
            filtro.value = '';
            this.classList.remove('active', 'btn-danger');
            this.classList.add('btn-outline-danger');
        } else {
            filtro.value = 'critico';
            this.classList.add('active', 'btn-danger');
            this.classList.remove('btn-outline-danger');
        }
        aplicarFiltroContentores();
        if (filtro.value) {
            document.querySelectorAll('.secao-cidade .collapse').forEach(c => {
                new bootstrap.Collapse(c, { show: true });
            });
        }
    });

    <?php if (!empty($_SESSION['toast'])): ?>
        showToast(<?= json_encode($_SESSION['toast']['message']) ?>, <?= json_encode($_SESSION['toast']['type']) ?>);
        <?php unset($_SESSION['toast']); ?>
    <?php endif; ?>
</script>