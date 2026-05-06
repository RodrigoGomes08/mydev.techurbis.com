<?php

require "../app/controllers/WebController.php";
require "../app/controllers/AuthController.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/' || $uri === '/index' || $uri === '/home') {
  (new WebController())->index();
} elseif ($uri === '/login' && $method === "GET") {
  (new WebController())->login();
} else if ($uri === '/login' && $method === 'POST') {
  (new AuthController())->loginWeb();
} elseif ($uri === '/logout' && $method === 'GET') {

  $_SESSION['toast'] = [
    'type' => 'success',
    'message' => 'Logout efetuado com sucesso!!!'
  ];


  header("Location: /index");
} elseif ($uri === '/PortalADMGeral') {
  (new WebController())->adminGeral();
} elseif ($uri === '/PortalADMUtilizadores') {
  (new WebController())->adminUtilizadores();
} elseif ($uri === '/PortalADMContentores') {
  (new WebController())->adminContentores();
} elseif ($uri === '/PortalADMPostes') {
  (new WebController())->adminPostes();
} elseif ($uri === '/PortalADMParques') {
  (new WebController())->adminParques();
} elseif ($uri === '/PortalADMCidade') {
  (new WebController())->adminCidade();
} elseif ($uri === "/teste") {

  echo password_hash("1234", PASSWORD_DEFAULT);
} else {
  echo "404";
}













