<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Poste.php';
require_once __DIR__ . '/EstadoDAO.php';

class PosteDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new DataBase())->connect();
    }

    public function getAllPostes()
    {
        $sql = "SELECT cu.id, cu.id_cidade, cu.id_estado, cu.longitude, cu.latitude,
                       (SELECT texto FROM candeeiro_observacoes
                        WHERE id_candeeiro_urbano = cu.id
                        ORDER BY id DESC LIMIT 1) AS observacao
                FROM candeeiro_urbanos cu
                ORDER BY cu.id ASC";

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
                $row['observacao'] ?? ''
            );
        }

        return $postes;
    }

    public function getAllPostesAPI()
    {
        $sql = "SELECT id, id_cidade, id_estado, longitude, latitude FROM candeeiro_urbanos ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $postes = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $estado = (new EstadoDAO())->findByID($row['id_estado']);
            $row['estado'] = $estado;
            $postes[] = $row;
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

    public function createPoste($id, $id_cidade, $id_estado, $longitude, $latitude)
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
                (id, id_cidade, id_estado, longitude, latitude)
            VALUES
                (:id, :id_cidade, :id_estado, :longitude, :latitude)
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'id_cidade' => $id_cidade,
            'id_estado' => $id_estado,
            'longitude' => $longitude,
            'latitude' => $latitude,
        ]);

        return (int) $this->conn->lastInsertId();
    }

    public function posteUpdateDAO($id, $id_cidade, $id_estado, $longitude, $latitude)
    {
        $sql = "UPDATE candeeiro_urbanos SET id_cidade = ?, id_estado = ?, longitude = ?, latitude = ? WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_cidade, $id_estado, $longitude, $latitude, $id]);

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
            SELECT
                SUM(CASE WHEN e.nome = 'avariado'     THEN 1 ELSE 0 END) AS candeeiros_avariados,
                SUM(CASE WHEN e.nome = 'operacional'  THEN 1 ELSE 0 END) AS candeeiros_operacionais,
                SUM(CASE WHEN e.nome = 'manutencao'   THEN 1 ELSE 0 END) AS candeeiros_em_manutencao
            FROM candeeiro_urbanos cu
            INNER JOIN estados e ON cu.id_estado = e.id
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertObs($id_candeeiro_urbano, $texto)
    {
        $sql = "INSERT INTO candeeiro_observacoes (id_candeeiro_urbano, texto)
                VALUES (?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_candeeiro_urbano, $texto]);

        return $stmt->rowCount();
    }

    public function getObservacaoByPoste($id)
    {
        $sql = "SELECT texto
                FROM candeeiro_observacoes
                WHERE id_candeeiro_urbano = ?
                ORDER BY id DESC
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetchColumn();
    }
}