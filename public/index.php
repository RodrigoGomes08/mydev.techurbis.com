<?php

require "../app/controllers/WebController.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = str_replace("mydevpiratas.com/public", "", $uri);

$method = $_SERVER['REQUEST_METHOD'];

if($uri === '/' || $uri === '/index' || $uri === '/home') {
  (new WebController())->index();
} elseif ($uri === '/login' && $method === "GET") {
  var_dump("Entrar na página login");
} elseif ($uri === '/login' && $method === "POST") {
  var_dump("Entrar na página login post para submeter");
}  elseif ($uri === '/sigup' && $method === "GET") {
  var_dump("Entrar na página sigup");
} elseif ($uri === '/signup' && $method === "POST") {
  var_dump("Entrar na página signup post para submeter");
}
elseif($uri === '/users' && $method === "GET") {
  var_dump("Entrar na página users");
} else {
  echo "404";
}













