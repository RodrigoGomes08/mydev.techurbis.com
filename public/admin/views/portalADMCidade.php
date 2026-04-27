<?php include __DIR__ . "/../../includes/header.php"; ?>

    <main class="content-admin">
        <section id="cidade" class="adm-page active">

            <!-- CABEÇALHO -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h2 class="mb-1">Informações Gerais da Cidade</h2>
                    <p class="text-muted mb-0" style="font-size:0.9rem;">
                        <i class="bi bi-clock me-1"></i>Última atualização: <span id="cidade-sync-time">—</span>
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-admin-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#painelInfo">
                        <i class="bi bi-cpu me-1"></i> Recursos Disponíveis
                    </button>
                </div>
            </div>

            <!-- KPIs -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#e8edff;color:#435ebe;"><i class="bi bi-wifi"></i></div>
                        <div><div class="pkpi-value">247</div><div class="pkpi-label">Sensores IoT Ativos</div></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#fff4e0;color:#f39c12;"><i class="bi bi-lightbulb-fill"></i></div>
                        <div><div class="pkpi-value text-warning">1 234</div><div class="pkpi-label">Luminárias Inteligentes</div></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#e6f9ef;color:#27ae60;"><i class="bi bi-lightning-charge-fill"></i></div>
                        <div><div class="pkpi-value text-success">45</div><div class="pkpi-label">Estações Carregamento</div></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#fde8e8;color:#e74c3c;"><i class="bi bi-camera-video-fill"></i></div>
                        <div><div class="pkpi-value text-danger">89</div><div class="pkpi-label">Câmaras de Vigilância</div></div>
                    </div>
                </div>
            </div>

            <!-- PAINEL RECURSOS (COLLAPSE) -->
            <div class="collapse mb-4" id="painelInfo">
                <div class="card-admin">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i class="bi bi-cpu me-2" style="color:#435ebe;"></i>Recursos Disponíveis</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div>
                                    <div class="d-flex justify-content-between mb-1" style="font-size:0.85rem;"><span><i class="bi bi-wifi me-1"></i>Sensores IoT Online</span><span class="fw-bold">247 / 260</span></div>
                                    <div class="parque-prog"><div class="parque-prog-bar normal" style="width:95%;"></div></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div>
                                    <div class="d-flex justify-content-between mb-1" style="font-size:0.85rem;"><span><i class="bi bi-lightbulb me-1"></i>Luminárias Operacionais</span><span class="fw-bold">1 190 / 1 234</span></div>
                                    <div class="parque-prog"><div class="parque-prog-bar normal" style="width:96%;"></div></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div>
                                    <div class="d-flex justify-content-between mb-1" style="font-size:0.85rem;"><span><i class="bi bi-lightning-charge me-1"></i>Estações Operacionais</span><span class="fw-bold">42 / 45</span></div>
                                    <div class="parque-prog"><div class="parque-prog-bar normal" style="width:93%;"></div></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div>
                                    <div class="d-flex justify-content-between mb-1" style="font-size:0.85rem;"><span><i class="bi bi-camera me-1"></i>Câmaras Online</span><span class="fw-bold">85 / 89</span></div>
                                    <div class="parque-prog"><div class="parque-prog-bar normal" style="width:95%;"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARDS DE INFO -->
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="parque-card normal">
                        <div class="parque-card-header">
                            <div class="parque-titulo">
                                <span><i class="bi bi-thermometer-half me-2" style="color:#e74c3c;"></i>Ambiente</span>
                                <span class="parque-tag ev">Atualizado</span>
                            </div>
                            <div class="parque-subtitulo"><i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>Condições normais</div>
                        </div>
                        <div class="parque-info-grid">
                            <div class="parque-info-item">
                                <span class="parque-info-label">Temperatura</span>
                                <span class="parque-info-value">22.5°C</span>
                            </div>
                            <div class="parque-info-item">
                                <span class="parque-info-label">Humidade</span>
                                <span class="parque-info-value">65%</span>
                            </div>
                            <div class="parque-info-item">
                                <span class="parque-info-label">Qualidade do Ar</span>
                                <span class="parque-info-value">Bom</span>
                            </div>
                            <div class="parque-info-item">
                                <span class="parque-info-label">Vento</span>
                                <span class="parque-info-value">12 km/h</span>
                            </div>
                        </div>
                        <div class="parque-actions">
                            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-clockwise me-1"></i>Atualizar</button>
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-bar-chart me-1"></i>Histórico</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="parque-card normal">
                        <div class="parque-card-header">
                            <div class="parque-titulo">
                                <span><i class="bi bi-people me-2" style="color:#435ebe;"></i>Tráfego Pedonal</span>
                                <span class="parque-tag">Normal</span>
                            </div>
                            <div class="parque-subtitulo"><i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>Fluxo dentro do esperado</div>
                        </div>
                        <div class="parque-info-grid">
                            <div class="parque-info-item">
                                <span class="parque-info-label">Zona Centro</span>
                                <span class="parque-info-value">Alto</span>
                            </div>
                            <div class="parque-info-item">
                                <span class="parque-info-label">Zona Norte</span>
                                <span class="parque-info-value">Médio</span>
                            </div>
                            <div class="parque-info-item">
                                <span class="parque-info-label">Zona Sul</span>
                                <span class="parque-info-value">Baixo</span>
                            </div>
                            <div class="parque-info-item">
                                <span class="parque-info-label">Zona Industrial</span>
                                <span class="parque-info-value">Baixo</span>
                            </div>
                        </div>
                        <div class="parque-actions">
                            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-map me-1"></i>Ver Mapa</button>
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1"></i>Detalhes</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="parque-card normal">
                        <div class="parque-card-header">
                            <div class="parque-titulo">
                                <span><i class="bi bi-recycle me-2" style="color:#27ae60;"></i>Resíduos</span>
                                <span class="parque-tag ev">Em dia</span>
                            </div>
                            <div class="parque-subtitulo"><i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>Recolha a cumprir o calendário</div>
                        </div>
                        <div class="parque-info-grid">
                            <div class="parque-info-item">
                                <span class="parque-info-label">Última Recolha</span>
                                <span class="parque-info-value">Hoje 06:30</span>
                            </div>
                            <div class="parque-info-item">
                                <span class="parque-info-label">Próxima Recolha</span>
                                <span class="parque-info-value">Amanhã</span>
                            </div>
                            <div class="parque-info-item">
                                <span class="parque-info-label">Rotas Concluídas</span>
                                <span class="parque-info-value">7 / 10</span>
                            </div>
                            <div class="parque-info-item">
                                <span class="parque-info-label">Reciclagem</span>
                                <span class="parque-info-value">38%</span>
                            </div>
                        </div>
                        <div class="parque-actions">
                            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-calendar me-1"></i>Calendário</button>
                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-eye me-1"></i>Detalhes</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL CONFIRM -->
            <div class="modal fade" id="modalConfirm" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                            <h5 class="modal-title"><i class="bi bi-check-circle me-2"></i>Confirmar Submissão</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-success mb-0">
                                <i class="bi bi-check-circle me-2"></i>
                                <strong>Sucesso!</strong> O relatório foi enviado com sucesso e será analisado pela equipa de suporte.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i>Fechar
                            </button>
                        </div>
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

<?php include __DIR__ . "/../../includes/footer.php"; ?>
<!-- <script>
    if (!sessionStorage.getItem('loggedIn')) { window.location.href = 'login.html'; }
    const syncEl = document.getElementById('cidade-sync-time');
    if (syncEl) syncEl.textContent = new Date().toLocaleString('pt-PT');
</script> -->
</body>
</html>
