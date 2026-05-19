<?php include __DIR__ . "/../../includes/header.php"; ?>

    <main class="content-admin">

        <!-- ========================= PARQUES DE ESTACIONAMENTO ========================= -->
        <section id="parques" class="adm-page active">

            <!-- CABEÇALHO -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h2 class="mb-1">Parques de Estacionamento</h2>
                    <p class="text-muted mb-0" style="font-size:0.9rem;">
                        <i class="bi bi-clock me-1"></i>Total de registos: <strong><?= count($parques) ?></strong>
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalNovoParque">
                        <i class="bi bi-plus-circle me-1"></i>Adicionar Parque
                    </button>
                </div>
            </div>

            <!-- KPIs GLOBAIS -->
            <?php
                $total     = count($parques);
                $totalLugares = array_sum(array_map(fn($p) => $p->getNumMaxLugares(), $parques));
            ?>
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#e8edff;color:#435ebe;">
                            <i class="bi bi-p-circle-fill"></i>
                        </div>
                        <div>
                            <div class="pkpi-value" id="pkpi-total"><?= $total ?></div>
                            <div class="pkpi-label">Total de Parques</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#e6f9ef;color:#27ae60;">
                            <i class="bi bi-car-front-fill"></i>
                        </div>
                        <div>
                            <div class="pkpi-value text-success"><?= $totalLugares ?></div>
                            <div class="pkpi-label">Lugares Totais</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#fff4e0;color:#f39c12;">
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <div class="pkpi-value text-warning">
                                <?= count(array_filter($parques, fn($p) => $p->getTipo() === 'Coberto')) ?>
                            </div>
                            <div class="pkpi-label">Cobertos</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#fde8e8;color:#e74c3c;">
                            <i class="bi bi-currency-euro"></i>
                        </div>
                        <div>
                            <div class="pkpi-value text-danger">
                                <?= count(array_filter($parques, fn($p) => $p->getTarifa() == 0)) ?>
                            </div>
                            <div class="pkpi-label">Gratuitos</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FILTRO / PESQUISA -->
            <div class="d-flex gap-2 mb-3 flex-wrap">
                <div class="input-group" style="max-width:300px;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
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

            <!-- TABELA DE PARQUES -->
            <div class="table-responsive">
                <table class="table table-admin table-hover align-middle" id="tabelaParques">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Lugares</th>
                            <th>Tarifa</th>
                            <th>Localização</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($parques)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-p-circle fs-3 d-block mb-2"></i>
                                    Nenhum parque registado.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($parques as $parque): ?>
                                <tr data-nome="<?= strtolower(htmlspecialchars($parque->getNome())) ?>"
                                    data-tipo="<?= htmlspecialchars($parque->getTipo()) ?>">
                                    <td><span class="badge bg-secondary">#<?= $parque->getId() ?></span></td>
                                    <td>
                                        <strong><?= htmlspecialchars($parque->getNome()) ?></strong>
                                    </td>
                                    <td>
                                        <?php
                                            $tipo = $parque->getTipo();
                                            $icon = match($tipo) {
                                                'Coberto'     => 'bi-building',
                                                'Subterrâneo' => 'bi-layers-fill',
                                                default       => 'bi-sun'
                                            };
                                        ?>
                                        <i class="bi <?= $icon ?> me-1"></i><?= htmlspecialchars($tipo) ?>
                                    </td>
                                    <td><?= $parque->getNumMaxLugares() ?> lugares</td>
                                    <td>
                                        <?php if ($parque->getTarifa() == 0): ?>
                                            <span class="badge bg-success">Gratuito</span>
                                        <?php else: ?>
                                            <?= number_format($parque->getTarifa(), 2) ?> €/h
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            <?= number_format((float)$parque->getLatitude(), 5) ?>,
                                            <?= number_format((float)$parque->getLongitude(), 5) ?>
                                        </small>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end">
                                            <!-- Botão Mapa -->
                                            <button class="btn btn-sm btn-outline-secondary btn-parque-mapa"
                                                data-lat="<?= $parque->getLatitude() ?>"
                                                data-lng="<?= $parque->getLongitude() ?>"
                                                data-nome="<?= htmlspecialchars($parque->getNome()) ?>"
                                                title="Ver no mapa">
                                                <i class="bi bi-map"></i>
                                            </button>
                                            <!-- Botão Editar -->
                                            <button class="btn btn-sm btn-outline-warning btn-editar-parque"
                                                data-id="<?= $parque->getId() ?>"
                                                data-id-cidade="<?= $parque->getIdCidade() ?>"
                                                data-nome="<?= htmlspecialchars($parque->getNome()) ?>"
                                                data-num-max-lugares="<?= $parque->getNumMaxLugares() ?>"
                                                data-tipo="<?= htmlspecialchars($parque->getTipo()) ?>"
                                                data-tarifa="<?= $parque->getTarifa() ?>"
                                                data-longitude="<?= $parque->getLongitude() ?>"
                                                data-latitude="<?= $parque->getLatitude() ?>"
                                                title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <!-- Botão Eliminar -->
                                            <button class="btn btn-sm btn-outline-danger btn-eliminar-parque"
                                                data-id="<?= $parque->getId() ?>"
                                                data-nome="<?= htmlspecialchars($parque->getNome()) ?>"
                                                title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </section>

        <!-- ===== MODAL MAPA PARQUE ===== -->
        <div class="modal fade" id="modalMapaParque" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalMapaParqueTitle">Localização</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div style="width:100%;height:420px;">
                            <iframe id="iframeMapaParque" src="" width="100%" height="100%" style="border:0;" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== MODAL ADICIONAR PARQUE ===== -->
        <div class="modal fade" id="modalNovoParque" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                        <h5 class="modal-title">Adicionar Parque</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="/admin/create-parque" method="POST">
                        <div class="modal-body">
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">ID</label>
                                    <input type="number" class="form-control" name="id" placeholder="Ex: 10" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">ID Cidade</label>
                                    <input type="number" class="form-control" name="id_cidade" placeholder="Ex: 1" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nome do Parque</label>
                                <input type="text" class="form-control" name="nome" placeholder="Ex: Parque Sul" required>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Nº Máx. Lugares</label>
                                    <input type="number" class="form-control" name="num_max_lugares" placeholder="Ex: 200" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Tipo</label>
                                    <select class="form-select" name="tipo" required>
                                        <option value="Coberto">Coberto</option>
                                        <option value="Descoberto">Descoberto</option>
                                        <option value="Subterrâneo">Subterrâneo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tarifa (€/hora)</label>
                                <input type="number" step="0.10" class="form-control" name="tarifa" placeholder="Ex: 1.50 (0 = Gratuito)">
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Latitude</label>
                                    <input type="number" step="any" class="form-control" name="latitude" placeholder="Ex: 38.83" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Longitude</label>
                                    <input type="number" step="any" class="form-control" name="longitude" placeholder="Ex: -9.17" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg me-1"></i>Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-floppy me-1"></i>Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ===== MODAL EDITAR PARQUE ===== -->
        <div class="modal fade" id="modalEditarParque" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                        <h5 class="modal-title" id="modalEditarParqueLabel">Editar Parque</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="/admin/update-parque" method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit_parque_id">
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">ID</label>
                                    <input type="number" class="form-control" id="edit_parque_id_display" disabled>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">ID Cidade</label>
                                    <input type="number" class="form-control" name="id_cidade" id="edit_parque_id_cidade" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nome do Parque</label>
                                <input type="text" class="form-control" name="nome" id="edit_parque_nome" required>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Nº Máx. Lugares</label>
                                    <input type="number" class="form-control" name="num_max_lugares" id="edit_parque_num_max_lugares" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Tipo</label>
                                    <select class="form-select" name="tipo" id="edit_parque_tipo" required>
                                        <option value="Coberto">Coberto</option>
                                        <option value="Descoberto">Descoberto</option>
                                        <option value="Subterrâneo">Subterrâneo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tarifa (€/hora)</label>
                                <input type="number" step="0.10" class="form-control" name="tarifa" id="edit_parque_tarifa">
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Latitude</label>
                                    <input type="number" step="any" class="form-control" name="latitude" id="edit_parque_latitude" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Longitude</label>
                                    <input type="number" step="any" class="form-control" name="longitude" id="edit_parque_longitude" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg me-1"></i>Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-floppy me-1"></i>Guardar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ===== MODAL ELIMINAR PARQUE ===== -->
        <div class="modal fade" id="modalEliminarParque" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Eliminar Parque</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p class="mb-1">Tens a certeza que queres eliminar o parque</p>
                        <strong id="eliminar_parque_nome_display" class="d-block mb-2 text-danger"></strong>
                        <input type="hidden" id="eliminar_parque_id">
                        <small class="text-muted">Esta ação não pode ser desfeita.</small>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="btnConfirmarEliminarParque">
                            <i class="bi bi-trash me-1"></i>Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </main>

</div>
</body>
</html>

<?php include __DIR__ . "/../../includes/footer.php"; ?>