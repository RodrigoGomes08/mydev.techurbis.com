<?php

session_start();

$base = realpath(__DIR__ . '/../../app/controllers/');
require $base . '/WebController.php';
require $base . '/UserController.php';
require $base . '/RoleController.php';

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

} elseif ($uri === '/admin/PortalADMPostes') {
    (new WebController())->adminPostes();

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