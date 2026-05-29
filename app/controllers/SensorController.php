<?php

require_once __DIR__ . '/../dao/SensorDAO.php';

class SensorController
{
    public function valorSensorTemp()
    {
        //Método para obter o valor do sensor de temperatura
    }

    public function valorSensorHum()
    {
        //Método para obter o valor do sensor de humidade
    }

    public function valorSensorDistancia()
    {
        //Método para obter o valor do sensor de distância
        $json = file_get_contents("php://input");
            // Converter JSON para array PHP
            $data = json_decode($json, true);

            // Verificar se chegou corretamente
            if ($data) {

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

            } else {

               echo json_encode([
                    "success" => false,
                    "message" => "Erro ao receber JSON",
                    "data" => [
                    ]
                ]);
            }
    }

    public function sensorListApi()
    {
        // if (empty($_SESSION['token'])) {
        //     header("Location: /login");
        //     exit;
        // }

        $sensorDAO = new SensorDAO();
        $sensores = $sensorDAO->getAllSensores();

        
        Utils::jsonResponse([
            'success' => true,
            'message' => 'Lista de sensores obtida com sucesso.',
            'data' => $sensores
        ]);
    }

    public function sensorDetailObsApi($id)
    {

        $pdo = DatabaseSingle::connect();
        $pdo->beginTransaction();

        try {
            $posteDAO = new PosteDAO();
            $postes = (new PosteDAO())->findByID($id);
            $posteDAO->posteInsertObsDAO($id);

            $pdo->commit();

        } catch (Exception $e) {

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
}