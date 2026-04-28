<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilizadores | Portal SmartCity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/stylesPortal.css">
    <link rel="icon" type="image/png" href="Img/logo.png">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-admin">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="PortalADMGeral.html">
            <img src="Img/logo.png" alt="Logo" style="width:30px;height:30px;margin-right:8px;">
            <span>Portal de Administração</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto"></ul>
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="btn btn-notif" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        <span class="badge badge-notif bg-danger">0</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="width:320px;">
                        <li class="dropdown-header"><strong>Notificações</strong></li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="dropdown-item text-muted text-center">Sem notificações</li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="dropdown-item text-center text-muted" style="font-size:0.85rem;">Sistema SmartCity</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
<div class="admin-container">
    <aside class="sidebar-admin">
        <nav class="nav flex-column h-100">
            <a class="nav-link" href="PortalADMGeral.html"><i class="bi bi-graph-up"></i> Geral</a>
            <a class="nav-link active" href="PortalADMUtilizadores.html"><i class="bi bi-people"></i> Utilizadores</a>
            <a class="nav-link" href="PortalADMContentores.html"><i class="bi bi-trash"></i> Contentores de Lixo</a>
            <a class="nav-link" href="PortalADMPostes.html"><i class="bi bi-lightbulb"></i> Postes de Iluminação</a>
            <a class="nav-link" href="PortalADMParques.html"><i class="bi bi-p-circle"></i> Parques de Estacionamento</a>
            <a class="nav-link" href="PortalADMCidade.html"><i class="bi bi-info-circle"></i> Informações da Cidade</a>
            <a class="nav-link text-danger mt-auto" href="index.html"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </nav>
    </aside>
    <main class="content-admin">
        <section id="utilizadores" class="adm-page active">

            <!-- CABEÇALHO -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h2 class="mb-1">Gestão de Utilizadores</h2>
                    <p class="text-muted mb-0" style="font-size:0.9rem;">
                        <i class="bi bi-people me-1"></i><span id="util-count">3 utilizadores registados</span>
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-success btn-sm btn-add-util">
                        <i class="bi bi-plus-circle me-1"></i>Adicionar Utilizador
                    </button>
                </div>
            </div>

            <!-- KPIs -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#e8edff;color:#435ebe;"><i class="bi bi-people-fill"></i></div>
                        <div><div class="pkpi-value" id="pkpi-util-total">3</div><div class="pkpi-label">Total de Utilizadores</div></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#fde8e8;color:#e74c3c;"><i class="bi bi-shield-fill"></i></div>
                        <div><div class="pkpi-value text-danger" id="pkpi-util-admins">1</div><div class="pkpi-label">Administradores</div></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#e6f9ef;color:#27ae60;"><i class="bi bi-person-check-fill"></i></div>
                        <div><div class="pkpi-value text-success" id="pkpi-util-comuns">2</div><div class="pkpi-label">Utilizadores Comuns</div></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#fff4e0;color:#f39c12;"><i class="bi bi-geo-alt-fill"></i></div>
                        <div><div class="pkpi-value text-warning" id="pkpi-util-cidades">3</div><div class="pkpi-label">Cidades Diferentes</div></div>
                    </div>
                </div>
            </div>

            <!-- PESQUISA -->
            <div class="d-flex gap-2 mb-4 flex-wrap">
                <div class="input-group" style="max-width:300px;">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="searchUtil" class="form-control border-start-0 ps-0" placeholder="Pesquisar utilizador...">
                </div>
                <select id="filtroUtilCargo" class="form-select" style="max-width:180px;">
                    <option value="">Todos os cargos</option>
                    <option>Administrador</option>
                    <option>Utilizador</option>
                </select>
            </div>

            <!-- TABELA -->
            <div class="card-admin">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-admin table-hover">
                            <thead>
                                <tr>
                                    <th>Utilizador</th>
                                    <th>Cargo</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Morada</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="listaUtilizadores"></tbody>
                        </table>
                    </div>
                    <nav>
                        <ul class="pagination pagination-admin justify-content-end mt-3">
                            <li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">Próximo</a></li>
                        </ul>
                    </nav>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- MODAL ADICIONAR / EDITAR UTILIZADOR -->
<div class="modal fade" id="modalUtilizador" tabindex="-1" aria-labelledby="modalUtilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                <h5 class="modal-title" id="modalUtilLabel">Adicionar Utilizador</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="utilIndex">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nome Completo</label>
                    <input type="text" class="form-control" id="inputUtilNome" placeholder="Ex: João Silva">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" id="inputUtilEmail" placeholder="Ex: joao@email.com">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Cargo</label>
                    <select class="form-select" id="inputUtilCargo">
                        <option value="Utilizador">Utilizador</option>
                        <option value="Administrador">Administrador</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Morada</label>
                    <input type="text" class="form-control" id="inputUtilMorada" placeholder="Ex: Rua das Flores, Porto">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password <span class="text-muted fw-normal">(deixar em branco para manter)</span></label>
                    <input type="password" class="form-control" id="inputUtilPass" placeholder="Nova password">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btnGuardarUtil">
                    <i class="bi bi-floppy me-1"></i>Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="scripts/scriptsPortal.js"></script>
<script>
    if (!sessionStorage.getItem('loggedIn')) { window.location.href = 'login.html'; }
</script>
</body>
</html>