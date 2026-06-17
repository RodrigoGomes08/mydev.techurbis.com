<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geral | Portal SmartCity</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/stylesPortal.css">
<link rel="icon" type="image/png" href="/assets/img/logo.png">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-admin">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="PortalADMGeral.html">
            <img src="/../assets/img/logo.png" alt="Logo" style="width:30px;height:30px;margin-right:8px;">
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
                        <span class="badge badge-notif bg-danger" id="notifCount">0</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="width:320px;">
                        <li class="dropdown-header"><strong>Notificações</strong></li>
                        <li><hr class="dropdown-divider"></li>
                        <div id="notifList" style="max-height:300px;overflow-y:auto;">
                            <li class="dropdown-item text-muted text-center">Sem notificações</li>
                        </div>
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
            <a class="nav-link <?= $activeGeral ?>" href="PortalADMGeral"><i class="bi bi-graph-up"></i> Geral</a>
            <a class="nav-link <?= $activeUtilizadores ?>" href="PortalADMUtilizadores"><i class="bi bi-people"></i> Utilizadores</a>
            <a class="nav-link <?= $activeContentores ?>" href="PortalADMContentores"><i class="bi bi-trash"></i> Contentores de Lixo</a>
            <a class="nav-link <?= $activePostes ?>" href="PortalADMPostes"><i class="bi bi-lightbulb"></i> Postes de Iluminação</a>
            <a class="nav-link <?= $activeParques ?>" href="PortalADMParques"><i class="bi bi-p-circle"></i> Parques de Estacionamento</a>
            <a class="nav-link <?= $activeCidade ?>" href="PortalADMCidade"><i class="bi bi-info-circle"></i> Informações da Cidade</a>
            <a class="nav-link text-danger mt-auto" href="/"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </nav>
    </aside>

<!-- Estava a assim
            <a class="nav-link active" href="PortalADMGeral"><i class="bi bi-graph-up"></i> Geral</a>
            <a class="nav-link" href="PortalADMUtilizadores"><i class="bi bi-people"></i> Utilizadores</a>
            <a class="nav-link" href="PortalADMContentores"><i class="bi bi-trash"></i> Contentores de Lixo</a>
            <a class="nav-link" href="PortalADMPostes"><i class="bi bi-lightbulb"></i> Postes de Iluminação</a>
            <a class="nav-link" href="PortalADMParques"><i class="bi bi-p-circle"></i> Parques de Estacionamento</a>
            <a class="nav-link" href="PortalADMCidade"><i class="bi bi-info-circle"></i> Informações da Cidade</a>
            <a class="nav-link text-danger mt-auto" href="/"><i class="bi bi-box-arrow-right"></i> Sair</a> -->