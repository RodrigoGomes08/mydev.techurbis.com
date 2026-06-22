(function () {
  // =========================
  // TOAST (helper global do ficheiro)
  // =========================
  // Usado quando uma ação é feita via fetch/AJAX e não há redirect com
  // $_SESSION['toast'] (ex: eliminar via modal sem reload da página).
  function mostrarToastJS(tipo, mensagem) {
    const container = document.getElementById("toast-container");
    if (!container) return;
    const id = "toast-" + Date.now();
    const bg = tipo === "success" ? "bg-success" : "bg-danger";
    const icon = tipo === "success" ? "bi-check-circle" : "bi-x-circle";
    container.insertAdjacentHTML("beforeend", `
    <div id="${id}" class="toast align-items-center text-white ${bg} border-0 show" role="alert">
      <div class="d-flex">
        <div class="toast-body">
          <i class="bi ${icon} me-2"></i>${mensagem}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  `);
    setTimeout(() => document.getElementById(id)?.remove(), 4000);
  }

  document.addEventListener("DOMContentLoaded", function () {
    const root = document.body;

    // =========================
    // TOAST PENDENTE (após reload de ação AJAX como eliminar parque)
    // =========================
    const toastPendente = sessionStorage.getItem('toastPendente');
    if (toastPendente) {
      sessionStorage.removeItem('toastPendente');
      try {
        const { tipo, mensagem } = JSON.parse(toastPendente);
        mostrarToastJS(tipo, mensagem);
      } catch (_) { }
    }

    // =========================
    // MENU LATERAL — ACTIVE
    // =========================
    const path = window.location.pathname;
    document.querySelectorAll('.sidebar-admin .nav-link').forEach(link => {
      link.classList.remove('active');
      const href = link.getAttribute('href') || '';
      if (href && href !== '/' && path.toLowerCase().includes(href.toLowerCase())) {
        link.classList.add('active');
      }
    });

    // =========================
    // NOTIFICAÇÕES (SIMULADAS)
    // =========================
    let notificacoes = 0;
    const notifCount = document.getElementById("notifCount");
    const notifList = document.getElementById("notifList");

    function adicionarNotificacao(mensagem) {
      notificacoes++;
      if (notifCount) notifCount.textContent = notificacoes;

      if (notificacoes === 1 && notifList) {
        notifList.innerHTML = "";
      }

      if (notifList) {
        const item = document.createElement("li");
        item.className = "dropdown-item";
        item.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <span>${mensagem}</span>
                    <small class="text-muted">Agora</small>
                </div>
            `;
        notifList.prepend(item);
      }
    }

    setTimeout(() => adicionarNotificacao("🗑️ Contentor C atingiu 95%"), 3000);
    setTimeout(() => adicionarNotificacao("💡 Poste 2 ficou offline"), 7000);
    setTimeout(
      () => adicionarNotificacao("🅿️ Parque Norte quase cheio"),
      11000,
    );

    // =========================
    // ATUALIZAR HORA
    // =========================
    const updateTime = document.getElementById("update-time");
    if (updateTime) {
      setInterval(() => {
        const agora = new Date();
        updateTime.textContent =
          agora.toLocaleDateString("pt-PT") +
          " — " +
          agora.toLocaleTimeString("pt-PT");
      }, 1000);
    }

    // =========================
    // PROGRESS BARS
    // =========================
    document.querySelectorAll(".progress-bar-custom").forEach((bar) => {
      const value = parseInt(bar.dataset.value, 10);
      if (isNaN(value)) return;
      bar.style.width = value + "%";
      bar.textContent = value + "%";
      bar.classList.remove(
        "progress-bar-success",
        "progress-bar-warning",
        "progress-bar-danger",
      );
      if (value <= 35) bar.classList.add("progress-bar-success");
      else if (value <= 80) bar.classList.add("progress-bar-warning");
      else bar.classList.add("progress-bar-danger");
    });

    // =========================
    // ALERTAS (página Geral)
    // =========================
    const listaAlertas = document.getElementById("lista-alertas");
    if (listaAlertas) {
      const alertas = [
        "🗑️ Contentor C com 90% de capacidade",
        "💡 Poste 2 encontra-se avariado",
        "🅿️ Parque Norte com 85% de ocupação",
        "🗑️ Contentor B precisa de esvaziamento",
        "⚡ Poste 4 manutenção agendada",
      ];
      listaAlertas.innerHTML = "";
      alertas.forEach((alerta) => {
        const li = document.createElement("li");
        li.className = "list-group-item";
        li.textContent = alerta;
        listaAlertas.appendChild(li);
      });
    }

    const ultimaAtualizacao = document.getElementById("ultima-atualizacao");
    if (ultimaAtualizacao) {
      const agora = new Date();
      ultimaAtualizacao.textContent = `Última atualização: ${agora.toLocaleDateString("pt-PT")} — ${agora.toLocaleTimeString("pt-PT")}`;
    }

    // =========================
    // POSTES (server-side)
    // =========================
    // A tabela é renderizada pelo PHP. Este bloco trata dos modais de editar e eliminar.

    const secPostes = document.getElementById("postes");
    if (secPostes) {
      // Modal editar
      const modalEditarPosteEl = document.getElementById("modalEditarPoste");
      if (modalEditarPosteEl) {
        const modalEditarPoste = new bootstrap.Modal(modalEditarPosteEl);

        document.addEventListener("click", function (e) {
          const btn = e.target.closest(".btn-editar-poste");
          if (!btn) return;

          const d = btn.dataset;
          document.getElementById("edit_poste_id").value = d.id;
          document.getElementById("edit_poste_longitude").value = d.longitude;
          document.getElementById("edit_poste_latitude").value = d.latitude;
          document.getElementById("edit_poste_id_freguesia").value = d.idFreguesia;
          document.getElementById("edit_poste_id_estado").value = d.idEstado;
          document.getElementById("edit_poste_observacao").value = d.observacao;

          modalEditarPoste.show();
        });
      }

      // Modal eliminar
      const modalEliminarPosteEl = document.getElementById("modalEliminarPoste");
      if (modalEliminarPosteEl) {
        const modalEliminarPoste = new bootstrap.Modal(modalEliminarPosteEl);
        let rowPosteParaEliminar = null;

        document.addEventListener("click", function (e) {
          const btn = e.target.closest(".btn-eliminar-poste");
          if (!btn) return;

          const posteId = btn.dataset.id;
          document.getElementById("eliminar_poste_id_display").textContent = "#" + posteId;
          document.getElementById("eliminar_poste_id").value = posteId;
          rowPosteParaEliminar = btn.closest("tr");

          modalEliminarPoste.show();
        });

        document.getElementById("btnConfirmarEliminarPoste")?.addEventListener("click", function () {
          const posteId = document.getElementById("eliminar_poste_id").value;

          fetch(`/admin/delete-poste/${posteId}`, { method: "POST" })
            .then(r => r.json())
            .then(data => {
              modalEliminarPoste.hide();
              if (data.success) {
                rowPosteParaEliminar?.remove();
                rowPosteParaEliminar = null;
                mostrarToastJS("success", data.message || "Poste eliminado com sucesso!");
              } else {
                mostrarToastJS("error", data.message || "Erro ao eliminar poste.");
              }
            })
            .catch(() => {
              modalEliminarPoste.hide();
              mostrarToastJS("error", "Erro de ligação ao servidor.");
            });
        });
      }
    }

    // =========================
    // PARQUES — ver bloco "server-side" mais abaixo (fora do DOMContentLoaded)
    // =========================
    // (O bloco antigo com dados mock e #gridParques foi removido — os parques
    // são agora renderizados pelo PHP a partir da base de dados.)

    // =========================
    // UTILIZADORES (server-side)
    // =========================
    // Os modais #modalUtilizador e #modalRole são abertos directamente via
    // data-bs-toggle/data-bs-target no HTML — não é necessário código JS para os abrir.
    // Este bloco trata apenas da pesquisa e filtro de cargo na tabela server-side.

    const secUtilizadores = document.getElementById("utilizadores");
    if (secUtilizadores) {
      // Pesquisa em tempo real por nome / email
      document
        .getElementById("searchUtil")
        ?.addEventListener("input", function () {
          const termo = this.value.toLowerCase();
          document
            .querySelectorAll("table.table-admin tbody tr[data-role]")
            .forEach((row) => {
              row.style.display = row.innerText.toLowerCase().includes(termo)
                ? ""
                : "none";
            });
        });

      // Filtro por cargo (role)
      document
        .getElementById("filtroUtilCargo")
        ?.addEventListener("change", function () {
          const roleId = this.value;
          document
            .querySelectorAll("table.table-admin tbody tr[data-role]")
            .forEach((row) => {
              row.style.display =
                !roleId || row.dataset.role === roleId ? "" : "none";
            });
        });

      // Abrir modal de edição e popular com os dados do utilizador
      const modalEditarEl = document.getElementById("modalEditarUtilizador");
      if (modalEditarEl) {
        const modalEditar = new bootstrap.Modal(modalEditarEl);

        document.addEventListener("click", function (e) {
          const btn = e.target.closest(".btn-editar-util");
          if (!btn) return;

          const d = btn.dataset;

          document.getElementById("edit_id").value = d.id;
          document.getElementById("edit_nome").value = d.nome;
          document.getElementById("edit_email").value = d.email;
          document.getElementById("edit_morada").value = d.morada;
          document.getElementById("edit_telefone").value = d.telefone;
          document.getElementById("edit_nascimento").value = d.nascimento;

          // Selects — ativo e mobilidade
          const selectAtivo = document.getElementById("edit_ativo");
          selectAtivo.value = d.ativo;

          const selectMobilidade = document.getElementById("edit_mobilidade");
          selectMobilidade.value = d.mobilidade;

          // Select de role
          const selectRole = document.getElementById("edit_id_role");
          selectRole.value = d.idRole;

          modalEditar.show();
        });
      }

      // Eliminar utilizador — abrir modal de confirmação
      const modalEliminarEl = document.getElementById("modalEliminarUtilizador");
      if (modalEliminarEl) {
        const modalEliminar = new bootstrap.Modal(modalEliminarEl);
        let rowParaEliminar = null;

        document.addEventListener("click", function (e) {
          const btn = e.target.closest(".btn-eliminar-util");
          if (!btn) return;

          const userId = btn.dataset.id;
          const nome = btn.dataset.nome;

          // Preencher o modal com o nome do utilizador
          document.getElementById("eliminar_util_nome").textContent = nome || "este utilizador";
          document.getElementById("eliminar_util_id").value = userId;

          // Guardar a linha da tabela para a remover após sucesso
          rowParaEliminar = btn.closest("tr");

          modalEliminar.show();
        });

        // Confirmar eliminação
        document.getElementById("btnConfirmarEliminar")?.addEventListener("click", function () {
          const userId = document.getElementById("eliminar_util_id").value;

          fetch(`/admin/delete-utilizador/${userId}`, { method: "POST" })
            .then(r => r.json())
            .then(data => {
              modalEliminar.hide();
              if (data.success) {
                rowParaEliminar?.remove();
                rowParaEliminar = null;
                // Mostrar toast de sucesso (reutiliza o sistema existente de toasts se existir)
                mostrarToastJS("success", data.message || "Utilizador eliminado com sucesso!");
              } else {
                mostrarToastJS("error", data.message || "Erro ao eliminar utilizador.");
              }
            })
            .catch(() => {
              modalEliminar.hide();
              mostrarToastJS("error", "Erro de ligação ao servidor.");
            });
        });
      }

    }

    // =========================
    // CIDADE — botões info cards
    // =========================
    const secCidade = document.getElementById("cidade");
    if (secCidade) {
      document
        .querySelector(".btn-atualizar-ambiente")
        ?.addEventListener("click", function () {
          const vals = {
            temp: (20 + Math.random() * 8).toFixed(1) + "°C",
            humidade: Math.round(55 + Math.random() * 20) + "%",
            vento: Math.round(8 + Math.random() * 15) + " km/h",
          };
          const grid =
            this.closest(".parque-card")?.querySelector(".parque-info-grid");
          if (grid) {
            const items = grid.querySelectorAll(".parque-info-value");
            items[0].textContent = vals.temp;
            items[1].textContent = vals.humidade;
            items[2].textContent = "Bom";
            items[3].textContent = vals.vento;
          }
          this.innerHTML = '<i class="bi bi-check-circle me-1"></i>Atualizado!';
          setTimeout(() => {
            this.innerHTML =
              '<i class="bi bi-arrow-clockwise me-1"></i>Atualizar';
          }, 1500);
        });

      document
        .querySelector(".btn-historico-ambiente")
        ?.addEventListener("click", function () {
          const el = document.getElementById("modalHistoricoLabel");
          if (el) el.textContent = "Histórico Ambiental — Últimas 24h";
          const body = document.getElementById("modalHistoricoBody");
          if (body) {
            const horas = [
              "00:00",
              "04:00",
              "08:00",
              "12:00",
              "16:00",
              "20:00",
              "Agora",
            ];
            const temps = [18.2, 17.5, 19.1, 22.5, 23.8, 21.4, 22.5];
            body.innerHTML = `
                <table class="table table-sm table-hover">
                    <thead><tr><th>Hora</th><th>Temperatura</th><th>Humidade</th><th>Qualidade Ar</th></tr></thead>
                    <tbody>${horas.map((h, i) => `<tr><td>${h}</td><td>${temps[i]}°C</td><td>${Math.round(60 + Math.random() * 15)}%</td><td>Bom</td></tr>`).join("")}</tbody>
                </table>`;
          }
          new bootstrap.Modal(document.getElementById("modalHistorico")).show();
        });

      document
        .querySelector(".btn-mapa-trafego")
        ?.addEventListener("click", function () {
          const titleEl = document.getElementById("modalMapaCidadeTitle");
          const iframeEl = document.getElementById("iframeMapaCidade");
          if (titleEl) titleEl.textContent = "Mapa de Tráfego Pedonal";
          if (iframeEl)
            iframeEl.src =
              "https://www.google.com/maps?q=38.7223,-9.1393&z=14&output=embed";
          new bootstrap.Modal(
            document.getElementById("modalMapaCidade"),
          ).show();
        });

      document
        .querySelector(".btn-detalhe-trafego")
        ?.addEventListener("click", function () {
          const el = document.getElementById("modalHistoricoLabel");
          if (el) el.textContent = "Detalhes — Tráfego Pedonal";
          const body = document.getElementById("modalHistoricoBody");
          if (body)
            body.innerHTML = `
                <div class="row g-3">
                    ${[
                ["Zona Centro", "Alto", "danger"],
                ["Zona Norte", "Médio", "warning"],
                ["Zona Sul", "Baixo", "success"],
                ["Zona Industrial", "Baixo", "success"],
              ]
                .map(
                  ([z, n, c]) => `
                    <div class="col-6"><div class="pkpi-card flex-column text-center p-3"><div class="pkpi-value text-${c}">${n}</div><div class="pkpi-label">${z}</div></div></div>`,
                )
                .join("")}
                </div>`;
          new bootstrap.Modal(document.getElementById("modalHistorico")).show();
        });

      document
        .querySelector(".btn-calendario-residuos")
        ?.addEventListener("click", function () {
          const el = document.getElementById("modalHistoricoLabel");
          if (el) el.textContent = "Calendário de Recolha — Esta Semana";
          const body = document.getElementById("modalHistoricoBody");
          if (body)
            body.innerHTML = `
                <table class="table table-sm table-hover">
                    <thead><tr><th>Dia</th><th>Zona</th><th>Tipo</th><th>Estado</th></tr></thead>
                    <tbody>
                        <tr><td>Seg</td><td>Centro</td><td>Indiferenciado</td><td><span class="badge bg-success">Concluído</span></td></tr>
                        <tr><td>Ter</td><td>Norte</td><td>Reciclagem</td><td><span class="badge bg-success">Concluído</span></td></tr>
                        <tr><td>Qua</td><td>Sul</td><td>Indiferenciado</td><td><span class="badge bg-warning text-dark">Em curso</span></td></tr>
                        <tr><td>Qui</td><td>Industrial</td><td>Orgânico</td><td><span class="badge bg-secondary">Agendado</span></td></tr>
                        <tr><td>Sex</td><td>Centro</td><td>Reciclagem</td><td><span class="badge bg-secondary">Agendado</span></td></tr>
                    </tbody>
                </table>`;
          new bootstrap.Modal(document.getElementById("modalHistorico")).show();
        });

      document
        .querySelector(".btn-detalhe-residuos")
        ?.addEventListener("click", function () {
          const el = document.getElementById("modalHistoricoLabel");
          if (el) el.textContent = "Detalhes — Gestão de Resíduos";
          const body = document.getElementById("modalHistoricoBody");
          if (body)
            body.innerHTML = `
                <div class="row g-3">
                    <div class="col-6"><div class="pkpi-card flex-column text-center p-3"><div class="pkpi-value text-success">7</div><div class="pkpi-label">Rotas Concluídas</div></div></div>
                    <div class="col-6"><div class="pkpi-card flex-column text-center p-3"><div class="pkpi-value text-warning">3</div><div class="pkpi-label">Rotas Pendentes</div></div></div>
                    <div class="col-6"><div class="pkpi-card flex-column text-center p-3"><div class="pkpi-value text-primary">38%</div><div class="pkpi-label">Taxa Reciclagem</div></div></div>
                    <div class="col-6"><div class="pkpi-card flex-column text-center p-3"><div class="pkpi-value">4.2 t</div><div class="pkpi-label">Resíduos Hoje</div></div></div>
                </div>`;
          new bootstrap.Modal(document.getElementById("modalHistorico")).show();
        });
    }
  }); // fim DOMContentLoaded

  // =========================
  // PARQUES (server-side, baseado em cards) — ver bloco mais abaixo
  // =========================
  // A pesquisa, filtros, mapa, detalhes, editar e eliminar de parques estão
  // implementados no bloco final deste ficheiro, alinhado com os cards
  // gerados por portalADMParques.php (não com uma tabela).

  // ── Botão Só Críticos ─────────────────────────────────────────────────────────
  const btnParquesCriticos = document.getElementById('btnParquesCriticos');
  if (btnParquesCriticos) {
    let soCriticos = false;
    btnParquesCriticos.addEventListener('click', function () {
      soCriticos = !soCriticos;
      this.classList.toggle('btn-outline-danger', !soCriticos);
      this.classList.toggle('btn-danger', soCriticos);
      document.querySelectorAll('[data-id][data-nome]').forEach(col => {
        const card = col.querySelector('.parque-card');
        if (!card) return;
        if (soCriticos) {
          col.style.display = card.classList.contains('critico') ? '' : 'none';
        } else {
          col.style.display = '';
        }
      });
    });
  }

  // ── Hora da última sincronização ─────────────────────────────────────────────
  const parqueSyncTimeEl = document.getElementById('parque-sync-time');
  if (parqueSyncTimeEl) parqueSyncTimeEl.textContent = new Date().toLocaleString('pt-PT');

  // ── Filtros ───────────────────────────────────────────────────────────────────
  function aplicarFiltroParques() {
    const texto = (document.getElementById('searchParque')?.value || '').toLowerCase();
    const tipo = document.getElementById('filtroParqueTipo')?.value || '';

    document.querySelectorAll('[data-id][data-nome]').forEach(col => {
      const matchNome = !texto || col.dataset.nome.toLowerCase().includes(texto);
      const matchTipo = !tipo || col.dataset.tipo === tipo;
      col.style.display = (matchNome && matchTipo) ? '' : 'none';
    });
  }

  document.getElementById('searchParque')?.addEventListener('input', aplicarFiltroParques);
  document.getElementById('filtroParqueTipo')?.addEventListener('change', aplicarFiltroParques);

  // ── Accordion manual — fechar o aberto ao abrir outro ────────────────────────
  document.addEventListener('DOMContentLoaded', function () {
    const accordionParques = document.getElementById('accordionParques');
    if (!accordionParques) return;

    // Inicializar todas as instâncias de collapse manualmente
    accordionParques.querySelectorAll('.collapse').forEach(function (el) {
      bootstrap.Collapse.getOrCreateInstance(el, { toggle: false });
    });

    accordionParques.addEventListener('show.bs.collapse', function (e) {
      accordionParques.querySelectorAll('.collapse.show').forEach(function (openPanel) {
        if (openPanel !== e.target) {
          bootstrap.Collapse.getOrCreateInstance(openPanel).hide();
        }
      });
    });
  });

  // ── Modal Mapa ─────────────────────────────────────────────────────────────────
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-mapa-parque');
    if (!btn) return;
    const lat = btn.dataset.lat;
    const lng = btn.dataset.lng;
    const nome = btn.dataset.nome;
    const titleEl = document.getElementById('modalMapaParqueTitle');
    const iframeEl = document.getElementById('mapaParqueIframe');
    if (titleEl) titleEl.textContent = nome + ' — Localização';
    if (iframeEl) iframeEl.src = `https://www.google.com/maps?q=${lat},${lng}&z=16&output=embed`;
  });

  // ── Modal Detalhes ─────────────────────────────────────────────────────────────
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-detalhe-parque');
    if (!btn) return;
    const col = btn.closest('[data-id]');
    if (!col) return;

    const nome = col.dataset.nome || '—';
    const tipo = col.dataset.tipo || '—';
    const lugares = col.dataset.numMaxLugares || '—';
    const tarifa = col.dataset.tarifa || '0';
    const lat = col.dataset.latitude || '—';
    const lng = col.dataset.longitude || '—';
    const tarifaStr = parseFloat(tarifa) === 0 ? 'Gratuito' : parseFloat(tarifa).toFixed(2) + ' €/h';

    const tipoIconMap = { 'Coberto': 'bi-building', 'Subterrâneo': 'bi-layers-fill', 'Descoberto': 'bi-sun' };
    const icon = tipoIconMap[tipo] || 'bi-p-circle';

    const tituloEl = document.getElementById('modalDetalheParqueTitle');
    const bodyEl = document.getElementById('modalDetalheParqueBody');
    if (tituloEl) tituloEl.textContent = nome + ' — Detalhes';
    if (bodyEl) bodyEl.innerHTML = `
        <div class="text-center mb-3">
            <div class="pkpi-icon mx-auto mb-2" style="background:#f0f3ff;color:#435ebe;width:60px;height:60px;border-radius:14px;font-size:1.8rem;display:flex;align-items:center;justify-content:center;">
                <i class="bi ${icon}"></i>
            </div>
            <div style="font-size:2.8rem;font-weight:800;" class="text-primary">${lugares}</div>
            <div class="text-muted">lugares disponíveis</div>
        </div>
        <table class="table table-sm table-borderless" style="font-size:0.9rem;">
            <tr><td class="text-muted">Nome</td><td><strong>${nome}</strong></td></tr>
            <tr><td class="text-muted">Tipo</td><td>${tipo}</td></tr>
            <tr><td class="text-muted">Capacidade Máx.</td><td>${lugares} lugares</td></tr>
            <tr><td class="text-muted">Tarifa</td><td><strong>${tarifaStr}</strong></td></tr>
            <tr><td class="text-muted">Coordenadas</td><td>${lat}, ${lng}</td></tr>
            <tr><td class="text-muted">Última sincronização</td><td><strong>${new Date().toLocaleString('pt-PT')}</strong></td></tr>
        </table>`;
    const modalDetalheEl = document.getElementById('modalDetalheParque');
    if (modalDetalheEl) new bootstrap.Modal(modalDetalheEl).show();
  });

  // ── Modal Editar ──────────────────────────────────────────────────────────────
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-editar-parque');
    if (!btn) return;
    const d = btn.dataset;

    const idEl = document.getElementById('edit-parque-id');
    if (!idEl) return; // não estamos na página de Parques

    idEl.value = d.id;
    document.getElementById('edit-parque-id-freguesia').value = d.idFreguesia;
    document.getElementById('edit-parque-nome').value = d.nome;
    document.getElementById('edit-parque-num-max-lugares').value = d.numMaxLugares;
    document.getElementById('edit-parque-tipo').value = d.tipo;
    document.getElementById('edit-parque-tarifa').value = d.tarifa;
    document.getElementById('edit-parque-latitude').value = d.latitude;
    document.getElementById('edit-parque-longitude').value = d.longitude;
  });

  // ── Eliminar parque ────────────────────────────────────────────────────────────
  const modalEliminarParqueEl = document.getElementById('modalEliminarParque');
  if (modalEliminarParqueEl) {
    const modalEliminarParque = new bootstrap.Modal(modalEliminarParqueEl);
    let parqueAEliminar = null;
    let cardParqueAEliminar = null;

    document.addEventListener('click', function (e) {
      const btn = e.target.closest('.btn-eliminar-parque');
      if (!btn) return;

      parqueAEliminar = btn.dataset.id;
      cardParqueAEliminar = btn.closest('[data-id]');
      const nomeEl = document.getElementById('eliminar_parque_nome');
      if (nomeEl) nomeEl.textContent = btn.dataset.nome || '';

      modalEliminarParque.show();
    });

    document.getElementById('btnConfirmarEliminarParque')?.addEventListener('click', function () {
      if (!parqueAEliminar) return;
      const confirmBtn = this;
      confirmBtn.disabled = true;
      confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

      fetch(`/admin/delete-parque/${parqueAEliminar}`, { method: 'POST' })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            sessionStorage.setItem('toastPendente', JSON.stringify({ tipo: 'success', mensagem: data.message || 'Parque eliminado com sucesso!' }));
            // Aguarda o Bootstrap fechar completamente o modal antes de fazer reload,
            // caso contrário o backdrop fica "preso" no ecrã após a navegação.
            modalEliminarParqueEl.addEventListener('hidden.bs.modal', () => location.reload(), { once: true });
            modalEliminarParque.hide();
          } else {
            modalEliminarParque.hide();
            mostrarToastJS('error', data.message || 'Erro ao eliminar parque.');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="bi bi-trash me-1"></i>Eliminar';
          }
        })
        .catch(() => {
          modalEliminarParque.hide();
          mostrarToastJS('error', 'Erro de ligação ao servidor.');
          confirmBtn.disabled = false;
          confirmBtn.innerHTML = '<i class="bi bi-trash me-1"></i>Eliminar';
        });
    });
  }
})();