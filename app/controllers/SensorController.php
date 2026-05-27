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
}