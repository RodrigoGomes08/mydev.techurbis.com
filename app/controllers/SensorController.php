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