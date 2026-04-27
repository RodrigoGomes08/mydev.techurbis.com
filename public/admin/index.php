<?php

require "../../app/controllers/WebController.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//$uri = str_replace("mydevpiratas.com/public", "", $uri);

$method = $_SERVER['REQUEST_METHOD'];

if($uri === '/admin/PortalADMGeral' || $uri === '/admin/') {
    //var_dump($uri);
  (new WebController())->adminGeral();
}else {
  echo "$uri";
}

if($uri === '/admin/PortalADMContentores') {
    //var_dump($uri);
  (new WebController())->adminContentores();
}else {
  echo "$uri";
}

if($uri === '/admin/PortalADMCidade') {
    //var_dump($uri);
  (new WebController())->adminCidade();
}else {
  echo "$uri";
}

if($uri === '/admin/PortalADMParques') {
    //var_dump($uri);
  (new WebController())->adminParques();
}else {
  echo "$uri";
}

if($uri === '/admin/PortalADMPostes') {
    //var_dump($uri);
  (new WebController())->adminPostes();
}else {
  echo "$uri";
}

if($uri === '/admin/PortalADMUtilizadores') {
    var_dump($uri);
  (new WebController())->adminUtilizadores();
}else {
  echo "$uri";
}













