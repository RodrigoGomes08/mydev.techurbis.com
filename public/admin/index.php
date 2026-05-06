<?php

session_start();

$base = realpath(__DIR__ . '/../../app/controllers/');
require $base . '/WebController.php';
require $base . '/UserController.php';

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
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
    (new WebController())->adminUtilizadores();

} else {
    http_response_code(404);
    echo "Página não encontrada";
}