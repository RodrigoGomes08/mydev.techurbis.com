<?php

session_start();

$base = realpath(__DIR__ . '/../app/controllers/');
require $base . '/WebController.php';
require $base . '/AuthController.php';
require $base . '/UserController.php';
require $base . '/RoleController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// ─── PUBLIC ───────────────────────────────────────────────────────────────────

if ($uri === '/' || $uri === '/index' || $uri === '/home') {
    (new WebController())->index();

} elseif ($uri === '/login' && $method === 'GET') {
    (new WebController())->login();

} elseif ($uri === '/login' && $method === 'POST') {
    (new AuthController())->loginWeb();

} elseif ($uri === '/logout' && $method === 'GET') {
    $_SESSION['toast'] = [
        'type' => 'success',
        'message' => 'Logout efetuado com sucesso!!!'
    ];
    header("Location: /index");

    // ─── UTILIZADORES ─────────────────────────────────────────────────────────────

} elseif ($uri === '/create-utilizador' && $method === 'POST') {
    (new UserController())->createUtilizador();

} elseif ($uri === '/create-role' && $method === 'POST') {
    (new RoleController())->createRole();   

} // POST /admin/delete-utilizador/{id}
else if ($method === 'POST' && preg_match('#^/admin/delete-utilizador/(\d+)$#', $uri, $m)) {
    (new UserController())->userDelete((int)$m[1]);
} 


elseif ($uri === '/teste') {
    echo password_hash("1234", PASSWORD_DEFAULT);

} else {
    echo "404";
}