<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Parque.php';

class ParqueDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new DataBase())->connect();
    }

    public function getAllParques()
    {
        $sql = "SELECT id, id_cidade, nome, num_max_lugares, tipo, tarifa, longitude, latitude
                FROM p_estacionamentos
                ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $parques = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $parques[] = new Parque(
                (int) $row['id'],
                (int) $row['id_cidade'],
                (String) $row['nome'],
                (int) $row['num_max_lugares'],
                (String) $row['tipo'],
                (float) $row['tarifa'],
                (float) $row['longitude'],
                (float) $row['latitude']
            );
        }

        return $parques;
    }

    public function findByID($id)
    {
        $sql = "SELECT * FROM contentores WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function createParque($id, $id_cidade, $nome, $num_max_lugares, $tipo , $tarifa, $longitude, $latitude)
    {
        $sqlCheck = "SELECT id FROM p_estacionamentos WHERE id = :id";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':id', $id);
        $stmtCheck->execute();

        if ($stmtCheck->fetch()) {
            throw new Exception("Parque com o ID \"{$id}\" já existe.");
        }

        $sql = "
            INSERT INTO p_estacionamentos
                (id, id_cidade, nome, num_max_lugares, tipo, tarifa, longitude, latitude)
            VALUES
                (:id, :id_cidade, :nome, :num_max_lugares, :tipo, :tarifa, :longitude, :latitude)
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'id_cidade' => $id_cidade,
            'nome' => $nome,
            'num_max_lugares' => $num_max_lugares,
            'tipo' => $tipo,
            'tarifa' => $tarifa,
            'longitude' => $longitude,
            'latitude' => $latitude
        ]);

        return (int) $this->conn->lastInsertId();
    }

    public function parqueUpdateDAO($id, $id_cidade, $nome, $num_max_lugares, $tipo , $tarifa, $longitude, $latitude)
    {
        $sql = "UPDATE p_estacionamentos
                SET id_cidade = ?, nome = ?, num_max_lugares = ?, tipo = ?, tarifa = ?, longitude = ?, latitude = ?
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_cidade, $nome, $num_max_lugares, $tipo, $tarifa, $longitude, $latitude, $id]);

        return $stmt->rowCount();
    }

    public function parqueDeleteDAO($id)
    {
        $sql = "DELETE FROM p_estacionamentos WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->rowCount();
    }

    public function getStats() {
        $sql = "
            SELECT tl.id, tl.tipo, count(tl.id) FROM p_estacionamentos p INNER JOIN lugares l ON l.id_p_estacionamento = p.id INNER JOIN tipo_lugares tl ON l.id_tipo_lugares = tl.id GROUP BY tl.id;
        ";
    }
}