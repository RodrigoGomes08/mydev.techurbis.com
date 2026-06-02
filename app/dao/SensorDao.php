<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Sensor.php';
require_once __DIR__ . '/../models/SensorLeituras.php';

class SensorDAO
{

    private $conn;

    public function __construct()
    {
        $this->conn = (new DataBase())->connect();
    }

    public function getAllSensores()
    {
        $sql = "SELECT s.id AS sensor_id, s.id_cidade, s.nome, sl.id AS leitura_id, sl.data_leitura, sl.leitura_string, sl.leitura_num
                FROM sensores s
                LEFT JOIN sensor_leituras sl ON s.id = sl.id_sensor
                ORDER BY s.id ASC, sl.data_leitura DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $sensoresMap = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sensorId = (int) $row['sensor_id'];

            // Cria o sensor só uma vez por id
            if (!isset($sensoresMap[$sensorId])) {
                $sensoresMap[$sensorId] = new Sensor(
                    $sensorId,
                    (int) $row['id_cidade'],
                    $row['nome']
                );
            }

            // Adiciona a leitura ao sensor (se existir)
            if ($row['leitura_id'] !== null) {
                $leitura = new SensorLeituras(
                    (int) $row['leitura_id'],
                    $sensorId,
                    new DateTime($row['data_leitura']),
                    $row['leitura_string'],
                    (float) $row['leitura_num']
                );
                $sensoresMap[$sensorId]->addLeitura($leitura);
            }
        }

        return array_values($sensoresMap);
    }

    public function findByID($id)
    {
        $sql = "SELECT s.id AS sensor_id, s.id_cidade, s.nome, sl.id AS leitura_id, sl.data_leitura, sl.leitura_string, sl.leitura_num
                FROM sensores s
                LEFT JOIN sensor_leituras sl ON s.id = sl.id_sensor
                WHERE s.id = :id
                ORDER BY sl.data_leitura DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $sensor = null;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($sensor === null) {
                $sensor = new Sensor(
                    (int) $row['sensor_id'],
                    (int) $row['id_cidade'],
                    $row['nome']
                );
            }

            if ($row['leitura_id'] !== null) {
                $leitura = new SensorLeituras(
                    (int) $row['leitura_id'],
                    (int) $row['sensor_id'],
                    new DateTime($row['data_leitura']),
                    $row['leitura_string'],
                    (float) $row['leitura_num']
                );
                $sensor->addLeitura($leitura);
            }
        }

        return $sensor;
    }

    public function getLast15ValoresSensor($id)
    {
        $sql = "SELECT id, id_sensor, data_leitura, leitura_string, leitura_num FROM sensor_leituras 
            WHERE id_sensor = :id 
            ORDER BY id DESC 
            LIMIT 15";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $valores = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $valores[] = $row;
        }
        return $valores;
    }
}