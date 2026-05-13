(function () {
  document.addEventListener("DOMContentLoaded", function () {
    const root = document.body;

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
    // POSTES (só em PortalADMPostes.html)
    // =========================
    const listaPostes = document.getElementById("listaPostes");
    const campoLabel = document.getElementById("campoLabel");
    const searchInput = document.getElementById("searchInput");
    const btnPesquisar = document.getElementById("btnPesquisar");
    const btnLimpar = document.getElementById("btnLimpar");
    const btnNovoPoste = document.getElementById("btnNovoPoste");
    const modalPosteEl = document.getElementById("modalPoste");

    if (listaPostes && modalPosteEl) {
      let postes = [
        {
          longitude: -8.6291,
          latitude: 41.1579,
          estado: "operacional",
          observacoes: "Funcionamento normal",
        },
        {
          longitude: -8.61,
          latitude: 41.1496,
          estado: "avariado",
          observacoes: "Lâmpada fundida",
        },
        {
          longitude: -9.1393,
          latitude: 38.7223,
          estado: "operacional",
          observacoes: "Sem ocorrências",
        },
        {
          longitude: -8.4265,
          latitude: 40.211,
          estado: "manutencao",
          observacoes: "Verificação elétrica agendada",
        },
      ];

      const modal = new bootstrap.Modal(modalPosteEl);
      let campoSelecionado = null;

      const estadoConfig = {
        operacional: {
          cls: "badge-status-ok",
          icon: "bi-check-circle",
          label: "Operacional",
        },
        avariado: {
          cls: "badge-status-error",
          icon: "bi-x-circle",
          label: "Avariado",
        },
        manutencao: {
          cls: "badge-status-warning",
          icon: "bi-exclamation-circle",
          label: "Manutenção",
        },
      };

      function badgeHtml(estado) {
        const cfg = estadoConfig[estado] || estadoConfig.operacional;
        return `<span class="badge ${cfg.cls}"><i class="bi ${cfg.icon}"></i> ${cfg.label}</span>`;
      }

      function renderTabela(lista) {
        listaPostes.innerHTML = "";
        lista.forEach((p, i) => {
          const realIndex = postes.indexOf(p);
          const tr = document.createElement("tr");
          tr.innerHTML = `
                    <td><strong>#${i + 1}</strong></td>
                    <td>${p.longitude}</td>
                    <td>${p.latitude}</td>
                    <td>${badgeHtml(p.estado)}</td>
                    <td>${p.observacoes}</td>
                    <td class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-edit" data-index="${realIndex}">
                            <i class="bi bi-pencil me-1"></i>Editar
                        </button>
                        <button class="btn btn-outline-danger btn-remove" data-index="${realIndex}">
                            <i class="bi bi-trash me-1"></i>Remover
                        </button>
                    </td>`;
          listaPostes.appendChild(tr);
        });

        document.querySelectorAll(".btn-edit").forEach((btn) => {
          btn.addEventListener("click", function () {
            abrirModalEditar(parseInt(this.dataset.index));
          });
        });
        document.querySelectorAll(".btn-remove").forEach((btn) => {
          btn.addEventListener("click", function () {
            removerPoste(parseInt(this.dataset.index));
          });
        });
      }

      function removerPoste(index) {
        if (
          !confirm(`Tens a certeza que queres remover o Poste #${index + 1}?`)
        )
          return;
        postes.splice(index, 1);
        renderTabela(postes);
      }

      function abrirModalEditar(index) {
        const p = postes[index];
        document.getElementById("modalPosteLabel").textContent =
          `Editar Poste #${index + 1}`;
        document.getElementById("posteIndex").value = index;
        document.getElementById("inputLongitude").value = p.longitude;
        document.getElementById("inputLatitude").value = p.latitude;
        document.getElementById("inputEstado").value = p.estado;
        document.getElementById("inputObservacoes").value = p.observacoes;
        modal.show();
      }

      if (btnNovoPoste) {
        btnNovoPoste.addEventListener("click", () => {
          document.getElementById("modalPosteLabel").textContent =
            "Adicionar Poste";
          document.getElementById("posteIndex").value = "";
          document.getElementById("inputLongitude").value = "";
          document.getElementById("inputLatitude").value = "";
          document.getElementById("inputEstado").value = "operacional";
          document.getElementById("inputObservacoes").value = "";
          modal.show();
        });
      }

      document
        .getElementById("btnGuardarPoste")
        ?.addEventListener("click", () => {
          const longitude = parseFloat(
            document.getElementById("inputLongitude").value,
          );
          const latitude = parseFloat(
            document.getElementById("inputLatitude").value,
          );
          const estado = document.getElementById("inputEstado").value;
          const observacoes = document
            .getElementById("inputObservacoes")
            .value.trim();

          if (isNaN(longitude) || isNaN(latitude)) {
            alert("Por favor preenche a longitude e latitude corretamente.");
            return;
          }

          const index = document.getElementById("posteIndex").value;
          if (index === "")
            postes.push({ longitude, latitude, estado, observacoes });
          else
            postes[parseInt(index)] = {
              longitude,
              latitude,
              estado,
              observacoes,
            };

          modal.hide();
          renderTabela(postes);
        });

      // Filtro
      const nomesAmigaveis = { estado: "Estado", observacoes: "Observações" };

      document.querySelectorAll("[data-campo]").forEach((item) => {
        item.addEventListener("click", function (e) {
          e.preventDefault();
          campoSelecionado = this.dataset.campo;
          campoLabel.textContent = nomesAmigaveis[campoSelecionado];
          searchInput.disabled = false;
          searchInput.placeholder = `Pesquisar por ${nomesAmigaveis[campoSelecionado].toLowerCase()}...`;
          searchInput.focus();
          btnPesquisar.disabled = false;
        });
      });

      if (searchInput)
        searchInput.addEventListener("keydown", (e) => {
          if (e.key === "Enter") btnPesquisar.click();
        });

      if (btnPesquisar)
        btnPesquisar.addEventListener("click", () => {
          const valor = searchInput.value.trim().toLowerCase();
          const resultado = valor
            ? postes.filter((p) =>
                String(p[campoSelecionado]).toLowerCase().includes(valor),
              )
            : postes;
          renderTabela(resultado);
        });

      if (btnLimpar)
        btnLimpar.addEventListener("click", () => {
          campoSelecionado = null;
          campoLabel.textContent = "Campo";
          searchInput.value = "";
          searchInput.disabled = true;
          searchInput.placeholder = "Pesquisar...";
          btnPesquisar.disabled = true;
          renderTabela(postes);
        });

      renderTabela(postes);
    }

    // =========================
    // PARQUES (só em PortalADMParques.html)
    // =========================
    const gridParques = document.getElementById("gridParques");

    if (gridParques) {
      let parques = [
        {
          nome: "Parque Central",
          capacidade: 250,
          ocupados: 100,
          tipo: "Subterrâneo",
          tarifa: 1.5,
          morada: "Av. Central, Loures",
          horario: "00h–24h",
          mr: 8,
          ev: 4,
          lat: 38.83,
          lng: -9.17,
        },
        {
          nome: "Parque Norte",
          capacidade: 180,
          ocupados: 153,
          tipo: "Coberto",
          tarifa: 1.0,
          morada: "Rua do Norte, Loures",
          horario: "07h–22h",
          mr: 6,
          ev: 2,
          lat: 38.835,
          lng: -9.168,
        },
        {
          nome: "Parque Sul",
          capacidade: 120,
          ocupados: 42,
          tipo: "Descoberto",
          tarifa: 0.5,
          morada: "Rua do Sul, Sacavém",
          horario: "08h–20h",
          mr: 4,
          ev: 0,
          lat: 38.79,
          lng: -9.11,
        },
        {
          nome: "Parque Oriente",
          capacidade: 300,
          ocupados: 261,
          tipo: "Subterrâneo",
          tarifa: 2.0,
          morada: "Av. Oriente, Moscavide",
          horario: "00h–24h",
          mr: 10,
          ev: 6,
          lat: 38.77,
          lng: -9.1,
        },
        {
          nome: "Parque Camarate",
          capacidade: 90,
          ocupados: 38,
          tipo: "Descoberto",
          tarifa: 0.0,
          morada: "Largo Principal, Camarate",
          horario: "00h–24h",
          mr: 3,
          ev: 0,
          lat: 38.8029,
          lng: -9.1175,
        },
        {
          nome: "Parque Santa Iria",
          capacidade: 200,
          ocupados: 148,
          tipo: "Coberto",
          tarifa: 1.2,
          morada: "Rua das Acácias, Santa Iria",
          horario: "06h–23h",
          mr: 7,
          ev: 3,
          lat: 38.87,
          lng: -9.07,
        },
      ];

      function getPct(p) {
        return Math.round((p.ocupados / p.capacidade) * 100);
      }
      function getClasse(pct) {
        if (pct > 80) return "critico";
        if (pct >= 50) return "atencao";
        return "normal";
      }
      function getTipoIcon(tipo) {
        return tipo === "Subterrâneo" ? "🏗️" : tipo === "Coberto" ? "🏢" : "☀️";
      }

      function atualizarKpis() {
        const total = parques.length;
        const cheios = parques.filter((p) => getPct(p) > 80).length;
        const media = Math.round(
          parques.reduce((s, p) => s + getPct(p), 0) / total,
        );
        const livres = parques.reduce(
          (s, p) => s + (p.capacidade - p.ocupados),
          0,
        );

        const elTotal = document.getElementById("pkpi-total");
        const elCheios = document.getElementById("pkpi-cheios");
        const elMedia = document.getElementById("pkpi-media");
        const elLivres = document.getElementById("pkpi-livres");

        if (elTotal) elTotal.textContent = total;
        if (elCheios) elCheios.textContent = cheios;
        if (elMedia) elMedia.textContent = media + "%";
        if (elLivres) elLivres.textContent = livres;

        const sync = document.getElementById("parque-sync-time");
        if (sync) {
          const now = new Date();
          sync.textContent =
            now.toLocaleDateString("pt-PT") +
            " — " +
            now.toLocaleTimeString("pt-PT");
        }
      }

      function renderAlertas(lista) {
        const box = document.getElementById("alertasParques");
        if (!box) return;
        const criticos = lista.filter((p) => getPct(p) > 80);
        const atencao = lista.filter((p) => {
          const pct = getPct(p);
          return pct >= 70 && pct <= 80;
        });
        let html = "";
        criticos.forEach((p) => {
          html += `<div class="alert alert-admin alert-danger d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-exclamation-octagon-fill fs-5"></i>
                    <div><strong>${p.nome}</strong> está com ${getPct(p)}% de ocupação — considere desviar tráfego para alternativas próximas.</div>
                </div>`;
        });
        atencao.forEach((p) => {
          html += `<div class="alert alert-admin alert-warning d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div><strong>${p.nome}</strong> está com ${getPct(p)}% de ocupação — monitorizar.</div>
                </div>`;
        });
        box.innerHTML = html;
      }

      function renderParques(lista) {
        gridParques.innerHTML = "";
        lista.forEach((p) => {
          const realIndex = parques.indexOf(p);
          const pct = getPct(p);
          const cls = getClasse(pct);
          const livres = p.capacidade - p.ocupados;
          const tarifaStr =
            p.tarifa === 0 ? "Gratuito" : `${p.tarifa.toFixed(2)} €/h`;

          let alertaHtml = "";
          if (cls === "critico") {
            alertaHtml = `<div class="parque-alerta"><i class="bi bi-exclamation-octagon-fill"></i> Capacidade crítica — redirecionamento recomendado</div>`;
          } else if (pct >= 70) {
            alertaHtml = `<div class="parque-alerta atencao"><i class="bi bi-exclamation-triangle-fill"></i> Ocupação elevada — a monitorizar</div>`;
          }

          const col = document.createElement("div");
          col.className = "col-12 col-md-6 col-xl-4 parque-col";
          col.dataset.nome = p.nome.toLowerCase();
          col.dataset.classe = cls;
          col.dataset.tipo = p.tipo;

          col.innerHTML = `
                <div class="parque-card ${cls} h-100">
                    <div class="parque-card-header">
                        <div class="parque-titulo">
                            <span>${p.nome}</span>
                            <span class="badge ${cls === "critico" ? "bg-danger" : cls === "atencao" ? "bg-warning text-dark" : "bg-success"}" style="font-size:0.75rem;">
                                ${cls === "critico" ? "Crítico" : cls === "atencao" ? "Atenção" : "Normal"}
                            </span>
                        </div>
                        <div class="parque-subtitulo">
                            <i class="bi bi-geo-alt"></i> ${p.morada}
                        </div>
                    </div>
                    <div class="parque-ocupacao-wrap">
                        <div class="d-flex align-items-end justify-content-between mb-1">
                            <div>
                                <span class="parque-pct-num ${cls}">${pct}%</span>
                                <span style="font-size:0.82rem;color:#aaa;margin-left:4px;">ocupado</span>
                            </div>
                            <div class="text-end">
                                <div style="font-size:1.1rem;font-weight:700;color:#2c3e50;">${livres}</div>
                                <div style="font-size:0.75rem;color:#aaa;">lugares livres</div>
                            </div>
                        </div>
                        <div class="parque-prog">
                            <div class="parque-prog-bar ${cls}" style="width:${pct}%"></div>
                        </div>
                        <div class="parque-lugares-label">${p.ocupados} / ${p.capacidade} lugares ocupados</div>
                    </div>
                    ${alertaHtml}
                    <div class="parque-info-grid">
                        <div class="parque-info-item">
                            <span class="parque-info-label"><i class="bi bi-clock me-1"></i>Horário</span>
                            <span class="parque-info-value">${p.horario}</span>
                        </div>
                        <div class="parque-info-item">
                            <span class="parque-info-label"><i class="bi bi-currency-euro me-1"></i>Tarifa</span>
                            <span class="parque-info-value">${tarifaStr}</span>
                        </div>
                        <div class="parque-info-item">
                            <span class="parque-info-label"><i class="bi bi-building me-1"></i>Tipo</span>
                            <span class="parque-info-value">${getTipoIcon(p.tipo)} ${p.tipo}</span>
                        </div>
                        <div class="parque-info-item">
                            <span class="parque-info-label"><i class="bi bi-people me-1"></i>Capacidade</span>
                            <span class="parque-info-value">${p.capacidade} lugares</span>
                        </div>
                    </div>
                    <div class="parque-tags">
                        ${p.mr > 0 ? `<span class="parque-tag mr"><i class="bi bi-person-wheelchair"></i> ${p.mr} MR</span>` : ""}
                        ${p.ev > 0 ? `<span class="parque-tag ev"><i class="bi bi-lightning-charge-fill"></i> ${p.ev} EV</span>` : ""}
                        <span class="parque-tag tipo">${p.tipo}</span>
                        ${p.tarifa === 0 ? '<span class="parque-tag ev">Gratuito</span>' : ""}
                    </div>
                    <div class="parque-actions">
                        <button class="btn btn-sm btn-outline-secondary btn-parque-mapa" data-lat="${p.lat}" data-lng="${p.lng}" data-nome="${p.nome}">
                            <i class="bi bi-map me-1"></i>Mapa
                        </button>
                        <button class="btn btn-sm btn-outline-primary btn-parque-detalhe" data-index="${realIndex}">
                            <i class="bi bi-info-circle me-1"></i>Detalhes
                        </button>
                        <button class="btn btn-sm btn-outline-warning btn-parque-editar" data-index="${realIndex}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btn-parque-remover" data-index="${realIndex}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>`;
          gridParques.appendChild(col);
        });

        document.querySelectorAll(".btn-parque-mapa").forEach((btn) => {
          btn.addEventListener("click", function () {
            const titleEl = document.getElementById("modalMapaParqueTitle");
            const iframeEl = document.getElementById("iframeMapaParque");
            if (titleEl)
              titleEl.textContent = this.dataset.nome + " — Localização";
            if (iframeEl)
              iframeEl.src = `https://www.google.com/maps?q=${this.dataset.lat},${this.dataset.lng}&z=16&output=embed`;
            const modalEl = document.getElementById("modalMapaParque");
            if (modalEl) new bootstrap.Modal(modalEl).show();
          });
        });

        document.querySelectorAll(".btn-parque-detalhe").forEach((btn) => {
          btn.addEventListener("click", function () {
            abrirDetalhe(parseInt(this.dataset.index));
          });
        });

        document.querySelectorAll(".btn-parque-editar").forEach((btn) => {
          btn.addEventListener("click", function () {
            abrirModalEditar(parseInt(this.dataset.index));
          });
        });

        document.querySelectorAll(".btn-parque-remover").forEach((btn) => {
          btn.addEventListener("click", function () {
            const idx = parseInt(this.dataset.index);
            if (confirm(`Remover "${parques[idx].nome}"?`)) {
              parques.splice(idx, 1);
              renderParques(parques);
              atualizarKpis();
              renderAlertas(parques);
            }
          });
        });

        renderAlertas(lista);
      }

      function abrirDetalhe(index) {
        const p = parques[index];
        const pct = getPct(p);
        const cls = getClasse(pct);

        const hist = [
          { hora: "08:00", pct: 20 },
          { hora: "10:00", pct: 45 },
          { hora: "12:00", pct: 72 },
          { hora: "14:00", pct: 68 },
          { hora: "16:00", pct: 80 },
          { hora: "18:00", pct: pct },
        ];
        const barras = hist
          .map((h) => {
            const c = getClasse(h.pct);
            return `
                <div class="d-flex flex-column align-items-center" style="flex:1;">
                    <div style="height:80px;display:flex;align-items:flex-end;width:100%;">
                        <div style="width:100%;height:${h.pct}%;border-radius:4px 4px 0 0;background:${c === "critico" ? "#e74c3c" : c === "atencao" ? "#f39c12" : "#2ecc71"};"></div>
                    </div>
                    <div style="font-size:0.7rem;color:#aaa;margin-top:4px;">${h.hora}</div>
                    <div style="font-size:0.72rem;font-weight:700;">${h.pct}%</div>
                </div>`;
          })
          .join("");

        const titleEl = document.getElementById("modalParqueDetalheTitle");
        const bodyEl = document.getElementById("modalParqueDetalheBody");
        if (titleEl) titleEl.textContent = p.nome + " — Detalhes";
        if (bodyEl)
          bodyEl.innerHTML = `
            <div class="row g-3">
                <div class="col-md-5">
                    <div class="text-center mb-3">
                        <div style="font-size:3.5rem;font-weight:800;" class="${cls === "critico" ? "text-danger" : cls === "atencao" ? "text-warning" : "text-success"}">${pct}%</div>
                        <div class="text-muted" style="font-size:0.85rem;">${p.ocupados} / ${p.capacidade} lugares</div>
                    </div>
                    <div class="parque-prog mb-3"><div class="parque-prog-bar ${cls}" style="width:${pct}%"></div></div>
                    <table class="table table-sm table-borderless" style="font-size:0.85rem;">
                        <tr><td class="text-muted">Tipo</td><td><strong>${p.tipo}</strong></td></tr>
                        <tr><td class="text-muted">Tarifa</td><td><strong>${p.tarifa === 0 ? "Gratuito" : p.tarifa.toFixed(2) + " €/h"}</strong></td></tr>
                        <tr><td class="text-muted">Horário</td><td><strong>${p.horario}</strong></td></tr>
                        <tr><td class="text-muted">Morada</td><td><strong>${p.morada}</strong></td></tr>
                        <tr><td class="text-muted">Lugares MR</td><td><strong>${p.mr}</strong></td></tr>
                        <tr><td class="text-muted">Lugares EV</td><td><strong>${p.ev}</strong></td></tr>
                    </table>
                </div>
                <div class="col-md-7">
                    <div style="font-weight:700;font-size:0.85rem;color:#7b8190;margin-bottom:0.5rem;">OCUPAÇÃO AO LONGO DO DIA (ESTIMADO)</div>
                    <div style="display:flex;gap:6px;align-items:flex-end;height:110px;padding-bottom:0;">${barras}</div>
                    <hr>
                    <div style="font-weight:700;font-size:0.85rem;color:#7b8190;margin-bottom:0.5rem;">DISTRIBUIÇÃO DE LUGARES</div>
                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <div style="background:#e8edff;border-radius:10px;padding:10px;">
                                <div style="font-size:1.3rem;font-weight:700;color:#435ebe;">${p.capacidade - p.ocupados}</div>
                                <div style="font-size:0.72rem;color:#7b8190;">Livres</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div style="background:#fde8e8;border-radius:10px;padding:10px;">
                                <div style="font-size:1.3rem;font-weight:700;color:#e74c3c;">${p.mr}</div>
                                <div style="font-size:0.72rem;color:#7b8190;">Mobilidade Red.</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div style="background:#e6f9ef;border-radius:10px;padding:10px;">
                                <div style="font-size:1.3rem;font-weight:700;color:#27ae60;">${p.ev}</div>
                                <div style="font-size:0.72rem;color:#7b8190;">Elétricos</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        const modalDetalheEl = document.getElementById("modalParqueDetalhe");
        if (modalDetalheEl) new bootstrap.Modal(modalDetalheEl).show();
      }

      function abrirModalEditar(index) {
        const p = parques[index];
        document.getElementById("modalNovoParqueLabel").textContent =
          "Editar " + p.nome;
        document.getElementById("parqueEditIndex").value = index;
        document.getElementById("inputParqueNome").value = p.nome;
        document.getElementById("inputParqueCapacidade").value = p.capacidade;
        document.getElementById("inputParqueOcupados").value = p.ocupados;
        document.getElementById("inputParqueTipo").value = p.tipo;
        document.getElementById("inputParqueTarifa").value = p.tarifa;
        document.getElementById("inputParqueMR").value = p.mr;
        document.getElementById("inputParqueEV").value = p.ev;
        document.getElementById("inputParqueMorada").value = p.morada;
        document.getElementById("inputParqueLat").value = p.lat;
        document.getElementById("inputParqueLng").value = p.lng;
        new bootstrap.Modal(document.getElementById("modalNovoParque")).show();
      }

      document
        .getElementById("btnNovoParque")
        ?.addEventListener("click", () => {
          document.getElementById("modalNovoParqueLabel").textContent =
            "Adicionar Parque";
          document.getElementById("parqueEditIndex").value = "";
          [
            "inputParqueNome",
            "inputParqueCapacidade",
            "inputParqueOcupados",
            "inputParqueTarifa",
            "inputParqueMR",
            "inputParqueEV",
            "inputParqueMorada",
            "inputParqueLat",
            "inputParqueLng",
          ].forEach((id) => {
            document.getElementById(id).value = "";
          });
          document.getElementById("inputParqueTipo").value = "Coberto";
          new bootstrap.Modal(
            document.getElementById("modalNovoParque"),
          ).show();
        });

      document
        .getElementById("btnGuardarParque")
        ?.addEventListener("click", () => {
          const nome = document.getElementById("inputParqueNome").value.trim();
          const capacidade = parseInt(
            document.getElementById("inputParqueCapacidade").value,
          );
          const ocupados = parseInt(
            document.getElementById("inputParqueOcupados").value,
          );
          if (!nome || isNaN(capacidade) || isNaN(ocupados)) {
            alert("Preenche pelo menos o nome, capacidade e lugares ocupados.");
            return;
          }
          const novoParque = {
            nome,
            capacidade,
            ocupados,
            tipo: document.getElementById("inputParqueTipo").value,
            tarifa:
              parseFloat(document.getElementById("inputParqueTarifa").value) ||
              0,
            mr: parseInt(document.getElementById("inputParqueMR").value) || 0,
            ev: parseInt(document.getElementById("inputParqueEV").value) || 0,
            morada:
              document.getElementById("inputParqueMorada").value.trim() || "—",
            horario: "00h–24h",
            lat:
              parseFloat(document.getElementById("inputParqueLat").value) ||
              38.83,
            lng:
              parseFloat(document.getElementById("inputParqueLng").value) ||
              -9.17,
          };
          const idx = document.getElementById("parqueEditIndex").value;
          if (idx === "") parques.push(novoParque);
          else parques[parseInt(idx)] = novoParque;

          bootstrap.Modal.getOrCreateInstance(
            document.getElementById("modalNovoParque"),
          ).hide();
          renderParques(parques);
          atualizarKpis();
        });

      // Filtros
      function aplicarFiltros() {
        const texto = (
          document.getElementById("pesquisaParque")?.value || ""
        ).toLowerCase();
        const estado =
          document.getElementById("filtroParqueEstado")?.value || "";
        const tipo = document.getElementById("filtroParqueTipo")?.value || "";

        document.querySelectorAll(".parque-col").forEach((col) => {
          const matchNome = !texto || col.dataset.nome.includes(texto);
          const matchEstado = !estado || col.dataset.classe === estado;
          const matchTipo = !tipo || col.dataset.tipo === tipo;
          col.classList.toggle(
            "parque-col-oculto",
            !(matchNome && matchEstado && matchTipo),
          );
        });
      }

      document
        .getElementById("pesquisaParque")
        ?.addEventListener("input", aplicarFiltros);
      document
        .getElementById("filtroParqueEstado")
        ?.addEventListener("change", aplicarFiltros);
      document
        .getElementById("filtroParqueTipo")
        ?.addEventListener("change", aplicarFiltros);

      document
        .getElementById("btnParquesCriticos")
        ?.addEventListener("click", function () {
          const filtro = document.getElementById("filtroParqueEstado");
          if (filtro.value === "critico") {
            filtro.value = "";
            this.classList.remove("active", "btn-danger");
            this.classList.add("btn-outline-danger");
          } else {
            filtro.value = "critico";
            this.classList.add("active", "btn-danger");
            this.classList.remove("btn-outline-danger");
          }
          aplicarFiltros();
        });

      renderParques(parques);
      atualizarKpis();
    }

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
})();
