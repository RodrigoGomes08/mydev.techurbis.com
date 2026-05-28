<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Poste.php';

class PosteDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new DataBase())->connect();
    }

    public function getAllPostes()
    {
        $sql = "SELECT id, id_cidade, id_estado, longitude, latitude, observacao FROM candeeiro_urbanos ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $postes = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $postes[] = new Poste(
                $row['id'],
                $row['id_cidade'],
                $row['id_estado'],
                $row['longitude'],
                $row['latitude'],
                $row['observacao']
            );
        }

        return $postes;
    }

    public function findByID($id)
    {
        $sql = "SELECT * FROM candeeiro_urbanos WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function createPoste($id, $id_cidade, $id_estado, $longitude, $latitude, $observacao)
    {
        $sqlCheck = "SELECT id FROM candeeiro_urbanos WHERE id = :id";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':id', $id);
        $stmtCheck->execute();

        if ($stmtCheck->fetch()) {
            throw new Exception("Poste com o ID \"{$id}\" já existe.");
        }

        $sql = "
            INSERT INTO candeeiro_urbanos
                (id, id_cidade, id_estado, longitude, latitude, observacao)
            VALUES
                (:id, :id_cidade, :id_estado, :longitude, :latitude, :observacao)
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'id_cidade' => $id_cidade,
            'id_estado' => $id_estado,
            'longitude' => $longitude,
            'latitude' => $latitude,
            'observacao' => $observacao,
        ]);

        return (int) $this->conn->lastInsertId();
    }

    public function posteUpdateDAO($id, $id_cidade, $id_estado, $longitude, $latitude, $observacao)
    {
        $sql = "UPDATE candeeiro_urbanos SET id_cidade = ?, id_estado = ?, longitude = ?, latitude = ?, observacao = ? WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_cidade, $id_estado, $longitude, $latitude, $observacao, $id]);

        return $stmt->rowCount();
    }

    public function posteDeleteDAO($id)
    {
        $sql = "DELETE FROM candeeiro_urbanos WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->rowCount();
    }

    public function numPosteEstado()
    {
        $sql = "
        SELECT SUM(CASE WHEN e.nome = 'avariado' THEN 1 ELSE 0 END) AS candeeiros_avariados, SUM(CASE WHEN e.nome = 'operacional' THEN 1 ELSE 0 END) AS candeeiros_operacionais, SUM(CASE WHEN e.nome = 'manutencao' THEN 1 ELSE 0 END) AS candeeiros_em_manutencao
            FROM candeeiro_urbanos cu
            INNER JOIN estados e ON cu.id_estado = e.id;
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function posteInsertObsDAO($id)
    {
        $sql = "UPDATE candeeiro_urbanos SET observacao = ? WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->rowCount();
    }
}
