<?php

session_start();

$base = realpath(__DIR__ . '/../../app/controllers/');
require $base . '/WebController.php';
require $base . '/UserController.php';
require $base . '/RoleController.php';
require $base . '/PosteController.php';
require $base . '/ContentorController.php';
require $base . '/ParqueController.php';
require realpath(__DIR__ . '/../../app/middleware/AuthMiddlewareWeb.php');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// ─── PORTAL ADMIN VIEWS ───────────────────────────────────────────────────────

if ($uri === '/admin/PortalADMGeral' || $uri === '/admin' || $uri === '/admin/') {
    AuthMiddlewareWeb::isLoged();
    AuthMiddlewareWeb::isAdmin();
    (new WebController())->adminGeral();

} elseif ($uri === '/admin/PortalADMCidade') {
    AuthMiddlewareWeb::isAdmin();
    (new WebController())->adminCidade();

    // ─── PARQUES ──────────────────────────────────────────────────────────────────

} elseif ($uri === '/admin/PortalADMParques') {
    AuthMiddlewareWeb::isAdmin();
    (new ParqueController())->showPortalADMParques();

} elseif ($uri === '/admin/create-parque' && $method === 'POST') {
    AuthMiddlewareWeb::isAdmin();
    (new ParqueController())->createParque();

} elseif ($uri === '/admin/update-parque' && $method === 'POST') {
    AuthMiddlewareWeb::isAdmin();
    (new ParqueController())->parqueUpdate($_POST['id'] ?? null);

} elseif ($method === 'POST' && preg_match('#^/admin/delete-parque/(\d+)$#', $uri, $m)) {
    AuthMiddlewareWeb::isAdmin();
    (new ParqueController())->parqueDelete((int) $m[1]);

    // ─── CONTENTORES ──────────────────────────────────────────────────────────────

} elseif ($uri === '/admin/PortalADMContentores') {
    AuthMiddlewareWeb::isWorker();
    (new ContentorController())->showPortalADMContentores();

} elseif ($uri === '/admin/create-contentor' && $method === 'POST') {
    AuthMiddlewareWeb::isAdmin();
    (new ContentorController())->createContentor();

} elseif ($uri === '/admin/update-contentor' && $method === 'POST') {
    AuthMiddlewareWeb::isAdmin();
    (new ContentorController())->ContentorUpdate($_POST['id'] ?? null);

} elseif ($method === 'POST' && preg_match('#^/admin/delete-contentor/(\d+)$#', $uri, $m)) {
    AuthMiddlewareWeb::isAdmin();
    (new ContentorController())->ContentorDelete((int) $m[1]);

    // ─── POSTES ───────────────────────────────────────────────────────────────────

} elseif ($uri === '/admin/PortalADMPostes') {
    AuthMiddlewareWeb::isAdmin();
    (new PosteController())->showPortalADMPostes();

} elseif ($uri === '/admin/create-poste' && $method === 'POST') {
    AuthMiddlewareWeb::isAdmin();
    (new PosteController())->createPoste();

} elseif ($uri === '/admin/update-poste' && $method === 'POST') {
    AuthMiddlewareWeb::isAdmin();
    (new PosteController())->PosteUpdate($_POST['id'] ?? null);

} elseif ($method === 'POST' && preg_match('#^/admin/delete-poste/(\d+)$#', $uri, $m)) {
    AuthMiddlewareWeb::isAdmin();
    (new PosteController())->PosteDelete((int) $m[1]);

    // ─── UTILIZADORES ─────────────────────────────────────────────────────────────

} elseif ($uri === '/admin/PortalADMUtilizadores') {
    AuthMiddlewareWeb::isAdmin();
    (new UserController())->showPortalADMUtilizadores();

} elseif ($uri === '/admin/update-utilizador' && $method === 'POST') {
    AuthMiddlewareWeb::isAdmin();
    (new UserController())->userUpdate($_POST['id'] ?? null);

} elseif ($method === 'POST' && preg_match('#^/admin/delete-utilizador/(\d+)$#', $uri, $m)) {
    AuthMiddlewareWeb::isAdmin();
    (new UserController())->userDelete((int) $m[1]);

} elseif ($uri === '/admin/create-utilizador' && $method === 'POST') {
    AuthMiddlewareWeb::isAdmin();
    (new UserController())->createUtilizador();

} else {
    http_response_code(404);
    echo "Página não encontrada";
}