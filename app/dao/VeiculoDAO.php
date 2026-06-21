<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Veiculo.php';

class VeiculoDAO{
    private $conn;

    public function __construct()
    {
        $this->conn = (new DataBase())->connect();
    }

    public function findByMatricula(string $matricula): ?Veiculo
    {         
    $sql = "SELECT * FROM veiculos WHERE matricula = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$matricula]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return null;
    }

    return new Veiculo(
    (int) $row['id'],
    (int) $row['id_user'],
    $row['tipo'],
    $row['matricula'],
    $row['modelo'],
    $row['marca'],
    $row['cor'],
    (int) $row['is_eletric']  
);
}
}