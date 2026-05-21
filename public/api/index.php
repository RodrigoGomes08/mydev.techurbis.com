<?php

require __DIR__ . '/../../vendor/autoload.php';


require_once __DIR__ . "/../../app/utils/Utils.php";
require_once __DIR__ . "/../../app/controllers/AuthController.php";
require_once __DIR__ . "/../../app/controllers/UserController.php";
require_once __DIR__ . "/../../app/middleware/AuthMiddlewareAPI.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// 1. Configuração 1 
header("Content-Type: application/json; charset=UTF-8");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//var_dump($uri);

$uri = str_replace("/api", "", $uri);
//var_dump($uri);
$method = $_SERVER['REQUEST_METHOD'];

if (($uri === "/" || $uri === "/index") && $method === 'GET') {

    Utils::jsonResponse([
        "success" => true,
        "message" => "id e nome são obrigatórios"
    ], 200);
    exit;
} elseif ($uri === '/signup' && $method === 'POST') {
    (new AuthController())->singupApi();
} elseif ($uri === '/login' && $method === 'POST') {
    (new AuthController())->loginApi();
}elseif ($uri === '/home' && $method === 'GET') {
    $dataToken = AuthMiddlewareAPI::check();
    // Só posso fazer com token válido
    //$users = (new UserController())->getAllDataToHome($dataToken->id);
} else {

    $dataResponse = [
        'success' => false,
        'message' => "Rota não encontrada",
        'data' => []
    ];

    Utils::jsonResponse($dataResponse, 404);
}



// 2. Template de Resposta
//$dataResponse = [
//  'success' => true,
//  'message' => "Operação realizada com sucesso",
//  'data'    => [] 
//];
// jsonResponse
//Utils::jsonResponse($dataResponse);


?>