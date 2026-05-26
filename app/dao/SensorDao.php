<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Sensor.php';
require_once __DIR__ . '/../models/SensorLeituras.php';

class SensorDAO{

    private $conn;

    public function __construct()
    {
        $this->conn = (new DataBase())->connect();
    }

    public function getAllSensores()
    {
        $sql = "SELECT id, id_cidade, name
                FROM sensores
                ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $sensores = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sensores[] = new Sensor(
                (int) $row['id'],
                (int) $row['id_cidade'],
                $row['name']
            );
        }

        return $sensores;
    }

    public function findByID($id)
    {
        $sql = "SELECT * FROM sensores WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }
}