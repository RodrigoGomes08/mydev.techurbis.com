<?php include __DIR__ . "/../../includes/header.php"; ?>

    <main class="content-admin">
        <section id="utilizadores" class="adm-page active">

            <!-- CABEÇALHO -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h2 class="mb-1">Gestão de Utilizadores</h2>
                    <p class="text-muted mb-0" style="font-size:0.9rem;">
                        <i class="bi bi-people me-1"></i>3 utilizadores registados
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Adicionar Utilizador
                    </button>
                </div>
            </div>

            <!-- KPIs -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#e8edff;color:#435ebe;"><i class="bi bi-people-fill"></i></div>
                        <div><div class="pkpi-value">3</div><div class="pkpi-label">Total de Utilizadores</div></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#fde8e8;color:#e74c3c;"><i class="bi bi-shield-fill"></i></div>
                        <div><div class="pkpi-value text-danger">1</div><div class="pkpi-label">Administradores</div></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#e6f9ef;color:#27ae60;"><i class="bi bi-person-check-fill"></i></div>
                        <div><div class="pkpi-value text-success">2</div><div class="pkpi-label">Utilizadores Comuns</div></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#fff4e0;color:#f39c12;"><i class="bi bi-geo-alt-fill"></i></div>
                        <div><div class="pkpi-value text-warning">3</div><div class="pkpi-label">Cidades Diferentes</div></div>
                    </div>
                </div>
            </div>

            <!-- PESQUISA -->
            <div class="d-flex gap-2 mb-4 flex-wrap">
                <div class="input-group" style="max-width:300px;">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" placeholder="Pesquisar utilizador...">
                </div>
                <select class="form-select" style="max-width:180px;">
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
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#435ebe,#5f7cff);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:0.85rem;flex-shrink:0;">JN</div>
                                            <strong>João Nicolau</strong>
                                        </div>
                                    </td>
                                    <td><span class="parque-tag" style="background:#fde8e8;color:#e74c3c;"><i class="bi bi-shield-fill me-1"></i>Administrador</span></td>
                                    <td>joao@gmail.com</td>
                                    <td><span class="badge bg-secondary">••••••••</span></td>
                                    <td><i class="bi bi-geo-alt text-muted me-1"></i>Rua das Flores, Porto</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></button>
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#27ae60,#2ecc71);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:0.85rem;flex-shrink:0;">AC</div>
                                            <strong>Ana Costa</strong>
                                        </div>
                                    </td>
                                    <td><span class="parque-tag"><i class="bi bi-person-fill me-1"></i>Utilizador</span></td>
                                    <td>ana@gmail.com</td>
                                    <td><span class="badge bg-secondary">••••••••</span></td>
                                    <td><i class="bi bi-geo-alt text-muted me-1"></i>Av. Central, Braga</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></button>
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#f39c12,#f1c40f);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:0.85rem;flex-shrink:0;">PS</div>
                                            <strong>Pedro Santos</strong>
                                        </div>
                                    </td>
                                    <td><span class="parque-tag"><i class="bi bi-person-fill me-1"></i>Utilizador</span></td>
                                    <td>pedro@gmail.com</td>
                                    <td><span class="badge bg-secondary">••••••••</span></td>
                                    <td><i class="bi bi-geo-alt text-muted me-1"></i>Rua do Sol, Lisboa</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></button>
                                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
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

<?php include __DIR__ . "/../../includes/footer.php"; ?>
<!-- <script>
    if (!sessionStorage.getItem('loggedIn')) { window.location.href = 'login.html'; }
</script> -->
</body>
</html>
