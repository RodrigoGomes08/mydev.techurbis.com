<?php

session_start();

$base = realpath(__DIR__ . '/../../app/controllers/');
require $base . '/WebController.php';
require $base . '/UserController.php';
require $base . '/RoleController.php';
require $base . '/PosteController.php';
require $base . '/ContentorController.php';
require $base . '/ParqueController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// ─── PORTAL ADMIN VIEWS ───────────────────────────────────────────────────────

if ($uri === '/admin/PortalADMGeral' || $uri === '/admin' || $uri === '/admin/') {
    (new WebController())->adminGeral();

} elseif ($uri === '/admin/PortalADMCidade') {
    (new WebController())->adminCidade();

    // ─── PARQUES ──────────────────────────────────────────────────────────────   ────

} elseif ($uri === '/admin/PortalADMParques') {
    (new ParqueController())->showPortalADMParques();

} elseif ($uri === '/admin/create-parque' && $method === 'POST') {
    (new ParqueController())->createParque();

} elseif ($uri === '/admin/update-parque' && $method === 'POST') {
    (new ParqueController())->parqueUpdate($_POST['id'] ?? null);

} elseif ($method === 'POST' && preg_match('#^/admin/delete-parque/(\d+)$#', $uri, $m)) {
    (new ParqueController())->parqueDelete((int) $m[1]);

    // ─── CONTENTORES ──────────────────────────────────────────────────────────────

} elseif ($uri === '/admin/PortalADMContentores') {
    (new ContentorController())->showPortalADMContentores();

} elseif ($uri === '/admin/create-contentor' && $method === 'POST') {
    (new ContentorController())->createContentor();

} elseif ($uri === '/admin/update-contentor' && $method === 'POST') {
    (new ContentorController())->ContentorUpdate($_POST['id'] ?? null);

} elseif ($method === 'POST' && preg_match('#^/admin/delete-contentor/(\d+)$#', $uri, $m)) {
    (new ContentorController())->ContentorDelete((int) $m[1]);

    // ─── POSTES ───────────────────────────────────────────────────────────────────

} elseif ($uri === '/admin/PortalADMPostes') {
    (new PosteController())->showPortalADMPostes();

} elseif ($uri === '/admin/create-poste' && $method === 'POST') {
    (new PosteController())->createPoste();

} elseif ($uri === '/admin/update-poste' && $method === 'POST') {
    (new PosteController())->PosteUpdate($_POST['id'] ?? null);

} elseif ($method === 'POST' && preg_match('#^/admin/delete-poste/(\d+)$#', $uri, $m)) {
    (new PosteController())->PosteDelete((int) $m[1]);

    // ─── UTILIZADORES ─────────────────────────────────────────────────────────────

} elseif ($uri === '/admin/PortalADMUtilizadores') {
    (new UserController())->showPortalADMUtilizadores();

} elseif ($uri === '/admin/update-utilizador' && $method === 'POST') {
    (new UserController())->userUpdate($_POST['id'] ?? null);

} elseif ($method === 'POST' && preg_match('#^/admin/delete-utilizador/(\d+)$#', $uri, $m)) {
    (new UserController())->userDelete((int) $m[1]);

} else {
    http_response_code(404);
    echo "Página não encontrada";
}