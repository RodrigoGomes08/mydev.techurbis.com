<?php include __DIR__ . "/../../includes/header.php"; ?>

    <main class="content-admin">

            <!-- ========================= PARQUES ========================= -->
                <!-- ========================= PARQUES DE ESTACIONAMENTO (MELHORADO) ========================= -->
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
                                <button class="btn btn-success btn-sm" id="btnNovoParque">
                                    <i class="bi bi-plus-circle me-1"></i>Adicionar Parque
                                </button>
                            </div>
                        </div>

                        <!-- KPIs GLOBAIS -->
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-3">
                                <div class="pkpi-card">
                                    <div class="pkpi-icon" style="background:#e8edff;color:#435ebe;">
                                        <i class="bi bi-p-circle-fill"></i>
                                    </div>
                                    <div>
                                        <div class="pkpi-value" id="pkpi-total">—</div>
                                        <div class="pkpi-label">Total de Parques</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="pkpi-card">
                                    <div class="pkpi-icon" style="background:#fde8e8;color:#e74c3c;">
                                        <i class="bi bi-car-front-fill"></i>
                                    </div>
                                    <div>
                                        <div class="pkpi-value text-danger" id="pkpi-cheios">—</div>
                                        <div class="pkpi-label">Quase Cheios (&gt;80%)</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="pkpi-card">
                                    <div class="pkpi-icon" style="background:#fff4e0;color:#f39c12;">
                                        <i class="bi bi-bar-chart-fill"></i>
                                    </div>
                                    <div>
                                        <div class="pkpi-value text-warning" id="pkpi-media">—</div>
                                        <div class="pkpi-label">Ocupação Média</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="pkpi-card">
                                    <div class="pkpi-icon" style="background:#e6f9ef;color:#27ae60;">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                    <div>
                                        <div class="pkpi-value text-success" id="pkpi-livres">—</div>
                                        <div class="pkpi-label">Lugares Livres Total</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FILTRO / PESQUISA -->
                        <div class="d-flex gap-2 mb-4 flex-wrap">
                            <div class="input-group" style="max-width:300px;">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" id="pesquisaParque" class="form-control border-start-0 ps-0"
                                    placeholder="Pesquisar parque...">
                            </div>
                            <select id="filtroParqueEstado" class="form-select" style="max-width:180px;">
                                <option value="">Todos os estados</option>
                                <option value="critico">Crítico (&gt;80%)</option>
                                <option value="atencao">Atenção (50–80%)</option>
                                <option value="normal">Normal (&lt;50%)</option>
                            </select>
                            <select id="filtroParqueTipo" class="form-select" style="max-width:180px;">
                                <option value="">Todos os tipos</option>
                                <option value="Coberto">Coberto</option>
                                <option value="Descoberto">Descoberto</option>
                                <option value="Subterrâneo">Subterrâneo</option>
                            </select>
                        </div>

                        <!-- GRID DE PARQUES -->
                        <div class="row g-4" id="gridParques"></div>

                        <!-- ALERTA ZONA -->
                        <div id="alertasParques" class="mt-4"></div>

                    </section>

                    <!-- ===== MODAL PARQUE DETALHE ===== -->
                    <div class="modal fade" id="modalParqueDetalhe" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                                    <h5 class="modal-title" id="modalParqueDetalheTitle">Detalhe do Parque</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body" id="modalParqueDetalheBody"></div>
                            </div>
                        </div>
                    </div>

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

                    <!-- ===== MODAL ADICIONAR / EDITAR PARQUE ===== -->
                    <div class="modal fade" id="modalNovoParque" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                                    <h5 class="modal-title" id="modalNovoParqueLabel">Adicionar Parque</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="parqueEditIndex">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nome do Parque</label>
                                        <input type="text" class="form-control" id="inputParqueNome" placeholder="Ex: Parque Sul">
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label fw-semibold">Capacidade Total</label>
                                            <input type="number" class="form-control" id="inputParqueCapacidade" placeholder="Ex: 200">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-semibold">Lugares Ocupados</label>
                                            <input type="number" class="form-control" id="inputParqueOcupados" placeholder="Ex: 120">
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label fw-semibold">Tipo</label>
                                            <select class="form-select" id="inputParqueTipo">
                                                <option>Coberto</option>
                                                <option>Descoberto</option>
                                                <option>Subterrâneo</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-semibold">Tarifa (€/hora)</label>
                                            <input type="number" step="0.10" class="form-control" id="inputParqueTarifa" placeholder="Ex: 1.50">
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label fw-semibold">Lugares Mobilidade Reduzida</label>
                                            <input type="number" class="form-control" id="inputParqueMR" placeholder="Ex: 5">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-semibold">Lugares Elétricos</label>
                                            <input type="number" class="form-control" id="inputParqueEV" placeholder="Ex: 3">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Morada</label>
                                        <input type="text" class="form-control" id="inputParqueMorada" placeholder="Ex: Rua Central, Loures">
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label fw-semibold">Latitude</label>
                                            <input type="number" step="any" class="form-control" id="inputParqueLat" placeholder="Ex: 38.83">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label fw-semibold">Longitude</label>
                                            <input type="number" step="any" class="form-control" id="inputParqueLng" placeholder="Ex: -9.17">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                        <i class="bi bi-x-lg me-1"></i>Cancelar
                                    </button>
                                    <button class="btn btn-primary" id="btnGuardarParque">
                                        <i class="bi bi-floppy me-1"></i>Guardar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
    </main>
</div>
<?php include __DIR__ . "/../../includes/footer.php"; ?>
<!-- <script>
    if (!sessionStorage.getItem('loggedIn')) { window.location.href = 'login.html'; }
</script> -->
</body>
</html>

<?php include __DIR__ . "/../../includes/footer.php"; ?>