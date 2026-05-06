<?php

require "../../app/controllers/WebController.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//$uri = str_replace("mydevpiratas.com/public", "", $uri);

$method = $_SERVER['REQUEST_METHOD'];

if($uri === '/admin/' || $uri === '/admin') {
    //var_dump($uri);
  (new WebController())->adminGeral();
}

elseif($uri === '/admin/PortalADMCidade') {
    //var_dump($uri);
  (new WebController())->adminCidade();
}

elseif($uri === '/admin/PortalADMContentores') {
    //var_dump($uri);
  (new WebController())->adminContentores();
}

elseif($uri === '/admin/PortalADMParques') {
    //var_dump($uri);
  (new WebController())->adminParques();
}

elseif($uri === '/admin/PortalADMPostes') {
    //var_dump($uri);
  (new WebController())->adminPostes();
}

elseif($uri === '/admin/PortalADMUtilizadores') {
  (new WebController())->adminUtilizadores();
}










