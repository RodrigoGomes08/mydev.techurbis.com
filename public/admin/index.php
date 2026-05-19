<?php

session_start();

$base = realpath(__DIR__ . '/../../app/controllers/');
require $base . '/WebController.php';
require $base . '/UserController.php';
require $base . '/RoleController.php';
require $base . '/PosteController.php'; // ← adicionado

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// ─── PORTAL ADMIN VIEWS ───────────────────────────────────────────────────────

if ($uri === '/admin/PortalADMGeral' || $uri === '/admin' || $uri === '/admin/') {
    (new WebController())->adminGeral();

} elseif ($uri === '/admin/PortalADMCidade') {
    (new WebController())->adminCidade();

} elseif ($uri === '/admin/PortalADMContentores') {
    (new WebController())->adminContentores();

} elseif ($uri === '/admin/PortalADMParques') {
    (new WebController())->adminParques();

// ─── POSTES ───────────────────────────────────────────────────────────────────

} elseif ($uri === '/admin/PortalADMPostes') {
    (new PosteController())->showPortalADMPostes(); // ← corrigido (era WebController)

} elseif ($uri === '/admin/create-poste' && $method === 'POST') {
    (new PosteController())->createPoste();

} elseif ($uri === '/admin/update-poste' && $method === 'POST') {
    (new PosteController())->PosteUpdate($_POST['id'] ?? null);

} elseif ($method === 'POST' && preg_match('#^/admin/delete-poste/(\d+)$#', $uri, $m)) {
    (new PosteController())->PosteDelete((int)$m[1]);

// ─── UTILIZADORES ─────────────────────────────────────────────────────────────

} elseif ($uri === '/admin/PortalADMUtilizadores') {
    (new UserController())->showPortalADMUtilizadores();

} elseif ($uri === '/admin/update-utilizador' && $method === 'POST') {
    (new UserController())->userUpdate($_POST['id'] ?? null);

} elseif ($method === 'POST' && preg_match('#^/admin/delete-utilizador/(\d+)$#', $uri, $m)) {
    (new UserController())->userDelete((int)$m[1]);

} else {
    http_response_code(404);
    echo "Página não encontrada";
}