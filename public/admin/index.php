<?php

require "../../app/controllers/WebController.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//$uri = str_replace("mydevpiratas.com/public", "", $uri);

$method = $_SERVER['REQUEST_METHOD'];

if($uri === '/admin/' || $uri === '/admin') {
    //var_dump($uri);
  (new WebController())->adminGeral();
}

if($uri === '/admin/PortalADMCidade') {
    //var_dump($uri);
  (new WebController())->adminCidade();
}

if($uri === '/admin/PortalADMContentores') {
    //var_dump($uri);
  (new WebController())->adminContentores();
}

if($uri === '/admin/PortalADMParques') {
    //var_dump($uri);
  (new WebController())->adminParques();
}

if($uri === '/admin/PortalADMPostes') {
    //var_dump($uri);
  (new WebController())->adminPostes();
}

if($uri === '/admin/PortalADMUtilizadores') {
  (new WebController())->adminUtilizadores();
}













