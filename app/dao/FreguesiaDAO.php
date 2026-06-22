<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Freguesia.php';

class FreguesiaDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new DataBase())->connect();
    }

    public function getAllFreguesias()
    {
        $sql = "SELECT id, nome
                FROM freguesias
                ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $freguesias = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $freguesias[] = new Freguesia(
                (int) $row['id'],
                (string) $row['nome']
            );
        }
        return $freguesias;
    }

    public function findByID($id)
    {
        $sql = "SELECT id, nome FROM freguesias WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }
}