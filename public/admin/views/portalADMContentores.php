<?php include __DIR__ . "/../../includes/header.php"; ?>
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .leaflet-container {
        font-family: inherit;
    }

    #mapaPickerSugestoes .list-group-item {
        cursor: pointer;
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
    }

    #mapaPickerSugestoes .list-group-item:hover {
        background: #e8edff;
        color: #435ebe;
    }

    .mapa-pin-pulse {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #435ebe;
        box-shadow: 0 0 0 0 rgba(67, 94, 190, 0.4);
        animation: mapaPulse 1.5s infinite;
    }

    @keyframes mapaPulse {
        0% {
            box-shadow: 0 0 0 0 rgba(67, 94, 190, 0.4);
        }

        70% {
            box-shadow: 0 0 0 12px rgba(67, 94, 190, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(67, 94, 190, 0);
        }
    }
</style>
<?php
$totalContentores = count($contentores);
$totalCriticos = 0;
$totalAtencao = 0;
$totalNormal = 0;
$porFreguesia = [];

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
    $FreguesiaId = $c->getIdFreguesia();
    if (!isset($porFreguesia[$FreguesiaId])) {
        $porFreguesia[$FreguesiaId] = ['contentores' => [], 'criticos' => 0];
    }
    $porFreguesia[$FreguesiaId]['contentores'][] = ['obj' => $c, 'estado' => $estadoKey];
    if ($estadoKey === 'critico')
        $porFreguesia[$FreguesiaId]['criticos']++;
}
$lastSync = date('d/m/Y H:i:s');

// Lookup rápido id → nome de freguesia
$fregMap = [];
foreach ($freguesias as $f) {
    $fregMap[$f->getId()] = $f->getNome();
}
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
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalCriarContentor">
                    <i class="bi bi-plus-lg me-1"></i>Adicionar Contentor
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
                        <div class="pkpi-value text-danger"><?= $numContentorPorEstado['contentores_criticos'] ?></div>
                        <div class="pkpi-label">Críticos (Cheios)</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="pkpi-card">
                    <div class="pkpi-icon" style="background:#fff4e0;color:#f39c12;"><i
                            class="bi bi-dash-circle-fill"></i></div>
                    <div>
                        <div class="pkpi-value text-warning"><?= $numContentorPorEstado['contentores_em_atencao'] ?>
                        </div>
                        <div class="pkpi-label">Em Atenção</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="pkpi-card">
                    <div class="pkpi-icon" style="background:#e6f9ef;color:#27ae60;"><i
                            class="bi bi-check-circle-fill"></i></div>
                    <div>
                        <div class="pkpi-value text-success"><?= $numContentorPorEstado['contentores_normais'] ?></div>
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
        <?php foreach ($porFreguesia as $FreguesiaId => $Freguesia):
            $collapseId = 'collapseC' . $FreguesiaId;
            $numCriticos = $Freguesia['criticos'];
            $numTotal = count($Freguesia['contentores']);
            ?>
            <div class="mb-3 secao-cidade">
                <button
                    class="btn btn-outline-primary w-100 text-start d-flex justify-content-between align-items-center py-3 px-4"
                    style="border-radius:10px;font-weight:600;background:white;border-color:#e6e9f0;"
                    data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>">
                    <span><i class="bi bi-geo-alt me-2" style="color:#435ebe;"></i>
                        <?= htmlspecialchars($fregMap[$FreguesiaId] ?? 'Freguesia ' . $FreguesiaId) ?></span>
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
                        <?php foreach ($Freguesia['contentores'] as $item):
                            $c = $item['obj'];
                            $estado = $item['estado'];
                            $pct = min((float) ($c->valor['percentagem'] ?? 0), 100);

                            if ($pct >= 80) {
                                $estadoVisual = 'critico';
                                $labelEstado = 'Crítico';
                                $corPonto = '#e74c3c';
                                $tagClass = 'ct';
                            } elseif ($pct >= 50) {
                                $estadoVisual = 'atencao';
                                $labelEstado = 'Atenção';
                                $corPonto = '#f39c12';
                                $tagClass = 'mr';
                            } else {
                                $estadoVisual = 'normal';
                                $labelEstado = 'Normal';
                                $corPonto = '#2ecc71';
                                $tagClass = 'ev';
                            }

                            $mMapaId = 'modalMapa_' . $c->getId();
                            $mEditId = 'modalEdit_' . $c->getId();
                            $mDeleteId = 'modalDelete_' . $c->getId();
                            ?>
                            <div class="col-12 col-md-6 col-lg-3 card-contentor" data-id="<?= $c->getId() ?>"
                                data-estado="<?= $estadoVisual ?>"
                                data-label="<?= strtolower(htmlspecialchars($c->getIdentificacao())) ?>">
                                <div class="parque-card <?= $estadoVisual ?>">
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
                                        <?php $pct = min((float) ($c->valor['percentagem'] ?? 0), 100); ?>
                                        <div class="parque-pct-num <?= $estadoVisual ?>"><?= $pct ?>%</div>
                                        <div class="parque-prog">
                                            <div class="parque-prog-bar <?= $estadoVisual ?>" style="width:<?= $pct ?>%"></div>
                                        </div>
                                        <div class="parque-lugares-label">
                                            <?php if ($c->valor): ?>
                                                <?= number_format($c->valor['valor'], 1) ?>L / <?= $c->getCapacidadeMax() ?>L
                                                &nbsp;·&nbsp;
                                                <span class="text-muted"
                                                    style="font-size:0.75rem;"><?= date('d/m H:i', strtotime($c->valor['data_leitura'])) ?></span>
                                            <?php else: ?>
                                                Sem leituras &nbsp;·&nbsp; Cap. máx.: <?= $c->getCapacidadeMax() ?>L
                                            <?php endif; ?>
                                            &nbsp;·&nbsp; <?= htmlspecialchars($c->getTipo()) ?>
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
                                            <i class="bi bi-trash me-1"></i>Eliminar
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
                                                <?= htmlspecialchars($c->getIdentificacao()) ?>
                                            </h5>
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
                                                        <label class="form-label fw-semibold">Freguesia</label>
                                                        <select name="id_freguesia" class="form-select" required>
                                                            <?php foreach ($freguesias as $freg): ?>
                                                                <option value="<?= $freg->getId() ?>"
                                                                    <?= $c->getIdFreguesia() === $freg->getId() ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($freg->getNome()) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-semibold">Estado</label>
                                                        <select name="id_estado" class="form-select" required>
                                                            <?php foreach ($estados as $estadoOpt): ?>
                                                                <option value="<?= $estadoOpt->getId() ?>"
                                                                    <?= $c->getIdEstado() === $estadoOpt->getId() ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars(ucfirst($estadoOpt->getNome())) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-semibold">Capacidade Máxima (L)</label>
                                                        <input type="number" name="capacidade_max" class="form-control"
                                                            value="<?= $c->getCapacidadeMax() ?>" required>
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
                                                        <label class="form-label fw-semibold">Latitude</label>
                                                        <input type="text" name="latitude" class="form-control"
                                                            value="<?= $c->getLatitude() ?>" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-semibold">Longitude</label>
                                                        <input type="text" name="longitude" class="form-control"
                                                            value="<?= $c->getLongitude() ?>" required>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Nova Observação</label>
                                                        <textarea name="observacao" class="form-control" rows="2"
                                                            placeholder="Adicionar nova observação..."></textarea>
                                                        <?php if (!empty($c->getObservacoes())): ?>
                                                            <div class="mt-2" style="max-height:100px;overflow-y:auto;">
                                                                <?php foreach (array_reverse($c->getObservacoes()) as $obs): ?>
                                                                    <p class="mb-1 text-muted" style="font-size:0.78rem;">
                                                                        <?= htmlspecialchars($obs->getTexto()) ?>
                                                                        <small
                                                                            class="text-muted d-block"><?= $obs->getCreatedAt() ?></small>
                                                                    </p>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-floppy me-1"></i>Guardar</button>
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
                                            <h5 class="modal-title text-danger">
                                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Eliminar
                                            </h5>
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
                                <label class="form-label fw-semibold">Freguesia</label>
                                <select name="id_freguesia" id="criar_id_freguesia" class="form-select" required>
                                    <option value="">-- Selecionar Freguesia --</option>
                                    <?php foreach ($freguesias as $freg): ?>
                                        <option value="<?= $freg->getId() ?>">
                                            <?= htmlspecialchars($freg->getNome()) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Capacidade Máxima (L)</label>
                                <input type="number" name="capacidade_max" class="form-control" placeholder="Ex: 1000" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Localização</label>
                                <input type="hidden" name="latitude" id="criar_latitude" required>
                                <input type="hidden" name="longitude" id="criar_longitude" required>
                                <div id="criar_localizacao_preview" class="d-none mb-2 p-2 rounded"
                                    style="background:#f0f4ff;font-size:0.82rem;color:#435ebe;">
                                    <i class="bi bi-geo-alt-fill me-1"></i>
                                    <span id="criar_localizacao_texto"></span>
                                </div>
                                <button type="button" class="btn btn-outline-primary w-100" id="btnAbrirMapaCriar">
                                    <i class="bi bi-map me-2"></i>Escolher Localização no Mapa
                                </button>
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

    <!-- MODAL MAPA PICKER -->
    <div class="modal fade" id="modalMapaPicker" tabindex="-1" style="z-index:1060;">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                    <h5 class="modal-title"><i class="bi bi-map me-2"></i>Escolher Localização do Contentor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0" style="overflow:visible;">
                    <!-- Barra de pesquisa -->
                    <div class="p-3 border-bottom" style="background:#f8f9fa;">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="mapaPickerSearch" class="form-control"
                                placeholder="Pesquisar rua, localidade... (ex: Rua da Liberdade, Lisboa)">
                            <button class="btn btn-primary" id="btnMapaPickerSearch" type="button">
                                <i class="bi bi-search"></i> Pesquisar
                            </button>
                        </div>
                        <div id="mapaPickerSugestoes" class="list-group mt-1 position-absolute"
                            style="z-index:9999;max-width:600px;display:none;"></div>
                    </div>
                    <!-- Info do ponto selecionado -->
                    <div id="mapaPickerInfo" class="px-3 py-2 border-bottom d-none"
                        style="background:#e8edff;font-size:0.85rem;color:#435ebe;">
                        <i class="bi bi-geo-alt-fill me-1"></i>
                        <span id="mapaPickerInfoTexto"></span>
                        <span class="ms-3 text-muted" id="mapaPickerCoords"></span>
                    </div>
                    <!-- Mapa -->
                    <div id="mapaPickerContainer" style="width:100%;height:350px;"></div>
                </div>
                <div class="modal-footer">
                    <small class="text-muted me-auto"><i class="bi bi-info-circle me-1"></i>Clique no mapa para
                        posicionar o contentor. A freguesia será detetada automaticamente.</small>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success btn-sm" id="btnMapaPickerConfirmar" disabled>
                        <i class="bi bi-check-lg me-1"></i>Confirmar Localização
                    </button>
                </div>
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
            const modalEl = document.getElementById(modalId);
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            try {
                const res = await fetch('/admin/delete-contentor/' + id, { method: 'POST' });
                const data = await res.json();
                if (data.success) {
                    bootstrap.Modal.getInstance(modalEl)?.hide();
                    modalEl.addEventListener('hidden.bs.modal', function onHidden() {
                        modalEl.removeEventListener('hidden.bs.modal', onHidden);
                        // Remover o card da secção
                        const card = document.querySelector(`.card-contentor[data-id="${id}"]`)
                            ?? modalEl.closest('.card-contentor');
                        card?.remove();
                        showToast(data.message, 'success');
                    });
                } else {
                    showToast(data.message, 'error');
                    this.disabled = false;
                    this.innerHTML = '<i class="bi bi-trash me-1"></i>Eliminar';
                }
            } catch (e) {
                showToast('Erro de comunicação.', 'error');
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-trash me-1"></i>Eliminar';
            }
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

<!-- Leaflet JS + Mapa Picker -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
        (function () {
            // ── Estado do picker ──────────────────────────────────────────────────────
            let mapaPickerInstance = null;
            let mapaPickerMarker = null;
            let mapaPickerSel = { lat: null, lng: null, morada: '', id_freguesia: null };
            let searchTimeout = null;

            // ── Abrir modal do mapa ───────────────────────────────────────────────────
            document.getElementById('btnAbrirMapaCriar')?.addEventListener('click', function () {
                // Fechar o modal de criar antes de abrir o mapa
                bootstrap.Modal.getInstance(document.getElementById('modalCriarContentor'))?.hide();

                setTimeout(() => {
                    const modalPicker = new bootstrap.Modal(document.getElementById('modalMapaPicker'));
                    modalPicker.show();

                    document.getElementById('modalMapaPicker').addEventListener('shown.bs.modal', function initMapa() {
                        this.removeEventListener('shown.bs.modal', initMapa);

                        if (!mapaPickerInstance) {
                            // Centro padrão: Lisboa
                            mapaPickerInstance = L.map('mapaPickerContainer').setView([38.7169, -9.1399], 12);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                                maxZoom: 19
                            }).addTo(mapaPickerInstance);

                            mapaPickerInstance.on('click', onMapaClick);
                        } else {
                            mapaPickerInstance.invalidateSize();
                        }
                    });
                }, 350);
            });

            // ── Clique no mapa ────────────────────────────────────────────────────────
            async function onMapaClick(e) {
                const { lat, lng } = e.latlng;

                // Mover/criar marcador
                if (mapaPickerMarker) {
                    mapaPickerMarker.setLatLng([lat, lng]);
                } else {
                    const icon = L.divIcon({
                        className: '',
                        html: '<div class="mapa-pin-pulse"></div>',
                        iconSize: [16, 16],
                        iconAnchor: [8, 8]
                    });
                    mapaPickerMarker = L.marker([lat, lng], { icon, draggable: true }).addTo(mapaPickerInstance);
                    mapaPickerMarker.on('dragend', function (ev) {
                        const pos = ev.target.getLatLng();
                        onMapaClick({ latlng: pos });
                    });
                }

                // Reverse geocoding via Nominatim
                try {
                    const r = await fetch(
                        `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&accept-language=pt`,
                        { headers: { 'Accept-Language': 'pt' } }
                    );
                    const geo = await r.json();

                    // Tentar extrair nome de freguesia do reverse geocoding
                    const addr = geo.address || {};
                    const morada = [addr.road, addr.suburb, addr.city_district, addr.town || addr.city || addr.municipality]
                        .filter(Boolean).join(', ');

                    // Candidatos a nome de freguesia (do mais específico para o menos)
                    const fregNomeCandidatos = [
                        addr.suburb,
                        addr.city_district,
                        addr.quarter,
                        addr.neighbourhood,
                        addr.village,
                        addr.town,
                        addr.city,
                        addr.municipality
                    ].filter(Boolean);

                    mapaPickerSel = { lat: lat.toFixed(6), lng: lng.toFixed(6), morada, fregNomeCandidatos };

                    // Atualizar UI
                    document.getElementById('mapaPickerInfoTexto').textContent = morada || 'Localização selecionada';
                    document.getElementById('mapaPickerCoords').textContent = `Lat: ${mapaPickerSel.lat} | Lng: ${mapaPickerSel.lng}`;
                    document.getElementById('mapaPickerInfo').classList.remove('d-none');
                    document.getElementById('btnMapaPickerConfirmar').disabled = false;

                    // Popup no marcador
                    mapaPickerMarker.bindPopup(`<strong>${morada || 'Local selecionado'}</strong><br><small>Lat: ${mapaPickerSel.lat} | Lng: ${mapaPickerSel.lng}</small>`).openPopup();

                } catch (err) {
                    mapaPickerSel = { lat: lat.toFixed(6), lng: lng.toFixed(6), morada: 'Local selecionado', fregNomeCandidatos: [] };
                    document.getElementById('mapaPickerInfoTexto').textContent = 'Local selecionado';
                    document.getElementById('mapaPickerCoords').textContent = `Lat: ${mapaPickerSel.lat} | Lng: ${mapaPickerSel.lng}`;
                    document.getElementById('mapaPickerInfo').classList.remove('d-none');
                    document.getElementById('btnMapaPickerConfirmar').disabled = false;
                }
            }

            // ── Pesquisa de rua ───────────────────────────────────────────────────────
            const searchInput = document.getElementById('mapaPickerSearch');
            const sugestoesBox = document.getElementById('mapaPickerSugestoes');

            searchInput?.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                const q = this.value.trim();
                if (q.length < 3) { sugestoesBox.style.display = 'none'; return; }
                searchTimeout = setTimeout(() => pesquisarRua(q), 400);
            });

            document.getElementById('btnMapaPickerSearch')?.addEventListener('click', function () {
                const q = searchInput.value.trim();
                if (q.length >= 3) pesquisarRua(q);
            });

            searchInput?.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); pesquisarRua(this.value.trim()); }
            });

            async function pesquisarRua(q) {
                try {
                    const r = await fetch(
                        `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(q)}&format=json&limit=5&addressdetails=1&accept-language=pt`,
                        { headers: { 'Accept-Language': 'pt' } }
                    );
                    const resultados = await r.json();
                    sugestoesBox.innerHTML = '';

                    if (!resultados.length) {
                        sugestoesBox.innerHTML = '<div class="list-group-item text-muted">Nenhum resultado encontrado.</div>';
                        sugestoesBox.style.display = 'block';
                        return;
                    }

                    resultados.forEach(res => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action';
                        item.textContent = res.display_name;
                        item.addEventListener('click', function () {
                            sugestoesBox.style.display = 'none';
                            searchInput.value = res.display_name;
                            const lat = parseFloat(res.lat);
                            const lng = parseFloat(res.lon);
                            mapaPickerInstance.setView([lat, lng], 17);
                            onMapaClick({ latlng: { lat, lng } });
                        });
                        sugestoesBox.appendChild(item);
                    });

                    sugestoesBox.style.display = 'block';
                } catch (e) {
                    sugestoesBox.style.display = 'none';
                }
            }

            // Fechar sugestões ao clicar fora
            document.addEventListener('click', function (e) {
                if (!sugestoesBox?.contains(e.target) && e.target !== searchInput) {
                    if (sugestoesBox) sugestoesBox.style.display = 'none';
                }
            });

            // ── Confirmar localização ─────────────────────────────────────────────────
            document.getElementById('btnMapaPickerConfirmar')?.addEventListener('click', function () {
                if (!mapaPickerSel.lat) return;

                // Preencher lat/lng
                document.getElementById('criar_latitude').value = mapaPickerSel.lat;
                document.getElementById('criar_longitude').value = mapaPickerSel.lng;

                // Tentar fazer match da freguesia no dropdown por nome (comparação parcial, case-insensitive)
                const select = document.getElementById('criar_id_freguesia');
                const opcoes = Array.from(select.options);
                let matched = false;

                if (mapaPickerSel.fregNomeCandidatos && mapaPickerSel.fregNomeCandidatos.length) {
                    for (const candidato of mapaPickerSel.fregNomeCandidatos) {
                        const candLower = candidato.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                        // 1ª passagem: match exato
                        const exact = opcoes.find(o =>
                            o.text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '') === candLower
                        );
                        if (exact) { select.value = exact.value; matched = true; break; }
                        // 2ª passagem: match parcial
                        const partial = opcoes.find(o =>
                            o.text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').includes(candLower) ||
                            candLower.includes(o.text.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, ''))
                        );
                        if (partial) { select.value = partial.value; matched = true; break; }
                    }
                }

                // Mostrar preview com info do match
                const fregNome = matched ? select.options[select.selectedIndex].text : null;
                const previewTexto = mapaPickerSel.morada +
                    (fregNome ? ` · Freguesia: ${fregNome}` : ' · Seleciona a freguesia manualmente') +
                    ` (Lat: ${mapaPickerSel.lat}, Lng: ${mapaPickerSel.lng})`;

                document.getElementById('criar_localizacao_texto').textContent = previewTexto;
                document.getElementById('criar_localizacao_preview').classList.remove('d-none');
                document.getElementById('criar_localizacao_preview').style.background = matched ? '#e8f5e9' : '#fff8e1';
                document.getElementById('criar_localizacao_preview').style.color = matched ? '#2e7d32' : '#e65100';

                // Atualizar botão
                document.getElementById('btnAbrirMapaCriar').innerHTML =
                    '<i class="bi bi-geo-alt-fill me-2 text-success"></i>Localização definida — clique para alterar';
                document.getElementById('btnAbrirMapaCriar').classList.replace('btn-outline-primary', 'btn-outline-success');

                // Fechar picker e reabrir criar
                bootstrap.Modal.getInstance(document.getElementById('modalMapaPicker'))?.hide();
                setTimeout(() => {
                    new bootstrap.Modal(document.getElementById('modalCriarContentor')).show();
                }, 300);
            });

            // Ao fechar o mapa sem confirmar, reabrir o modal de criar
            document.getElementById('modalMapaPicker')?.addEventListener('hidden.bs.modal', function () {
                // Só reabre se não foi via confirmar (se já tem lat preenchido)
                if (!document.getElementById('criar_latitude').value &&
                    !document.getElementById('btnMapaPickerConfirmar').disabled) {
                    // Utilizador cancelou sem confirmar
                }
            });

        })();
</script>