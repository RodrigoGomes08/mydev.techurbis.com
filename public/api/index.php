<?php
require __DIR__ . '/../../vendor/autoload.php';


require_once __DIR__ . "/../../app/utils/Utils.php";
require_once __DIR__ . "/../../app/controllers/AuthController.php";
require_once __DIR__ . "/../../app/controllers/UserController.php";
require_once __DIR__ . "/../../app/middleware/AuthMiddlewareAPI.php";
require_once __DIR__ . "/../../app/controllers/ContentorController.php";
require_once __DIR__ . "/../../app/controllers/PosteController.php";
require_once __DIR__ . "/../../app/controllers/ParqueController.php";
require_once __DIR__ . "/../../app/controllers/SensorController.php";


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
} elseif ($uri === '/home' && $method === 'GET') {
    $dataToken = AuthMiddlewareAPI::check();
    $valorTemp = (new SensorController())->valorSensorTemp();
    $valorHum = (new SensorController())->valorSensorHum();
} elseif ($uri === '/contentores' && $method === 'GET'){
    AuthMiddlewareApi::check();
    (new ContentorController())->contentorListApi();
} elseif($uri === '/contentoresDetails' && $method === 'GET'){
    AuthMiddlewareApi::check();
    (new ContentorController())->contentorDetailsApi();
} elseif ($uri === '/postes' && $method === 'GET') {
    AuthMiddlewareApi::check();
    (new PosteController())->posteListApi();
} elseif($uri === '/postesDetails' && $method === 'GET'){
    AuthMiddlewareApi::check();
    (new PosteController())->posteDetailsApi();
} elseif ($uri === '/parques' && $method === 'GET'){
    AuthMiddlewareApi::check();
    (new ParqueController())->parqueListApi();
} elseif($uri === '/parquesDetails' && $method === 'GET'){
    AuthMiddlewareApi::check();
    (new ParqueController())->parqueDetailsApi();
} elseif ($uri === '/sensores' && $method === 'GET'){
    AuthMiddlewareApi::check();
    (new SensorController())->sensorListApi();
} elseif($uri === '/sensoresDetails' && $method === 'GET'){
    AuthMiddlewareApi::check();
    (new SensorController())->sensorDetailsApi();
} elseif ($uri === '/sensor/valor-temp' && $method === 'GET') {
    AuthMiddlewareApi::check();
    (new SensorController())->valorSensorTemp();
} elseif ($uri === '/sensor/valor-hum' && $method === 'GET') {
    AuthMiddlewareApi::check();
    (new SensorController())->valorSensorHum();
} elseif ($uri === '/sensor/valor-distancia' && $method === 'GET'){
    AuthMiddlewareApi::check();
    (new SensorController())->valorSensorDistancia();
}





else {

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