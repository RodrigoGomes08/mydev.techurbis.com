<?php include __DIR__ . "/../../includes/header.php"; ?>


    <main class="content-admin">
        <section id="geral" class="adm-page active">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h2 class="mb-1">Visão Geral do Sistema</h2>
                    <p class="text-muted mb-0" style="font-size:0.9rem;">
                        <i class="bi bi-clock me-1"></i>Última atualização: <span id="update-time">—</span>
                    </p>
                </div>
            </div>

            <!-- KPIs -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#e8edff;color:#435ebe;"><i class="bi bi-people-fill"></i></div>
                        <div>
                            <div class="pkpi-value">3</div>
                            <div class="pkpi-label">Utilizadores</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#fde8e8;color:#e74c3c;"><i class="bi bi-trash-fill"></i></div>
                        <div>
                            <div class="pkpi-value text-danger">14</div>
                            <div class="pkpi-label">Contentores Críticos</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#fff4e0;color:#f39c12;"><i class="bi bi-lightbulb-fill"></i></div>
                        <div>
                            <div class="pkpi-value text-warning">2</div>
                            <div class="pkpi-label">Postes Avariados</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#e6f9ef;color:#27ae60;"><i class="bi bi-p-circle-fill"></i></div>
                        <div>
                            <div class="pkpi-value text-success">62%</div>
                            <div class="pkpi-label">Ocupação Média Parques</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card-admin">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Alertas Recentes</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center gap-3 px-0">
                                    <span style="background:#fff4e0;color:#f39c12;border-radius:10px;padding:7px 9px;font-size:1.1rem;flex-shrink:0;"><i class="bi bi-exclamation-circle"></i></span>
                                    <div><div style="font-weight:600;font-size:0.9rem;">Contentor C com 90% de capacidade</div><div style="font-size:0.78rem;color:#999;">Contentores de Lixo · Bucelas</div></div>
                                </li>
                                <li class="list-group-item d-flex align-items-center gap-3 px-0">
                                    <span style="background:#fde8e8;color:#e74c3c;border-radius:10px;padding:7px 9px;font-size:1.1rem;flex-shrink:0;"><i class="bi bi-x-circle"></i></span>
                                    <div><div style="font-weight:600;font-size:0.9rem;">Poste 2 encontra-se avariado</div><div style="font-size:0.78rem;color:#999;">Postes de Iluminação · Lâmpada fundida</div></div>
                                </li>
                                <li class="list-group-item d-flex align-items-center gap-3 px-0">
                                    <span style="background:#fff4e0;color:#f39c12;border-radius:10px;padding:7px 9px;font-size:1.1rem;flex-shrink:0;"><i class="bi bi-exclamation-circle"></i></span>
                                    <div><div style="font-weight:600;font-size:0.9rem;">Parque Norte com 85% de ocupação</div><div style="font-size:0.78rem;color:#999;">Parques de Estacionamento · Quase cheio</div></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card-admin h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="bi bi-activity me-2" style="color:#435ebe;"></i>Estado do Sistema</h5>
                            <div class="d-flex flex-column gap-3">
                                <div>
                                    <div class="d-flex justify-content-between mb-1" style="font-size:0.85rem;"><span><i class="bi bi-trash me-1"></i>Contentores</span><span class="fw-bold">73 / 88 ativos</span></div>
                                    <div class="parque-prog"><div class="parque-prog-bar atencao" style="width:83%;"></div></div>
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between mb-1" style="font-size:0.85rem;"><span><i class="bi bi-lightbulb me-1"></i>Postes Operacionais</span><span class="fw-bold">2 / 4 ativos</span></div>
                                    <div class="parque-prog"><div class="parque-prog-bar normal" style="width:50%;"></div></div>
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between mb-1" style="font-size:0.85rem;"><span><i class="bi bi-p-circle me-1"></i>Parques em bom estado</span><span class="fw-bold">3 / 5</span></div>
                                    <div class="parque-prog"><div class="parque-prog-bar normal" style="width:60%;"></div></div>
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between mb-1" style="font-size:0.85rem;"><span><i class="bi bi-wifi me-1"></i>Sensores IoT Online</span><span class="fw-bold">247 / 260</span></div>
                                    <div class="parque-prog"><div class="parque-prog-bar normal" style="width:95%;"></div></div>
                                </div>
                            </div>
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


<!-- <script>
    if (!sessionStorage.getItem('loggedIn')) { window.location.href = 'login.html'; }
</script> -->
</body>
</html>
<?php include __DIR__ . "/../../includes/footer.php"; ?>