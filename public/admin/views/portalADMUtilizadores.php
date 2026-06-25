<?php include __DIR__ . "/../../includes/header.php"; ?>

<main class="content-admin">
    <section id="utilizadores" class="adm-page active">

        <!-- CABEÇALHO -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h2 class="mb-1">Gestão de Utilizadores</h2>
                <p class="text-muted mb-0" style="font-size:0.9rem;">
                    <i class="bi bi-people me-1"></i>
                </p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-success btn-sm btn-add-util" data-bs-toggle="modal"
                    data-bs-target="#modalUtilizador">
                    <i class="bi bi-plus-circle me-1"></i>Adicionar Utilizador
                </button>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalRole">
                    <i class="bi bi-shield-plus me-1"></i>Adicionar Role
                </button>
            </div>
        </div>

        <!-- KPIs -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="pkpi-card">
                    <div class="pkpi-icon" style="background:#e8edff;color:#435ebe;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="pkpi-value"><?= (int) ($estatisticasUtilizadores['total_utilizadores'] ?? 0) ?>
                        </div>
                        <div class="pkpi-label">Total de Utilizadores</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="pkpi-card">
                    <div class="pkpi-icon" style="background:#e6f9ef;color:#27ae60;">
                        <i class="bi bi-shield-fill"></i>
                    </div>
                    <div>
                        <div class="pkpi-value text-success">
                            <?= (int) ($estatisticasUtilizadores['total_administradores'] ?? 0) ?></div>
                        <div class="pkpi-label">Administradores</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="pkpi-card">
                    <div class="pkpi-icon" style="background:#e8edff;color:#435ebe;">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <div>
                        <div class="pkpi-value" style="color:#435ebe;">
                            <?= (int) ($estatisticasUtilizadores['total_utilizadores_comuns'] ?? 0) ?></div>
                        <div class="pkpi-label">Utilizadores</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="pkpi-card">
                    <div class="pkpi-icon" style="background:#fde8e8;color:#e74c3c;">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div>
                        <div class="pkpi-value text-danger">
                            <?= (int) ($estatisticasUtilizadores['total_operadores'] ?? 0) ?></div>
                        <div class="pkpi-label">Operadores</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PESQUISA -->
        <div class="d-flex gap-2 mb-4 flex-wrap">
            <div class="input-group" style="max-width:300px;">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" id="searchUtil" class="form-control border-start-0 ps-0"
                    placeholder="Pesquisar utilizador...">
            </div>
            <select id="filtroUtilCargo" class="form-select" style="max-width:180px;">
                <option value="">Todos os cargos</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?= $role->getIdRole() ?>"><?= htmlspecialchars($role->getNomeRole()) ?></option>
                <?php endforeach; ?>
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
                                <th>Morada</th>
                                <th>Estado</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="listaUtilizadores">
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-people me-2"></i>Nenhum utilizador encontrado.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $user): ?>
                                    <?php
                                    // Encontrar o nome da role correspondente
                                    $roleNome = 'Desconhecido';
                                    foreach ($roles as $role) {
                                        if ($role->getIdRole() === $user->getIdRole()) {
                                            $roleNome = $role->getNomeRole();
                                            break;
                                        }
                                    }
                                    ?>
                                    <tr data-role="<?= $user->getIdRole() ?>">
                                        <td>
                                            <div class="fw-semibold">
                                                <?= htmlspecialchars($user->getNome()) ?>
                                            </div>
                                        </td>
                                        <?php
                                        $classeCargo = '';

                                        if (strtolower($roleNome) === 'administrador') {
                                            $classeCargo = 'badge-admin';
                                        } elseif (strtolower($roleNome) === 'utilizador') {
                                            $classeCargo = 'badge-utilizador';
                                        } elseif (strtolower($roleNome) === 'operador') {
                                            $classeCargo = 'badge-operador';
                                        }
                                        ?>

                                        <td>
                                            <span class="badge rounded-pill <?= $classeCargo ?>">
                                                <?= htmlspecialchars($roleNome) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($user->getEmail()) ?></td>
                                        <td><?= htmlspecialchars($user->getMorada()) ?></td>
                                        <td>
                                            <?php if ($user->getAtivo()): ?>
                                                <span class="badge bg-success">Ativo</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inativo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary me-1 btn-editar-util"
                                                data-id="<?= $user->getId() ?>" data-id-role="<?= $user->getIdRole() ?>"
                                                data-nome="<?= htmlspecialchars($user->getNome(), ENT_QUOTES) ?>"
                                                data-email="<?= htmlspecialchars($user->getEmail(), ENT_QUOTES) ?>"
                                                data-morada="<?= htmlspecialchars($user->getMorada(), ENT_QUOTES) ?>"
                                                data-telefone="<?= htmlspecialchars($user->getTelefone(), ENT_QUOTES) ?>"
                                                data-nascimento="<?= htmlspecialchars($user->getDataNascimento(), ENT_QUOTES) ?>"
                                                data-ativo="<?= $user->getAtivo() ?>"
                                                data-mobilidade="<?= $user->getTemMobilidadeReduzida() ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger btn-eliminar-util"
                                                data-id="<?= $user->getId() ?>"
                                                data-nome="<?= htmlspecialchars($user->getNome(), ENT_QUOTES) ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
                <span style="background:#fde8e8;color:#e74c3c;border-radius:10px;padding:8px 10px;font-size:1.2rem;">
                    <i class="bi bi-thermometer-half"></i>
                </span>
                <div>
                    <div style="font-size:0.75rem;color:#999;">Temperatura</div>
                    <div class="fw-bold">22.5°C</div>
                </div>
            </div>
            <div class="list-group-item d-flex align-items-center gap-3">
                <span style="background:#e8edff;color:#435ebe;border-radius:10px;padding:8px 10px;font-size:1.2rem;">
                    <i class="bi bi-droplet"></i>
                </span>
                <div>
                    <div style="font-size:0.75rem;color:#999;">Humidade</div>
                    <div class="fw-bold">65%</div>
                </div>
            </div>
            <div class="list-group-item d-flex align-items-center gap-3">
                <span style="background:#e6f9ef;color:#27ae60;border-radius:10px;padding:8px 10px;font-size:1.2rem;">
                    <i class="bi bi-wind"></i>
                </span>
                <div>
                    <div style="font-size:0.75rem;color:#999;">Qualidade do Ar</div>
                    <div class="fw-bold">Bom</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ADICIONAR UTILIZADOR -->
<div class="modal fade" id="modalUtilizador" tabindex="-1" aria-labelledby="modalUtilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/admin/create-utilizador" method="POST" id="formUtilizador">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                    <h5 class="modal-title" id="modalUtilLabel">Adicionar Utilizador</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nome Completo</label>
                        <input name="nome" type="text" class="form-control" placeholder="Ex: João Silva">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input name="email" type="email" class="form-control" placeholder="Ex: joao@email.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Cargo</label>
                        <select name="id_role" class="form-select">
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role->getIdRole() ?>">
                                    <?= htmlspecialchars($role->getNomeRole()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Morada</label>
                        <input name="morada" type="text" class="form-control" placeholder="Ex: Rua das Flores, Porto">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Password <span class="text-muted fw-normal">(deixar em branco para definir depois)</span>
                        </label>
                        <input name="password" type="password" class="form-control" placeholder="Nova password">
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

<!-- MODAL ADICIONAR ROLE -->
<div class="modal fade" id="modalRole" tabindex="-1" aria-labelledby="modalRoleLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/admin/create-role" method="POST" id="formRole">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                    <h5 class="modal-title" id="modalRoleLabel">Adicionar Role</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nome da Role</label>
                        <input name="nome" type="text" class="form-control" placeholder="Ex: Moderador">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Cor</label>
                        <div class="d-flex align-items-center gap-3">
                            <input name="cor" type="color" class="form-control form-control-color" value="#435ebe"
                                style="width:60px;height:40px;padding:2px;">
                            <span class="text-muted" style="font-size:0.85rem;">
                                Cor associada à role (usada em badges e filtros)
                            </span>
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


<!-- MODAL EDITAR UTILIZADOR -->
<div class="modal fade" id="modalEditarUtilizador" tabindex="-1" aria-labelledby="modalEditarUtilLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="/admin/update-utilizador" method="POST" id="formEditarUtilizador">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                    <h5 class="modal-title" id="modalEditarUtilLabel">
                        <i class="bi bi-pencil-square me-2"></i>Editar Utilizador
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nome Completo</label>
                            <input name="nome" id="edit_nome" type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input name="email" id="edit_email" type="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Cargo</label>
                            <select name="id_role" id="edit_id_role" class="form-select">
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role->getIdRole() ?>">
                                        <?= htmlspecialchars($role->getNomeRole()) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Morada</label>
                            <input name="morada" id="edit_morada" type="text" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Telefone</label>
                            <input name="telefone" id="edit_telefone" type="text" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Data de Nascimento</label>
                            <input name="data_nascimento" id="edit_nascimento" type="date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Estado</label>
                            <select name="ativo" id="edit_ativo" class="form-select">
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mobilidade Reduzida</label>
                            <select name="tem_mobilidade_reduzida" id="edit_mobilidade" class="form-select">
                                <option value="0">Não</option>
                                <option value="1">Sim</option>
                            </select>
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
<!-- MODAL CONFIRMAR ELIMINAÇÃO -->
<div class="modal fade" id="modalEliminarUtilizador" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>Confirmar Eliminação
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-person-x" style="font-size:3rem;color:#e74c3c;"></i>
                <p class="mt-3 mb-1">Tens a certeza que queres eliminar o utilizador</p>
                <p class="fw-bold fs-5" id="eliminar_util_nome"></p>
                <p class="text-muted" style="font-size:0.85rem;">Esta ação é irreversível.</p>
                <input type="hidden" id="eliminar_util_id">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">
                    <i class="bi bi-trash me-1"></i>Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($_SESSION['toast'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showToast(<?= json_encode($_SESSION['toast']['message']) ?>, <?= json_encode($_SESSION['toast']['type']) ?>);
        });
    </script>
    <?php unset($_SESSION['toast']); ?>
<?php endif; ?>

<?php include __DIR__ . "/../../includes/footer.php"; ?>