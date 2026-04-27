<?php include __DIR__ . "/../../includes/header.php"; ?>

    <main class="content-admin">
        <section id="postes" class="adm-page active">

            <!-- CABEÇALHO -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h2 class="mb-1">Postes de Iluminação</h2>
                    <p class="text-muted mb-0" style="font-size:0.9rem;">
                        <i class="bi bi-clock me-1"></i>Última sincronização: <span id="poste-sync-time">—</span>
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-outline-danger btn-sm" id="btnPostesAvariados">
                        <i class="bi bi-exclamation-triangle me-1"></i>Só Avariados
                    </button>
                    <button class="btn btn-success btn-sm" id="btnNovoPoste">
                        <i class="bi bi-plus-circle me-1"></i>Adicionar Poste
                    </button>
                </div>
            </div>

            <!-- KPIs -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#e8edff;color:#435ebe;"><i class="bi bi-lightbulb-fill"></i></div>
                        <div><div class="pkpi-value" id="pkpi-postes-total">—</div><div class="pkpi-label">Total de Postes</div></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#e6f9ef;color:#27ae60;"><i class="bi bi-check-circle-fill"></i></div>
                        <div><div class="pkpi-value text-success" id="pkpi-postes-operacionais">—</div><div class="pkpi-label">Operacionais</div></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#fde8e8;color:#e74c3c;"><i class="bi bi-x-circle-fill"></i></div>
                        <div><div class="pkpi-value text-danger" id="pkpi-postes-avariados">—</div><div class="pkpi-label">Avariados</div></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="pkpi-card">
                        <div class="pkpi-icon" style="background:#fff4e0;color:#f39c12;"><i class="bi bi-tools"></i></div>
                        <div><div class="pkpi-value text-warning" id="pkpi-postes-manutencao">—</div><div class="pkpi-label">Em Manutenção</div></div>
                    </div>
                </div>
            </div>

            <!-- FILTRO -->
            <div class="d-flex gap-2 mb-4 flex-wrap">
                <div class="input-group" style="max-width:300px;">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input id="searchInput" type="text" class="form-control border-start-0 ps-0" placeholder="Pesquisar..." disabled>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <span id="campoLabel">Campo</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-campo="estado">Estado</a></li>
                        <li><a class="dropdown-item" href="#" data-campo="observacoes">Observações</a></li>
                    </ul>
                </div>
                <button id="btnPesquisar" class="btn btn-primary" disabled>Pesquisar</button>
                <button id="btnLimpar" class="btn btn-outline-secondary">Limpar</button>
            </div>

            <!-- TABELA -->
            <div class="card-admin">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-admin table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Longitude</th>
                                    <th>Latitude</th>
                                    <th>Estado</th>
                                    <th>Observações</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="listaPostes"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- MODAL ADICIONAR / EDITAR -->
        <div class="modal fade" id="modalPoste" tabindex="-1" aria-labelledby="modalPosteLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background:var(--primary-gradient);color:white;">
                        <h5 class="modal-title" id="modalPosteLabel">Adicionar Poste</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="posteIndex">
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Longitude</label>
                                <input type="number" step="any" class="form-control" id="inputLongitude" placeholder="Ex: -8.6291">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold">Latitude</label>
                                <input type="number" step="any" class="form-control" id="inputLatitude" placeholder="Ex: 41.1579">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Estado</label>
                            <select class="form-select" id="inputEstado">
                                <option value="operacional">Operacional</option>
                                <option value="avariado">Avariado</option>
                                <option value="manutencao">Manutenção</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Observações</label>
                            <textarea class="form-control" id="inputObservacoes" rows="3" placeholder="Descrição do estado ou ocorrência..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-primary" id="btnGuardarPoste">
                            <i class="bi bi-floppy me-1"></i>Guardar
                        </button>
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

<?php include __DIR__ . "/../../includes/footer.php"; ?>
<script>
    // if (!sessionStorage.getItem('loggedIn')) { window.location.href = 'login.html'; }

    // // Sync time
    // const syncEl = document.getElementById('poste-sync-time');
    // if (syncEl) syncEl.textContent = new Date().toLocaleString('pt-PT');

    // KPI counters after scriptsPortal loads postes
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            const rows = document.querySelectorAll('#listaPostes tr');
            let op = 0, av = 0, mn = 0;
            rows.forEach(r => {
                const badge = r.querySelector('.badge');
                if (!badge) return;
                const t = badge.textContent.trim().toLowerCase();
                if (t.includes('operacional')) op++;
                else if (t.includes('avariado')) av++;
                else mn++;
            });
            const total = op + av + mn;
            document.getElementById('pkpi-postes-total').textContent = total || '—';
            document.getElementById('pkpi-postes-operacionais').textContent = op || '—';
            document.getElementById('pkpi-postes-avariados').textContent = av || '—';
            document.getElementById('pkpi-postes-manutencao').textContent = mn || '—';
        }, 200);
    });

    // Filtro só avariados
    document.getElementById('btnPostesAvariados')?.addEventListener('click', function () {
        const input = document.getElementById('searchInput');
        const campo = document.querySelector('[data-campo="estado"]');
        if (this.classList.contains('active')) {
            this.classList.remove('active','btn-danger');
            this.classList.add('btn-outline-danger');
            input.value = '';
            input.disabled = true;
            document.getElementById('btnPesquisar').disabled = true;
        } else {
            this.classList.add('active','btn-danger');
            this.classList.remove('btn-outline-danger');
            campo?.click();
            input.value = 'avariado';
            input.disabled = false;
            document.getElementById('btnPesquisar').click();
        }
    });
</script>
</body>
</html>
