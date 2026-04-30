<?php include __DIR__ . "/../../includes/header.php"; ?>

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
                    <button class="btn btn-outline-danger btn-sm" id="btnContCriticos">
                        <i class="bi bi-exclamation-triangle me-1"></i>Só Críticos
                    </button>
                </div>
            </div>

            <!-- KPIs -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#e8edff;color:#435ebe;"><i class="bi bi-trash-fill"></i></div>
                        <div><div class="pkpi-value">80</div><div class="pkpi-label">Total de Contentores</div></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#fde8e8;color:#e74c3c;"><i class="bi bi-exclamation-triangle-fill"></i></div>
                        <div><div class="pkpi-value text-danger">13</div><div class="pkpi-label">Críticos (&gt;80%)</div></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#fff4e0;color:#f39c12;"><i class="bi bi-dash-circle-fill"></i></div>
                        <div><div class="pkpi-value text-warning">46</div><div class="pkpi-label">Em Atenção (36–80%)</div></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#e6f9ef;color:#27ae60;"><i class="bi bi-check-circle-fill"></i></div>
                        <div><div class="pkpi-value text-success">21</div><div class="pkpi-label">Normais (≤35%)</div></div>
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

            <!-- SECÇÕES -->

                <!-- SECÇÃO: Bucelas -->
                <div class="mb-3">
                    <button class="btn btn-outline-primary w-100 text-start d-flex justify-content-between align-items-center py-3 px-4"
                        style="border-radius:10px;font-weight:600;background:white;border-color:#e6e9f0;"
                        data-bs-toggle="collapse" data-bs-target="#collapseBucelas">
                        <span><i class="bi bi-geo-alt me-2" style="color:#435ebe;"></i>Bucelas</span>
                        <span class="d-flex align-items-center gap-2">
                            <span class="badge" style="background:#fde8e8;color:#e74c3c;font-size:0.75rem;">3 críticos</span>
                            <span class="badge" style="background:#e8edff;color:#435ebe;font-size:0.75rem;">8 contentores</span>
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </button>
                    <div class="collapse" id="collapseBucelas">
                        <div class="parque-alerta"><i class="bi bi-exclamation-triangle-fill"></i> 3 contentores acima de 80% nesta área</div>
                        <div class="row g-3 mt-1">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Bucelas 001</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">73%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">73%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:73%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Bucelas 002</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">41%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">41%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:41%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card critico">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Bucelas 003</span>
                                        <span class="parque-tag " style="font-size:0.7rem;">88%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#e74c3c;font-size:0.5rem;"></i>
                                        Cheio
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num critico">88%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar critico" style="width:88%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Bucelas 004</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">16%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">16%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:16%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card critico">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Bucelas 005</span>
                                        <span class="parque-tag " style="font-size:0.7rem;">95%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#e74c3c;font-size:0.5rem;"></i>
                                        Cheio
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num critico">95%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar critico" style="width:95%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Bucelas 006</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">27%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">27%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:27%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Bucelas 007</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">54%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">54%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:54%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card critico">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Bucelas 008</span>
                                        <span class="parque-tag " style="font-size:0.7rem;">82%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#e74c3c;font-size:0.5rem;"></i>
                                        Cheio
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num critico">82%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar critico" style="width:82%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- SECÇÃO: União das freguesias de Camarate, Unhos e Apelação -->
                <div class="mb-3">
                    <button class="btn btn-outline-primary w-100 text-start d-flex justify-content-between align-items-center py-3 px-4"
                        style="border-radius:10px;font-weight:600;background:white;border-color:#e6e9f0;"
                        data-bs-toggle="collapse" data-bs-target="#collapseCamarate">
                        <span><i class="bi bi-geo-alt me-2" style="color:#435ebe;"></i>União das freguesias de Camarate, Unhos e Apelação</span>
                        <span class="d-flex align-items-center gap-2">
                            <span class="badge" style="background:#fde8e8;color:#e74c3c;font-size:0.75rem;">1 crítico</span>
                            <span class="badge" style="background:#e8edff;color:#435ebe;font-size:0.75rem;">8 contentores</span>
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </button>
                    <div class="collapse" id="collapseCamarate">
                        <div class="parque-alerta"><i class="bi bi-exclamation-triangle-fill"></i> 1 contentor acima de 80% nesta área</div>
                        <div class="row g-3 mt-1">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Apelação 001</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">39%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">39%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:39%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Apelação 002</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">67%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">67%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:67%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card critico">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Apelação 003</span>
                                        <span class="parque-tag " style="font-size:0.7rem;">91%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#e74c3c;font-size:0.5rem;"></i>
                                        Cheio
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num critico">91%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar critico" style="width:91%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Apelação 004</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">23%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">23%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:23%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Apelação 005</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">78%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">78%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:78%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Apelação 006</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">45%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">45%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:45%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Apelação 007</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">69%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">69%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:69%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Apelação 008</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">51%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">51%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:51%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- SECÇÃO: Fanhões -->
                <div class="mb-3">
                    <button class="btn btn-outline-primary w-100 text-start d-flex justify-content-between align-items-center py-3 px-4"
                        style="border-radius:10px;font-weight:600;background:white;border-color:#e6e9f0;"
                        data-bs-toggle="collapse" data-bs-target="#collapseFanhoes">
                        <span><i class="bi bi-geo-alt me-2" style="color:#435ebe;"></i>Fanhões</span>
                        <span class="d-flex align-items-center gap-2">
                            <span class="badge" style="background:#fde8e8;color:#e74c3c;font-size:0.75rem;">2 críticos</span>
                            <span class="badge" style="background:#e8edff;color:#435ebe;font-size:0.75rem;">8 contentores</span>
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </button>
                    <div class="collapse" id="collapseFanhoes">
                        <div class="parque-alerta"><i class="bi bi-exclamation-triangle-fill"></i> 2 contentores acima de 80% nesta área</div>
                        <div class="row g-3 mt-1">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Fanhões 001</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">38%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">38%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:38%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card critico">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Fanhões 002</span>
                                        <span class="parque-tag " style="font-size:0.7rem;">82%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#e74c3c;font-size:0.5rem;"></i>
                                        Cheio
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num critico">82%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar critico" style="width:82%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Fanhões 003</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">19%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">19%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:19%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Fanhões 004</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">71%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">71%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:71%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Fanhões 005</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">52%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">52%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:52%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card critico">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Fanhões 006</span>
                                        <span class="parque-tag " style="font-size:0.7rem;">86%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#e74c3c;font-size:0.5rem;"></i>
                                        Cheio
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num critico">86%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar critico" style="width:86%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Fanhões 007</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">33%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">33%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:33%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Fanhões 008</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">64%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">64%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:64%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- SECÇÃO: Loures -->
                <div class="mb-3">
                    <button class="btn btn-outline-primary w-100 text-start d-flex justify-content-between align-items-center py-3 px-4"
                        style="border-radius:10px;font-weight:600;background:white;border-color:#e6e9f0;"
                        data-bs-toggle="collapse" data-bs-target="#collapseLoures">
                        <span><i class="bi bi-geo-alt me-2" style="color:#435ebe;"></i>Loures</span>
                        <span class="d-flex align-items-center gap-2">
                            <span class="badge" style="background:#fde8e8;color:#e74c3c;font-size:0.75rem;">2 críticos</span>
                            <span class="badge" style="background:#e8edff;color:#435ebe;font-size:0.75rem;">8 contentores</span>
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </button>
                    <div class="collapse" id="collapseLoures">
                        <div class="parque-alerta"><i class="bi bi-exclamation-triangle-fill"></i> 2 contentores acima de 80% nesta área</div>
                        <div class="row g-3 mt-1">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Loures 001</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">76%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">76%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:76%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Loures 002</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">42%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">42%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:42%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card critico">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Loures 003</span>
                                        <span class="parque-tag " style="font-size:0.7rem;">93%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#e74c3c;font-size:0.5rem;"></i>
                                        Cheio
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num critico">93%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar critico" style="width:93%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Loures 004</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">18%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">18%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:18%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Loures 005</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">63%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">63%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:63%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card critico">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Loures 006</span>
                                        <span class="parque-tag " style="font-size:0.7rem;">87%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#e74c3c;font-size:0.5rem;"></i>
                                        Cheio
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num critico">87%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar critico" style="width:87%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Loures 007</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">24%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">24%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:24%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Loures 008</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">68%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">68%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:68%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- SECÇÃO: Lousa -->
                <div class="mb-3">
                    <button class="btn btn-outline-primary w-100 text-start d-flex justify-content-between align-items-center py-3 px-4"
                        style="border-radius:10px;font-weight:600;background:white;border-color:#e6e9f0;"
                        data-bs-toggle="collapse" data-bs-target="#collapseLousa">
                        <span><i class="bi bi-geo-alt me-2" style="color:#435ebe;"></i>Lousa</span>
                        <span class="d-flex align-items-center gap-2">
                            <span class="badge" style="background:#fde8e8;color:#e74c3c;font-size:0.75rem;">1 crítico</span>
                            <span class="badge" style="background:#e8edff;color:#435ebe;font-size:0.75rem;">8 contentores</span>
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </button>
                    <div class="collapse" id="collapseLousa">
                        <div class="parque-alerta"><i class="bi bi-exclamation-triangle-fill"></i> 1 contentor acima de 80% nesta área</div>
                        <div class="row g-3 mt-1">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Lousa 001</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">55%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">55%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:55%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Lousa 002</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">74%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">74%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:74%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Lousa 003</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">31%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">31%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:31%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Lousa 004</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">79%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">79%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:79%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Lousa 005</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">46%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">46%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:46%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card critico">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Lousa 006</span>
                                        <span class="parque-tag " style="font-size:0.7rem;">92%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#e74c3c;font-size:0.5rem;"></i>
                                        Cheio
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num critico">92%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar critico" style="width:92%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Lousa 007</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">28%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">28%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:28%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Lousa 008</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">57%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">57%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:57%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- SECÇÃO: União das freguesias de Moscavide e da Portela -->
                <div class="mb-3">
                    <button class="btn btn-outline-primary w-100 text-start d-flex justify-content-between align-items-center py-3 px-4"
                        style="border-radius:10px;font-weight:600;background:white;border-color:#e6e9f0;"
                        data-bs-toggle="collapse" data-bs-target="#collapseMoscavide">
                        <span><i class="bi bi-geo-alt me-2" style="color:#435ebe;"></i>União das freguesias de Moscavide e da Portela</span>
                        <span class="d-flex align-items-center gap-2">
                            <span class="badge" style="background:#fde8e8;color:#e74c3c;font-size:0.75rem;">1 crítico</span>
                            <span class="badge" style="background:#e8edff;color:#435ebe;font-size:0.75rem;">8 contentores</span>
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </button>
                    <div class="collapse" id="collapseMoscavide">
                        <div class="parque-alerta"><i class="bi bi-exclamation-triangle-fill"></i> 1 contentor acima de 80% nesta área</div>
                        <div class="row g-3 mt-1">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Portela 001</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">37%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">37%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:37%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Portela 002</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">65%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">65%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:65%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Portela 003</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">14%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">14%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:14%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card critico">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Portela 004</span>
                                        <span class="parque-tag " style="font-size:0.7rem;">89%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#e74c3c;font-size:0.5rem;"></i>
                                        Cheio
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num critico">89%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar critico" style="width:89%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Portela 005</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">61%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">61%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:61%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Portela 006</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">35%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">35%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:35%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Portela 007</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">70%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">70%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:70%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Portela 008</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">44%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">44%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:44%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- SECÇÃO: União das freguesias de Sacavém e Prior Velho -->
                <div class="mb-3">
                    <button class="btn btn-outline-primary w-100 text-start d-flex justify-content-between align-items-center py-3 px-4"
                        style="border-radius:10px;font-weight:600;background:white;border-color:#e6e9f0;"
                        data-bs-toggle="collapse" data-bs-target="#collapseSacavem">
                        <span><i class="bi bi-geo-alt me-2" style="color:#435ebe;"></i>União das freguesias de Sacavém e Prior Velho</span>
                        <span class="d-flex align-items-center gap-2">
                            <span class="badge" style="background:#fde8e8;color:#e74c3c;font-size:0.75rem;">1 crítico</span>
                            <span class="badge" style="background:#e8edff;color:#435ebe;font-size:0.75rem;">8 contentores</span>
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </button>
                    <div class="collapse" id="collapseSacavem">
                        <div class="parque-alerta"><i class="bi bi-exclamation-triangle-fill"></i> 1 contentor acima de 80% nesta área</div>
                        <div class="row g-3 mt-1">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Velho 001</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">56%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">56%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:56%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Velho 002</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">77%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">77%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:77%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Velho 003</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">25%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">25%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:25%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Velho 004</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">75%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">75%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:75%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Velho 005</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">50%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">50%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:50%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card critico">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Velho 006</span>
                                        <span class="parque-tag " style="font-size:0.7rem;">84%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#e74c3c;font-size:0.5rem;"></i>
                                        Cheio
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num critico">84%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar critico" style="width:84%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Velho 007</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">13%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">13%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:13%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Velho 008</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">59%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">59%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:59%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- SECÇÃO: União das freguesias de Santa Iria e de São João da Madeira -->
                <div class="mb-3">
                    <button class="btn btn-outline-primary w-100 text-start d-flex justify-content-between align-items-center py-3 px-4"
                        style="border-radius:10px;font-weight:600;background:white;border-color:#e6e9f0;"
                        data-bs-toggle="collapse" data-bs-target="#collapseSantaIria">
                        <span><i class="bi bi-geo-alt me-2" style="color:#435ebe;"></i>União das freguesias de Santa Iria e de São João da Madeira</span>
                        <span class="d-flex align-items-center gap-2">
                            <span class="badge" style="background:#fde8e8;color:#e74c3c;font-size:0.75rem;">1 crítico</span>
                            <span class="badge" style="background:#e8edff;color:#435ebe;font-size:0.75rem;">8 contentores</span>
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </button>
                    <div class="collapse" id="collapseSantaIria">
                        <div class="parque-alerta"><i class="bi bi-exclamation-triangle-fill"></i> 1 contentor acima de 80% nesta área</div>
                        <div class="row g-3 mt-1">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Madeira 001</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">72%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">72%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:72%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Madeira 002</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">36%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">36%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:36%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card critico">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Madeira 003</span>
                                        <span class="parque-tag " style="font-size:0.7rem;">81%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#e74c3c;font-size:0.5rem;"></i>
                                        Cheio
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num critico">81%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar critico" style="width:81%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Madeira 004</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">15%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">15%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:15%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Madeira 005</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">53%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">53%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:53%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Madeira 006</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">66%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">66%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:66%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Madeira 007</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">29%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">29%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:29%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Madeira 008</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">48%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">48%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:48%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- SECÇÃO: União das freguesias de Santo Antão e São Julião do Tojal -->
                <div class="mb-3">
                    <button class="btn btn-outline-primary w-100 text-start d-flex justify-content-between align-items-center py-3 px-4"
                        style="border-radius:10px;font-weight:600;background:white;border-color:#e6e9f0;"
                        data-bs-toggle="collapse" data-bs-target="#collapseSantoAntao">
                        <span><i class="bi bi-geo-alt me-2" style="color:#435ebe;"></i>União das freguesias de Santo Antão e São Julião do Tojal</span>
                        <span class="d-flex align-items-center gap-2">
                            <span class="badge" style="background:#fde8e8;color:#e74c3c;font-size:0.75rem;">1 crítico</span>
                            <span class="badge" style="background:#e8edff;color:#435ebe;font-size:0.75rem;">8 contentores</span>
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </button>
                    <div class="collapse" id="collapseSantoAntao">
                        <div class="parque-alerta"><i class="bi bi-exclamation-triangle-fill"></i> 1 contentor acima de 80% nesta área</div>
                        <div class="row g-3 mt-1">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Tojal 001</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">60%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">60%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:60%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Tojal 002</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">32%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">32%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:32%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Tojal 003</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">47%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">47%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:47%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card critico">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Tojal 004</span>
                                        <span class="parque-tag " style="font-size:0.7rem;">85%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#e74c3c;font-size:0.5rem;"></i>
                                        Cheio
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num critico">85%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar critico" style="width:85%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Tojal 005</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">40%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">40%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:40%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Tojal 006</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">22%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">22%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:22%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Tojal 007</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">58%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">58%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:58%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Tojal 008</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">26%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">26%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:26%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- SECÇÃO: União das freguesias de Santo António dos Cavaleiros e Frielas -->
                <div class="mb-3">
                    <button class="btn btn-outline-primary w-100 text-start d-flex justify-content-between align-items-center py-3 px-4"
                        style="border-radius:10px;font-weight:600;background:white;border-color:#e6e9f0;"
                        data-bs-toggle="collapse" data-bs-target="#collapseSantoAntonio">
                        <span><i class="bi bi-geo-alt me-2" style="color:#435ebe;"></i>União das freguesias de Santo António dos Cavaleiros e Frielas</span>
                        <span class="d-flex align-items-center gap-2">
                            
                            <span class="badge" style="background:#e8edff;color:#435ebe;font-size:0.75rem;">8 contentores</span>
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </button>
                    <div class="collapse" id="collapseSantoAntonio">
                        
                        <div class="row g-3 mt-1">
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Frielas 001</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">34%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">34%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:34%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Frielas 002</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">80%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">80%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:80%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Frielas 003</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">17%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">17%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:17%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Frielas 004</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">62%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">62%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:62%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Frielas 005</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">43%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">43%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:43%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Frielas 006</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">75%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">75%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:75%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card normal">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Frielas 007</span>
                                        <span class="parque-tag ev" style="font-size:0.7rem;">20%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#2ecc71;font-size:0.5rem;"></i>
                                        Normal
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num normal">20%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar normal" style="width:20%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="parque-card atencao">
                                <div class="parque-card-header">
                                    <div class="parque-titulo">
                                        <span><i class="bi bi-trash me-1"></i>Frielas 008</span>
                                        <span class="parque-tag mr" style="font-size:0.7rem;">61%</span>
                                    </div>
                                    <div class="parque-subtitulo">
                                        <i class="bi bi-circle-fill" style="color:#f39c12;font-size:0.5rem;"></i>
                                        Atenção
                                    </div>
                                </div>
                                <div class="parque-ocupacao-wrap">
                                    <div class="parque-pct-num atencao">61%</div>
                                    <div class="parque-prog">
                                        <div class="parque-prog-bar atencao" style="width:61%;"></div>
                                    </div>
                                    <div class="parque-lugares-label">Capacidade usada</div>
                                </div>
                                <div class="parque-actions">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalContentor001">
                                        <i class="bi bi-geo-alt me-1"></i>Mapa
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary btn-detalhe-cont">
                                        <i class="bi bi-eye me-1"></i>Detalhes
                                    </button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

        </section>

        <!-- MODAL MAPA -->
        <div class="modal fade" id="modalContentor001" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                        <h5 class="modal-title">Localização do Contentor</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div style="width:100%;height:420px;">
                            <iframe src="https://www.google.com/maps?q=38.9019,-9.1165&z=15&output=embed"
                                width="100%" height="100%" style="border:0;" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

<!-- MODAL DETALHES CONTENTOR -->
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

<script>
    /*if (!sessionStorage.getItem('loggedIn')) { window.location.href = 'login.html'; }

    const syncEl = document.getElementById('cont-sync-time');
    if (syncEl) syncEl.textContent = new Date().toLocaleString('pt-PT');
*/
    // Filter
    function aplicarFiltroContentores() {
        const texto = (document.getElementById('searchContentor')?.value || '').toLowerCase();
        const estado = document.getElementById('filtroContentorEstado')?.value || '';
        document.querySelectorAll('.parque-card').forEach(card => {
            const parent = card.closest('.col-12');
            if (!parent) return;
            const label = card.querySelector('.parque-titulo')?.textContent?.toLowerCase() || '';
            const cls = card.classList.contains('critico') ? 'critico' : card.classList.contains('atencao') ? 'atencao' : 'normal';
            const matchText = !texto || label.includes(texto);
            const matchEstado = !estado || cls === estado;
            parent.style.display = (matchText && matchEstado) ? '' : 'none';
        });
    }

    document.getElementById('searchContentor')?.addEventListener('input', aplicarFiltroContentores);
    document.getElementById('filtroContentorEstado')?.addEventListener('change', aplicarFiltroContentores);

    document.getElementById('btnContCriticos')?.addEventListener('click', function () {
        const filtro = document.getElementById('filtroContentorEstado');
        if (filtro.value === 'critico') {
            filtro.value = '';
            this.classList.remove('active','btn-danger');
            this.classList.add('btn-outline-danger');
        } else {
            filtro.value = 'critico';
            this.classList.add('active','btn-danger');
            this.classList.remove('btn-outline-danger');
        }
        aplicarFiltroContentores();
        document.querySelectorAll('.collapse').forEach(c => {
            if (filtro.value) new bootstrap.Collapse(c, {show:true});
        });
    });

    // Botões Detalhes — abre modal com info do contentor
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-detalhe-cont');
        if (!btn) return;
        const card  = btn.closest('.parque-card');
        const nome  = card?.querySelector('.parque-titulo span')?.textContent?.trim() || 'Contentor';
        const pct   = card?.querySelector('.parque-pct-num')?.textContent?.trim() || '—';
        const cls   = card?.classList.contains('critico') ? 'danger' : card?.classList.contains('atencao') ? 'warning' : 'success';
        const estado = cls === 'danger' ? 'Crítico' : cls === 'warning' ? 'Atenção' : 'Normal';
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
                <div class="parque-prog-bar ${cls === 'danger' ? 'critico' : cls === 'warning' ? 'atencao' : 'normal'}" style="width:${pct}"></div>
            </div>
            <table class="table table-sm table-borderless" style="font-size:0.9rem;">
                <tr><td class="text-muted">Nome</td><td><strong>${nome}</strong></td></tr>
                <tr><td class="text-muted">Estado</td><td><span class="badge bg-${cls}">${estado}</span></td></tr>
                <tr><td class="text-muted">Última leitura</td><td><strong>${new Date().toLocaleString('pt-PT')}</strong></td></tr>
                <tr><td class="text-muted">Próxima recolha</td><td><strong>Amanhã 06:30</strong></td></tr>
            </table>`;
        new bootstrap.Modal(document.getElementById('modalDetalheContentor')).show();
    });
</script>
</body>
</html>

<?php include __DIR__ . "/../../includes/footer.php"; ?>