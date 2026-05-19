<?php include __DIR__ . "/../../includes/header.php"; ?>

<?php
// ── Calcular KPIs a partir dos dados reais ────────────────────────────────────
$total    = count($contentores);
$criticos = 0;
$atencao  = 0;
$normais  = 0;

// Agrupar contentores por cidade (id_cidade)
// Para mostrar o nome da cidade precisamos de o passar do controller;
// enquanto não existir, usamos "Cidade {id_cidade}" como fallback.
$grupos = [];
foreach ($contentores as $c) {
    $cidadeKey = $c->getIdCidade();
    $grupos[$cidadeKey][] = $c;

    // Calcular nível de ocupação com base em is_full e capacidade_max.
    // Como não há coluna "nivel_atual" na DB, usamos is_full como proxy:
    //   is_full = 1  → 100 %   |   is_full = 0 → assumimos 0 % para KPI
    // (quando tiveres sensores reais, substitui pela coluna correta)
    $pct = $c->getIsFull() ? 100 : 0;
    if ($pct > 80)       $criticos++;
    elseif ($pct >= 36)  $atencao++;
    else                 $normais++;
}

// Helper: classe CSS e label de estado
function estadoInfo(bool $isFull, int $pct = null): array {
    // Se não vier pct calculado, usa is_full
    $p = ($pct !== null) ? $pct : ($isFull ? 100 : 0);
    if ($p > 80)      return ['css' => 'critico',  'label' => 'Crítico',  'color' => '#e74c3c', 'badgebg' => '#fde8e8'];
    if ($p >= 36)     return ['css' => 'atencao',  'label' => 'Atenção',  'color' => '#f39c12', 'badgebg' => '#fff4e0'];
    return              ['css' => 'normal',   'label' => 'Normal',   'color' => '#27ae60', 'badgebg' => '#e6f9ef'];
}
?>

    <main class="content-admin">
        <section id="contentores" class="adm-page active">

            <!-- CABEÇALHO -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h2 class="mb-1">Contentores de Lixo</h2>
                    <p class="text-muted mb-0" style="font-size:0.9rem;">
                        <i class="bi bi-clock me-1"></i>Última sincronização: <span id="cont-sync-time">—</span>
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalCriarContentor">
                        <i class="bi bi-plus-lg me-1"></i>Novo Contentor
                    </button>
                    <button class="btn btn-outline-danger btn-sm" id="btnContCriticos">
                        <i class="bi bi-exclamation-triangle me-1"></i>Só Críticos
                    </button>
                </div>
            </div>

            <!-- KPIs dinâmicos -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#e8edff;color:#435ebe;"><i class="bi bi-trash-fill"></i></div>
                        <div><div class="pkpi-value"><?= $total ?></div><div class="pkpi-label">Total de Contentores</div></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#fde8e8;color:#e74c3c;"><i class="bi bi-exclamation-triangle-fill"></i></div>
                        <div><div class="pkpi-value text-danger"><?= $criticos ?></div><div class="pkpi-label">Críticos (&gt;80%)</div></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#fff4e0;color:#f39c12;"><i class="bi bi-dash-circle-fill"></i></div>
                        <div><div class="pkpi-value text-warning"><?= $atencao ?></div><div class="pkpi-label">Em Atenção (36–80%)</div></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#e6f9ef;color:#27ae60;"><i class="bi bi-check-circle-fill"></i></div>
                        <div><div class="pkpi-value text-success"><?= $normais ?></div><div class="pkpi-label">Normais (≤35%)</div></div>
                    </div>
                </div>
            </div>

            <!-- PESQUISA -->
            <div class="d-flex gap-2 mb-4 flex-wrap">
                <div class="input-group" style="max-width:300px;">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="searchContentor" class="form-control border-start-0 ps-0" placeholder="Pesquisar contentor...">
                </div>
                <select id="filtroContentorEstado" class="form-select" style="max-width:200px;">
                    <option value="">Todos os estados</option>
                    <option value="critico">Crítico (&gt;80%)</option>
                    <option value="atencao">Atenção (36–80%)</option>
                    <option value="normal">Normal (≤35%)</option>
                </select>
            </div>

            <!-- SECÇÕES AGRUPADAS POR CIDADE -->
            <?php if (empty($contentores)): ?>
                <div class="alert alert-info">Nenhum contentor registado.</div>
            <?php else: ?>
                <?php foreach ($grupos as $cidadeId => $lista):
                    // Contar críticos neste grupo (proxy via is_full)
                    $criticosCidade = count(array_filter($lista, fn($c) => $c->getIsFull()));
                    $collapseId = 'collapseCidade' . $cidadeId;
                    $nomeCidade = isset($cidades[$cidadeId]) ? $cidades[$cidadeId] : "Cidade {$cidadeId}";
                ?>
                <div class="mb-3 grupo-cidade">
                    <button class="btn btn-outline-primary w-100 text-start d-flex justify-content-between align-items-center py-3 px-4"
                        style="border-radius:10px;font-weight:600;background:white;border-color:#e6e9f0;"
                        data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>">
                        <span><i class="bi bi-geo-alt me-2" style="color:#435ebe;"></i><?= htmlspecialchars($nomeCidade) ?></span>
                        <span class="d-flex align-items-center gap-2">
                            <?php if ($criticosCidade > 0): ?>
                            <span class="badge" style="background:#fde8e8;color:#e74c3c;font-size:0.75rem;"><?= $criticosCidade ?> crítico<?= $criticosCidade > 1 ? 's' : '' ?></span>
                            <?php endif; ?>
                            <span class="badge" style="background:#e8edff;color:#435ebe;font-size:0.75rem;"><?= count($lista) ?> contentor<?= count($lista) > 1 ? 'es' : '' ?></span>
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </button>

                    <div class="collapse show" id="<?= $collapseId ?>">
                        <?php if ($criticosCidade > 0): ?>
                        <div class="parque-alerta">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <?= $criticosCidade ?> contentor<?= $criticosCidade > 1 ? 'es' : '' ?> acima de 80% nesta área
                        </div>
                        <?php endif; ?>

                        <div class="row g-3 mt-1">
                        <?php foreach ($lista as $cont):
                            $pct   = $cont->getIsFull() ? 100 : 0;
                            $info  = estadoInfo($cont->getIsFull(), $pct);
                            $css   = $info['css'];
                            $label = $info['label'];
                            $color = $info['color'];
                        ?>
                            <div class="col-12 col-md-6 col-lg-3"
                                 data-id="<?= $cont->getId() ?>"
                                 data-id-cidade="<?= $cont->getIdCidade() ?>"
                                 data-id-estado="<?= $cont->getIdEstado() ?>"
                                 data-capacidade-max="<?= $cont->getCapacidadeMax() ?>"
                                 data-longitude="<?= $cont->getLongitude() ?>"
                                 data-latitude="<?= $cont->getLatitude() ?>"
                                 data-tipo="<?= htmlspecialchars($cont->getTipo()) ?>"
                                 data-identificacao="<?= htmlspecialchars($cont->getIdentificacao()) ?>"
                                 data-observacao="<?= htmlspecialchars($cont->getObservacao()) ?>">
                                <div class="parque-card <?= $css ?>">
                                    <div class="parque-card-header">
                                        <div class="parque-titulo">
                                            <span><i class="bi bi-trash me-1"></i><?= htmlspecialchars($cont->getIdentificacao()) ?></span>
                                            <span class="parque-tag" style="font-size:0.7rem;"><?= $pct ?>%</span>
                                        </div>
                                        <div class="parque-subtitulo">
                                            <i class="bi bi-circle-fill" style="color:<?= $color ?>;font-size:0.5rem;"></i>
                                            <?= $label ?>
                                        </div>
                                    </div>
                                    <div class="parque-ocupacao-wrap">
                                        <div class="parque-pct-num <?= $css ?>"><?= $pct ?>%</div>
                                        <div class="parque-prog">
                                            <div class="parque-prog-bar <?= $css ?>" style="width:<?= $pct ?>%;"></div>
                                        </div>
                                        <div class="parque-lugares-label">Capacidade usada</div>
                                    </div>
                                    <div class="parque-actions d-flex gap-1 flex-wrap">
                                        <button class="btn btn-sm btn-outline-secondary btn-mapa-cont"
                                            data-lat="<?= $cont->getLatitude() ?>"
                                            data-lng="<?= $cont->getLongitude() ?>"
                                            data-bs-toggle="modal" data-bs-target="#modalMapaContentor">
                                            <i class="bi bi-geo-alt me-1"></i>Mapa
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                            <i class="bi bi-eye me-1"></i>Detalhes
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning btn-editar-cont"
                                            data-bs-toggle="modal" data-bs-target="#modalEditarContentor">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger btn-eliminar-cont"
                                            data-id="<?= $cont->getId() ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </section>
    </main>

<!-- ═══════════════════════════════════════════════════════════
     MODAL — CRIAR CONTENTOR
═══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalCriarContentor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Novo Contentor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/admin/create-contentor">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">ID do Contentor</label>
                            <input type="number" name="id" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ID Cidade</label>
                            <input type="number" name="id_cidade" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ID Estado</label>
                            <input type="number" name="id_estado" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Capacidade Máxima (L)</label>
                            <input type="number" name="capacidade_max" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Longitude</label>
                            <input type="text" name="longitude" class="form-control" placeholder="-9.1365" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Latitude</label>
                            <input type="text" name="latitude" class="form-control" placeholder="38.7169" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipo</label>
                            <input type="text" name="tipo" class="form-control" placeholder="Orgânico, Papel, etc." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Identificação</label>
                            <input type="text" name="identificacao" class="form-control" placeholder="Bucelas 001" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Observação</label>
                            <textarea name="observacao" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Criar Contentor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     MODAL — EDITAR CONTENTOR
═══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalEditarContentor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar Contentor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/admin/update-contentor">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-cont-id">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">ID Cidade</label>
                            <input type="number" name="id_cidade" id="edit-cont-id-cidade" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ID Estado</label>
                            <input type="number" name="id_estado" id="edit-cont-id-estado" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Capacidade Máxima (L)</label>
                            <input type="number" name="capacidade_max" id="edit-cont-capacidade" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Longitude</label>
                            <input type="text" name="longitude" id="edit-cont-longitude" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Latitude</label>
                            <input type="text" name="latitude" id="edit-cont-latitude" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tipo</label>
                            <input type="text" name="tipo" id="edit-cont-tipo" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Identificação</label>
                            <input type="text" name="identificacao" id="edit-cont-identificacao" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Observação</label>
                            <textarea name="observacao" id="edit-cont-observacao" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Guardar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     MODAL — MAPA
═══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalMapaContentor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                <h5 class="modal-title"><i class="bi bi-geo-alt me-2"></i>Localização do Contentor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div style="width:100%;height:420px;">
                    <iframe id="mapaContentorIframe" src="" width="100%" height="100%" style="border:0;" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     MODAL — DETALHES CONTENTOR
═══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalDetalheContentor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                <h5 class="modal-title" id="modalDetalheContentorTitle">Detalhes do Contentor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalDetalheContentorBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Offcanvas Informações Diárias -->
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
                <div><div style="font-size:0.75rem;color:#999;">Temperatura</div><div class="fw-bold">—</div></div>
            </div>
            <div class="list-group-item d-flex align-items-center gap-3">
                <span style="background:#e8edff;color:#435ebe;border-radius:10px;padding:8px 10px;font-size:1.2rem;"><i class="bi bi-droplet"></i></span>
                <div><div style="font-size:0.75rem;color:#999;">Humidade</div><div class="fw-bold">—</div></div>
            </div>
            <div class="list-group-item d-flex align-items-center gap-3">
                <span style="background:#e6f9ef;color:#27ae60;border-radius:10px;padding:8px 10px;font-size:1.2rem;"><i class="bi bi-wind"></i></span>
                <div><div style="font-size:0.75rem;color:#999;">Qualidade do Ar</div><div class="fw-bold">—</div></div>
            </div>
        </div>
    </div>
</div>

<script>
// ── Hora da última sincronização ─────────────────────────────────────────────
document.getElementById('cont-sync-time').textContent = new Date().toLocaleString('pt-PT');

// ── Filtros ───────────────────────────────────────────────────────────────────
function aplicarFiltroContentores() {
    const texto  = (document.getElementById('searchContentor')?.value || '').toLowerCase();
    const estado = document.getElementById('filtroContentorEstado')?.value || '';

    document.querySelectorAll('.parque-card').forEach(card => {
        const col   = card.closest('[data-id]');
        if (!col) return;
        const label = col.dataset.identificacao?.toLowerCase() || '';
        const cls   = card.classList.contains('critico') ? 'critico'
                    : card.classList.contains('atencao')  ? 'atencao' : 'normal';
        const matchText   = !texto  || label.includes(texto);
        const matchEstado = !estado || cls === estado;
        col.style.display = (matchText && matchEstado) ? '' : 'none';
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
    document.querySelectorAll('.collapse').forEach(c => {
        if (filtro.value) new bootstrap.Collapse(c, { show: true });
    });
});

// ── Modal Mapa ─────────────────────────────────────────────────────────────────
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-mapa-cont');
    if (!btn) return;
    const lat = btn.dataset.lat;
    const lng = btn.dataset.lng;
    document.getElementById('mapaContentorIframe').src =
        `https://www.google.com/maps?q=${lat},${lng}&z=16&output=embed`;
});

// ── Modal Detalhes ─────────────────────────────────────────────────────────────
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-detalhe-cont');
    if (!btn) return;
    const col  = btn.closest('[data-id]');
    const card = btn.closest('.parque-card');
    if (!col || !card) return;

    const nome      = col.dataset.identificacao || '—';
    const tipo      = col.dataset.tipo           || '—';
    const cap       = col.dataset.capacidadeMax  || '—';
    const lat       = col.dataset.latitude       || '—';
    const lng       = col.dataset.longitude      || '—';
    const obs       = col.dataset.observacao      || '—';
    const pct       = card.querySelector('.parque-pct-num')?.textContent?.trim() || '—';
    const isCrit    = card.classList.contains('critico');
    const isAtencao = card.classList.contains('atencao');
    const cls       = isCrit ? 'danger' : isAtencao ? 'warning' : 'success';
    const estado    = isCrit ? 'Crítico' : isAtencao ? 'Atenção' : 'Normal';

    document.getElementById('modalDetalheContentorTitle').textContent = nome + ' — Detalhes';
    document.getElementById('modalDetalheContentorBody').innerHTML = `
        <div class="text-center mb-3">
            <div class="pkpi-icon mx-auto mb-2" style="background:#f0f3ff;color:#435ebe;width:60px;height:60px;border-radius:14px;font-size:1.8rem;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-trash-fill"></i>
            </div>
            <div style="font-size:2.8rem;font-weight:800;" class="text-${cls}">${pct}</div>
            <div class="text-muted">capacidade utilizada</div>
        </div>
        <div class="parque-prog mb-3">
            <div class="parque-prog-bar ${isCrit ? 'critico' : isAtencao ? 'atencao' : 'normal'}" style="width:${pct}"></div>
        </div>
        <table class="table table-sm table-borderless" style="font-size:0.9rem;">
            <tr><td class="text-muted">Identificação</td><td><strong>${nome}</strong></td></tr>
            <tr><td class="text-muted">Tipo</td><td>${tipo}</td></tr>
            <tr><td class="text-muted">Capacidade Máx.</td><td>${cap} L</td></tr>
            <tr><td class="text-muted">Estado</td><td><span class="badge bg-${cls}">${estado}</span></td></tr>
            <tr><td class="text-muted">Coordenadas</td><td>${lat}, ${lng}</td></tr>
            <tr><td class="text-muted">Observação</td><td>${obs}</td></tr>
            <tr><td class="text-muted">Última leitura</td><td><strong>${new Date().toLocaleString('pt-PT')}</strong></td></tr>
        </table>`;
    new bootstrap.Modal(document.getElementById('modalDetalheContentor')).show();
});

// ── Modal Editar ──────────────────────────────────────────────────────────────
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-editar-cont');
    if (!btn) return;
    const col = btn.closest('[data-id]');
    if (!col) return;

    document.getElementById('edit-cont-id').value           = col.dataset.id;
    document.getElementById('edit-cont-id-cidade').value    = col.dataset.idCidade;
    document.getElementById('edit-cont-id-estado').value    = col.dataset.idEstado;
    document.getElementById('edit-cont-capacidade').value   = col.dataset.capacidadeMax;
    document.getElementById('edit-cont-longitude').value    = col.dataset.longitude;
    document.getElementById('edit-cont-latitude').value     = col.dataset.latitude;
    document.getElementById('edit-cont-tipo').value         = col.dataset.tipo;
    document.getElementById('edit-cont-identificacao').value = col.dataset.identificacao;
    document.getElementById('edit-cont-observacao').value   = col.dataset.observacao;
});

// ── Eliminar contentor (AJAX) ─────────────────────────────────────────────────
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-eliminar-cont');
    if (!btn) return;
    const id = btn.dataset.id;
    if (!confirm(`Tem a certeza que deseja eliminar o contentor #${id}?`)) return;

    fetch(`/admin/delete-contentor/${id}`, { method: 'POST' })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                btn.closest('[data-id]')?.remove();
            } else {
                alert('Erro: ' + data.message);
            }
        })
        .catch(() => alert('Erro de comunicação com o servidor.'));
});
</script>

<?php include __DIR__ . "/../../includes/footer.php"; ?>