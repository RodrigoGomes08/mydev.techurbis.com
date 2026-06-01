<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Contentor.php';

class ContentorDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new DataBase())->connect();
    }

    public function getAllContentores()
    {
        $sql = "SELECT id, id_cidade, id_estado, capacidade_max, longitude, latitude, tipo, identificacao, is_full
                FROM contentores
                ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $contentores = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $estado = $this->getEstadoContentorById($row['id']);
            $peso = $this->getPesoContentorById($row['id']);
            $obs = $this->getObsByContentorId($row['id']);

            $contentores[] = new Contentor(
                (int) $row['id'],
                (int) $row['id_cidade'],
                (int) $row['id_estado'],
                (int) $row['capacidade_max'],
                (float) $row['longitude'],
                (float) $row['latitude'],
                $row['tipo'],
                $row['identificacao'],
                (bool) $row['is_full'],
                $estado,
                $peso,
                $obs
            );
        }

        return $contentores;
    }

    public function getPesoContentorById($id)
    {
        $sql = 'SELECT c.identificacao, c.tipo, MAX(cl.data_leitura) AS ultima_leitura, cl.peso
            FROM contentores c
            INNER JOIN contentor_leituras cl ON c.id = cl.id_contentor
            WHERE c.id = ?
            GROUP BY c.id, c.identificacao, c.tipo, cl.peso
            ORDER BY cl.peso DESC;
        ';

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        $peso = $stmt->fetch(PDO::FETCH_ASSOC);

        return $peso;
    }

    public function getEstadoContentorById($id)
    {
        $sql = 'SELECT e.id, e.nome, e.cor 
            FROM estados e
            INNER JOIN contentores c ON e.id = c.id_estado
            WHERE c.id = ?
        ';

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        $estadoContentor = $stmt->fetch(PDO::FETCH_ASSOC);
        return $estadoContentor;

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

    public function createContentor($id, $id_cidade, $id_estado, $capacidade_max, $longitude, $latitude, $tipo, $identificacao, $observacao)
    {
        $sqlCheck = "SELECT id FROM contentores WHERE id = :id";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':id', $id);
        $stmtCheck->execute();

        if ($stmtCheck->fetch()) {
            throw new Exception("Contentor com o ID \"{$id}\" já existe.");
        }

        $sql = "
            INSERT INTO contentores
                (id, id_cidade, id_estado, capacidade_max, longitude, latitude, tipo, identificacao, is_full)
            VALUES
                (:id, :id_cidade, :id_estado, :capacidade_max, :longitude, :latitude, :tipo, :identificacao, 0)
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'id_cidade' => $id_cidade,
            'id_estado' => $id_estado,
            'capacidade_max' => $capacidade_max,
            'longitude' => $longitude,
            'latitude' => $latitude,
            'tipo' => $tipo,
            'identificacao' => $identificacao,
        ]);

        return (int) $this->conn->lastInsertId();
    }

    public function contentorUpdateDAO($id, $id_cidade, $id_estado, $capacidade_max, $longitude, $latitude, $tipo, $identificacao)
    {
        $sql = "UPDATE contentores
                SET id_cidade = ?, id_estado = ?, capacidade_max = ?, longitude = ?, latitude = ?, tipo = ?, identificacao = ?
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_cidade, $id_estado, $capacidade_max, $longitude, $latitude, $tipo, $identificacao, $id]);

        return $stmt->rowCount();
    }

    public function contentorDeleteDAO($id)
    {
        $sql = "DELETE FROM contentores WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->rowCount();
    }

    public function numContentorEstado()
    {
        $sql = "SELECT SUM(CASE WHEN e.nome = 'critico' THEN 1 ELSE 0 END) AS contentores_criticos, SUM(CASE WHEN e.nome = 'normal' THEN 1 ELSE 0 END) AS contentores_normais, SUM(CASE WHEN e.nome = 'atencao' THEN 1 ELSE 0 END) AS contentores_em_atencao
                FROM contentores c
                INNER JOIN estados e ON c.id_estado = e.id;
            ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertObs($id_contentor, $texto)
    {
        $sql = "INSERT INTO contentor_observacoes (id_contentor, texto, created_at)
            VALUES (?, ?, NOW())";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_contentor, $texto]);

        return $stmt->rowCount();
    }

    public function getObsByContentorId($id_contentor)
    {
        $sql = "SELECT id, id_contentor, texto, created_at
            FROM contentor_observacoes 
            WHERE id_contentor = ? 
            ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_contentor]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}