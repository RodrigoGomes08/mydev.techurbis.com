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

$uri = str_replace("/api", "", $uri);
$method = $_SERVER['REQUEST_METHOD'];

if (($uri === "/" || $uri === "/index") && $method === 'GET') {

    Utils::jsonResponse([
        "success" => true,
        "message" => "id e nome"
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
}

//==================================================
// CONTENTORES
//==================================================
elseif ($uri === '/contentores' && $method === 'GET') {
    AuthMiddlewareApi::check();
    (new ContentorController())->contentorListApi();

} elseif (preg_match('#^/contentores/(\d+)$#', $uri, $m) && $method === "GET") {
    AuthMiddlewareApi::check();
    $id = (int) $m[1];
    (new ContentorController())->contentorDetailApi($id);

} elseif (preg_match('#^/contentoresobs/(\d+)$#', $uri, $m) && $method === "POST") {
    AuthMiddlewareApi::check();
    $id = (int) $m[1];
    (new ContentorController())->insertObsEmContentorApi($id);
} 


//==================================================
// POSTES
//==================================================
elseif ($uri === '/postes' && $method === 'GET') {
    AuthMiddlewareApi::check();
    (new PosteController())->posteListApi();

} elseif (preg_match('#^/postes/(\d+)$#', $uri, $m) && $method === "GET") {
    AuthMiddlewareApi::check();
    $id = (int) $m[1];
    (new PosteController())->posteDetailApi($id);

} elseif (preg_match('#^/postesobs/(\d+)$#', $uri, $m) && $method === "POST") {
    AuthMiddlewareApi::check();
    $id = (int) $m[1];
    (new PosteController())->insertObsEmPostesApi($id);
} 

//==================================================
// PARQUES
//==================================================
elseif ($uri === '/parques' && $method === 'GET') {
    AuthMiddlewareApi::check();
    (new ParqueController())->parqueListApi();

} elseif (preg_match('#^/parques/(\d+)$#', $uri, $m) && $method === "GET") {
    AuthMiddlewareApi::check();
    $id = (int) $m[1];
    (new ParqueController())->parqueDetailApi($id);
} 

//==================================================
// SENSORES
//==================================================
elseif ($uri === '/sensores' && $method === 'GET') {
    AuthMiddlewareApi::check();
    (new SensorController())->sensorListApi();

} elseif (preg_match('#^/sensores/(\d+)$#', $uri, $m) && $method === "GET") {
    AuthMiddlewareApi::check();
    $id = (int) $m[1];
    (new SensorController())->sensorDetailApi($id);

} elseif (preg_match('#^/sensoresobs/(\d+)$#', $uri, $m) && $method === "POST") {
    AuthMiddlewareApi::check();
    $id = (int) $m[1];
    (new SensorController())->insertObsEmSensoresApi($id);
} 

//==================================================
// SENSORES_VALOR
//==================================================
elseif ($uri === '/sensor/valor-temp' && $method === 'GET') {
    AuthMiddlewareApi::check();
    (new SensorController())->valorSensorTemp();

} elseif ($uri === '/sensor/valor-hum' && $method === 'GET') {
    AuthMiddlewareApi::check();
    (new SensorController())->valorSensorHum();
    
} elseif ($uri === '/sensor/valor-distancia' && $method === 'POST') {
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