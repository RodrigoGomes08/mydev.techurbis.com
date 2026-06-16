<?php include __DIR__ . "/../../includes/header.php"; ?>

<?php
// ── Calcular KPIs a partir dos dados reais ────────────────────────────────────
$total = count($parques);
$cobertos = 0;
$subterraneos = 0;
$descobertos = 0;
$totalLugares = 0;

// Agrupar parques por freguesia
$grupos = [];
foreach ($parques as $p) {
    $FreguesiaKey = $p->getIdFreguesia();
    $grupos[$FreguesiaKey][] = $p;

    $totalLugares += $p->getNumMaxLugares();

    switch ($p->getTipo()) {
        case 'Coberto':
            $cobertos++;
            break;
        case 'Subterrâneo':
            $subterraneos++;
            break;
        default:
            $descobertos++;
            break;
    }
}

// Helper: ícone por tipo
function tipoIcon(string $tipo): string
{
    return match ($tipo) {
        'Coberto' => 'bi-building',
        'Subterrâneo' => 'bi-layers-fill',
        default => 'bi-sun',
    };
}
?>

<main class="content-admin">
    <section id="parques" class="adm-page active">

        <!-- CABEÇALHO -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="mb-1">Parques de Estacionamento</h2>
                <p class="text-muted mb-0" style="font-size:0.9rem;">
                    <i class="bi bi-clock me-1"></i>Última sincronização: <span id="parque-sync-time">—</span>
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-outline-danger btn-sm" id="btnParquesCriticos">
                    <i class="bi bi-exclamation-triangle me-1"></i>Só Críticos
                </button>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalCriarParque">
                    <i class="bi bi-plus-circle me-1"></i>Adicionar Parque
                </button>
            </div>
        </div>

        <!-- KPIs dinâmicos -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="pkpi-card">
                    <div class="pkpi-icon" style="background:#e8edff;color:#435ebe;"><i class="bi bi-p-circle-fill"></i>
                    </div>
                    <div>
                        <div class="pkpi-value"><?= $total ?></div>
                        <div class="pkpi-label">Total de Parques</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="pkpi-card">
                    <div class="pkpi-icon" style="background:#e6f9ef;color:#27ae60;"><i
                            class="bi bi-car-front-fill"></i></div>
                    <div>
                        <div class="pkpi-value text-success"><?= $totalLugares ?></div>
                        <div class="pkpi-label">Lugares Totais</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="pkpi-card">
                    <div class="pkpi-icon" style="background:#fff4e0;color:#f39c12;"><i class="bi bi-building"></i>
                    </div>
                    <div>
                        <div class="pkpi-value text-warning"><?= $cobertos ?></div>
                        <div class="pkpi-label">Cobertos</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="pkpi-card">
                    <div class="pkpi-icon" style="background:#fde8e8;color:#e74c3c;"><i class="bi bi-layers-fill"></i>
                    </div>
                    <div>
                        <div class="pkpi-value text-danger"><?= $subterraneos ?></div>
                        <div class="pkpi-label">Subterrâneos</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PESQUISA / FILTROS -->
        <div class="d-flex gap-2 mb-4 flex-wrap">
            <div class="input-group" style="max-width:300px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" id="searchParque" class="form-control border-start-0 ps-0"
                    placeholder="Pesquisar parque...">
            </div>
            <select id="filtroParqueTipo" class="form-select" style="max-width:180px;">
                <option value="">Todos os tipos</option>
                <option value="Coberto">Coberto</option>
                <option value="Descoberto">Descoberto</option>
                <option value="Subterrâneo">Subterrâneo</option>
            </select>
        </div>

        <!-- SECÇÕES AGRUPADAS POR FREGUESIA -->
        <?php if (empty($parques)): ?>
            <div class="alert alert-info">Nenhum parque registado.</div>
        <?php else: ?>
            <?php foreach ($grupos as $freguesiaId => $lista):
                $collapseId = 'collapseParqueFreguesia' . $freguesiaId;
                $nomeFreguesia = isset($freguesias[$freguesiaId]) ? $freguesias[$freguesiaId] : "Freguesia {$freguesiaId}";
                $lugaresGrupo = array_sum(array_map(fn($p) => $p->getNumMaxLugares(), $lista));
                ?>
                <div class="mb-3 grupo-cidade">
                    <button
                        class="btn btn-outline-primary w-100 text-start d-flex justify-content-between align-items-center py-3 px-4"
                        style="border-radius:10px;font-weight:600;background:white;border-color:#e6e9f0;"
                        data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>">
                        <span><i class="bi bi-geo-alt me-2"
                                style="color:#435ebe;"></i><?= htmlspecialchars($nomeFreguesia) ?></span>
                        <span class="d-flex align-items-center gap-2">
                            <span class="badge" style="background:#e6f9ef;color:#27ae60;font-size:0.75rem;"><?= $lugaresGrupo ?>
                                lugares</span>
                            <span class="badge" style="background:#e8edff;color:#435ebe;font-size:0.75rem;"><?= count($lista) ?>
                                parque<?= count($lista) > 1 ? 's' : '' ?></span>
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </button>

                    <div class="collapse show" id="<?= $collapseId ?>">
                        <div class="row g-3 mt-1">
                            <?php foreach ($lista as $parque):
                                $tipoIcon = tipoIcon($parque->getTipo());
                                $tarifaStr = $parque->getTarifa() == 0 ? 'Gratuito' : number_format($parque->getTarifa(), 2) . ' €/h';
                                ?>
                                <div class="col-12 col-md-6 col-lg-3" data-id="<?= $parque->getId() ?>"
                                    data-id-freguesia="<?= $parque->getIdFreguesia() ?>"
                                    data-nome="<?= htmlspecialchars($parque->getNome()) ?>"
                                    data-num-max-lugares="<?= $parque->getNumMaxLugares() ?>"
                                    data-tipo="<?= htmlspecialchars($parque->getTipo()) ?>"
                                    data-tarifa="<?= $parque->getTarifa() ?>" data-longitude="<?= $parque->getLongitude() ?>"
                                    data-latitude="<?= $parque->getLatitude() ?>">
                                    <?php
                                    $maxLugares = $parque->getNumMaxLugares();
                                    // Sem dados de ocupação real, assumir 0 ocupados (a lógica JS trata do resto)
                                    $pct = 0;
                                    $estado = 'normal';
                                    ?>
                                    <div class="parque-card <?= $estado ?>">
                                        <div class="parque-card-header">
                                            <div class="parque-titulo">
                                                <span><i
                                                        class="bi <?= $tipoIcon ?> me-1"></i><?= htmlspecialchars($parque->getNome()) ?></span>
                                                <span class="badge bg-secondary"
                                                    style="font-size:0.68rem;font-weight:600;"><?= htmlspecialchars($parque->getTipo()) ?></span>
                                            </div>
                                            <div class="parque-subtitulo">
                                                <i class="bi bi-geo-alt" style="color:#435ebe;font-size:0.7rem;"></i>
                                                <?= $parque->getNumMaxLugares() ?> lugares · <?= $tarifaStr ?>
                                            </div>
                                        </div>
                                        <div class="parque-ocupacao-wrap">
                                            <div class="d-flex justify-content-between align-items-baseline">
                                                <div class="parque-pct-num <?= $estado ?>"><?= $maxLugares ?></div>
                                                <div style="font-size:0.78rem;color:#aaa;">lugares totais</div>
                                            </div>
                                            <div class="parque-prog">
                                                <div class="parque-prog-bar <?= $estado ?>" style="width:<?= $pct ?>%"></div>
                                            </div>
                                            <div class="parque-lugares-label"><?= $maxLugares ?> lugares disponíveis</div>
                                        </div>
                                        <div class="parque-tags">
                                            <span class="parque-tag tipo"><i
                                                    class="bi <?= $tipoIcon ?>"></i><?= htmlspecialchars($parque->getTipo()) ?></span>
                                            <?php if ($parque->getTarifa() == 0): ?>
                                                <span class="parque-tag ev"><i class="bi bi-check-circle"></i>Gratuito</span>
                                            <?php else: ?>
                                                <span class="parque-tag mr"><i
                                                        class="bi bi-currency-euro"></i><?= number_format($parque->getTarifa(), 2) ?>/h</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="parque-info-grid">
                                            <div class="parque-info-item">
                                                <span class="parque-info-label">Latitude</span>
                                                <span class="parque-info-value"><?= $parque->getLatitude() ?></span>
                                            </div>
                                            <div class="parque-info-item">
                                                <span class="parque-info-label">Longitude</span>
                                                <span class="parque-info-value"><?= $parque->getLongitude() ?></span>
                                            </div>
                                        </div>
                                        <div class="parque-actions">
                                            <button class="btn btn-sm btn-outline-secondary btn-mapa-parque"
                                                data-lat="<?= $parque->getLatitude() ?>" data-lng="<?= $parque->getLongitude() ?>"
                                                data-nome="<?= htmlspecialchars($parque->getNome()) ?>" data-bs-toggle="modal"
                                                data-bs-target="#modalMapaParque">
                                                <i class="bi bi-geo-alt me-1"></i>Mapa
                                            </button>
                                            <button class="btn btn-sm btn-outline-primary btn-detalhe-parque">
                                                <i class="bi bi-eye me-1"></i>Detalhes
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning btn-editar-parque" data-bs-toggle="modal"
                                                data-bs-target="#modalEditarParque">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger btn-eliminar-parque"
                                                data-id="<?= $parque->getId() ?>">
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
    MODAL — CRIAR PARQUE
═══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalCriarParque" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Adicionar Parque</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/admin/create-parque">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nome do Parque</label>
                            <input type="text" name="nome" class="form-control" placeholder="Ex: Parque Norte" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">ID Cidade</label>
                            <input type="number" name="id_cidade" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nº Máx. Lugares</label>
                            <input type="number" name="num_max_lugares" class="form-control" placeholder="Ex: 200"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tipo</label>
                            <select name="tipo" class="form-select" required>
                                <option value="Coberto">Coberto</option>
                                <option value="Descoberto">Descoberto</option>
                                <option value="Subterrâneo">Subterrâneo</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tarifa (€/hora)</label>
                            <input type="number" step="0.10" name="tarifa" class="form-control"
                                placeholder="0 = Gratuito">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Latitude</label>
                            <input type="text" name="latitude" class="form-control" placeholder="38.7169" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Longitude</label>
                            <input type="text" name="longitude" class="form-control" placeholder="-9.1365" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i
                            class="bi bi-x-lg me-1"></i>Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i>Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
    MODAL — EDITAR PARQUE
═══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalEditarParque" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Editar Parque</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/admin/update-parque">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-parque-id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">ID Cidade</label>
                            <input type="number" name="id_cidade" id="edit-parque-id-cidade" class="form-control"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nome do Parque</label>
                            <input type="text" name="nome" id="edit-parque-nome" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nº Máx. Lugares</label>
                            <input type="number" name="num_max_lugares" id="edit-parque-num-max-lugares"
                                class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tipo</label>
                            <select name="tipo" id="edit-parque-tipo" class="form-select" required>
                                <option value="Coberto">Coberto</option>
                                <option value="Descoberto">Descoberto</option>
                                <option value="Subterrâneo">Subterrâneo</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tarifa (€/hora)</label>
                            <input type="number" step="0.10" name="tarifa" id="edit-parque-tarifa" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Latitude</label>
                            <input type="text" name="latitude" id="edit-parque-latitude" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Longitude</label>
                            <input type="text" name="longitude" id="edit-parque-longitude" class="form-control"
                                required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i
                            class="bi bi-x-lg me-1"></i>Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i>Guardar
                        Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     MODAL — MAPA
═══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalMapaParque" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                <h5 class="modal-title" id="modalMapaParqueTitle"><i class="bi bi-geo-alt me-2"></i>Localização do
                    Parque</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div style="width:100%;height:420px;">
                    <iframe id="mapaParqueIframe" src="" width="100%" height="100%" style="border:0;"
                        loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     MODAL — DETALHES PARQUE
═══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalDetalheParque" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                <h5 class="modal-title" id="modalDetalheParqueTitle">Detalhes do Parque</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalDetalheParqueBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i
                        class="bi bi-x-lg me-1"></i>Fechar</button>
            </div>
        </div>
    </div>
</div>


<?php include __DIR__ . "/../../includes/footer.php"; ?>