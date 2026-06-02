<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Estado.php';

class EstadoDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new DataBase())->connect();
    }

    public function getAllEstados()
    {
        $sql = "SELECT id, nome, cor
                FROM estados
                ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $estados = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $estados[] = new Estado(
                (int) $row['id'],
                (String) $row['nome'],
                (String) $row['cor']
            );
        }
        return $estados;
    }

    public function findByID($id)
    {
        $sql = "SELECT id, nome, cor FROM estados WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }
}