<?php

require_once __DIR__ . '/../dao/SensorDAO.php';

class SensorController
{
    public function valorSensorTempHum()
    {

        die("Aqui");    
        try {
            $json = file_get_contents("php://input");

            if (!$json) {
                throw new Exception("Erro ao ler o corpo do pedido");
            }

            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("JSON inválido: " . json_last_error_msg());
            }

            if (!isset($data["temp_value"], $data["hum_value"], $data["date"])) {
                throw new Exception("Campos obrigatórios em falta");
            }

            $temp = $data["temp_value"];
            $hum = $data["hum_value"];
            $date = $data["date"];

            echo json_encode([
                "success" => true,
                "message" => "Valor recebido com sucesso",
                "data" => [
                    "temperatura" => $temp,
                    "humidade" => $hum,
                    "data_leitura" => $date
                ]
            ]);

        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage(),
                "data" => []
            ]);
        }
    }

    public function valorSensorDistancia()
    {
        try {
            $json = file_get_contents("php://input");

            if (!$json) {
                throw new Exception("Erro ao ler o corpo do pedido");
            }

            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("JSON inválido: " . json_last_error_msg());
            }

            if (!isset($data["dist_value"], $data["date"])) {
                throw new Exception("Campos obrigatórios em falta");
            }

            $dist = $data["dist_value"];
            $date = $data["date"];

            echo json_encode([
                "success" => true,
                "message" => "Valor recebido com sucesso",
                "data" => [
                    "distancia" => $dist,
                    "data_leitura" => $date
                ]
            ]);

        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage(),
                "data" => []
            ]);
        }
    }

    public function sensorListApi()
    {
        $pdo = DatabaseSingle::connect();
        $pdo->beginTransaction();

        try {
            // if (empty($_SESSION['token'])) {
            //     header("Location: /login");
            //     exit;
            // }

            $sensorDAO = new SensorDAO();
            $sensores = $sensorDAO->getAllSensores();

            $pdo->commit();
            Utils::jsonResponse([
                'success' => true,
                'message' => 'Lista de sensores obtida com sucesso.',
                'data' => [
                    'sensores' => $sensores
                ]
            ]);

        } catch (Exception $e) {
            $pdo->rollBack();
            Utils::jsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function sensorDetailApi($id)
    {
        $pdo = DatabaseSingle::connect();
        $pdo->beginTransaction();

        try {
            $sensores = (new SensorDAO())->findByID($id);

            if (!$sensores) {
                throw new Exception("Sensor não encontrado");
            }

            $pdo->commit();
            Utils::jsonResponse([
                'success' => true,
                'message' => 'Detalhe do sensor obtido com sucesso.',
                'data' => [
                    "detalhes_sensor" => $sensores
                ]
            ]);

        } catch (Exception $e) {
            $pdo->rollback();
            Utils::jsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 404);
        }
    }

    public function insertObsEmSensoresApi($id)
    {
        // $id já vem da URL, não precisas de o ler do POST
        $texto = trim($_POST["texto"] ?? '');

        if (empty($id) || empty($texto)) {
            Utils::jsonResponse([
                'success' => false,
                'message' => 'ID e texto são obrigatórios.',
                'data' => null
            ], 400);
            return;
        }

        try {
            $postesObs = (new PosteDAO())->insertObs($id, $texto);

            Utils::jsonResponse([
                'success' => true,
                'message' => 'Observação inserida com sucesso.',
                'data' => $postesObs
            ]);
        } catch (Exception $e) {
            Utils::jsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function getLast15ValoresSensorApi($id)
    {
        try {
            $id = trim($id ?? '');

            if (empty($id)) {
                Utils::jsonResponse([
                    'success' => false,
                    'message' => 'ID é obrigatório.',
                    'data' => []
                ], 400);
                return;
            }

            $sensorDAO = new SensorDAO();

            // Verificar se o sensor existe
            $sensor = $sensorDAO->findByID($id);
            if (!$sensor) {
                throw new Exception("Sensor com id '$id' não encontrado.");
            }

            $ValoresSensor = $sensorDAO->getLast15ValoresSensor($id);

            Utils::jsonResponse([
                'success' => true,
                'message' => 'Valores obtidos com sucesso.',
                'data' => [
                    "15ValoresSensor" => $ValoresSensor
                ]
            ]);
        } catch (Exception $e) {
            Utils::jsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}