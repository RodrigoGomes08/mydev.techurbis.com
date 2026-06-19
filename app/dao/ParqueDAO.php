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
        $sql = "SELECT id, id_freguesia, nome, num_max_lugares, tipo, tarifa, longitude, latitude
                FROM p_estacionamentos
                ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $parques = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $parques[] = new Parque(
                (int) $row['id'],
                (int) $row['id_freguesia'],
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
        $sql = "SELECT id, id_freguesia, nome, num_max_lugares, tipo, tarifa, longitude, latitude FROM p_estacionamentos WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function createParque($id, $id_freguesia, $nome, $num_max_lugares, $tipo, $tarifa, $longitude, $latitude)
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
                (id, id_freguesia, nome, num_max_lugares, tipo, tarifa, longitude, latitude)
            VALUES
                (:id, :id_freguesia, :nome, :num_max_lugares, :tipo, :tarifa, :longitude, :latitude)
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'id_freguesia' => $id_freguesia,
            'nome' => $nome,
            'num_max_lugares' => $num_max_lugares,
            'tipo' => $tipo,
            'tarifa' => $tarifa,
            'longitude' => $longitude,
            'latitude' => $latitude
        ]);

        return (int) $this->conn->lastInsertId();
    }

    public function parqueUpdateDAO($id, $id_freguesia, $nome, $num_max_lugares, $tipo, $tarifa, $longitude, $latitude)
    {
        $sql = "UPDATE p_estacionamentos
                SET id_freguesia = ?, nome = ?, num_max_lugares = ?, tipo = ?, tarifa = ?, longitude = ?, latitude = ?
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_freguesia, $nome, $num_max_lugares, $tipo, $tarifa, $longitude, $latitude, $id]);

        return $stmt->rowCount();
    }

    public function parqueDeleteDAO($id)
    {
        $sql = "DELETE FROM p_estacionamentos WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->rowCount();
    }

    public function getAllParquesComLugaresApi()
    {
        $sql = "SELECT 
                p.id, p.id_freguesia, p.nome, p.num_max_lugares, p.tipo, p.tarifa, p.longitude, p.latitude,
                l.id AS lugar_id, l.id_tipo_lugares, l.identificacao, l.ocupado
            FROM p_estacionamentos p
            LEFT JOIN lugares l ON l.id_p_estacionamento = p.id
            ORDER BY p.id ASC, l.id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $parques = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $p_id = $row['id'];

            // Cria o parque apenas uma vez
            if (!isset($parques[$p_id])) {
                $parques[$p_id] = [
                    'id' => (int) $row['id'],
                    'id_freguesia' => (int) $row['id_freguesia'],
                    'nome' => (string) $row['nome'],
                    'num_max_lugares' => (int) $row['num_max_lugares'],
                    'tipo' => (string) $row['tipo'],
                    'tarifa' => (float) $row['tarifa'],
                    'longitude' => (float) $row['longitude'],
                    'latitude' => (float) $row['latitude'],
                    'lugares' => [],
                ];
            }

            // Adiciona o lugar ao parque (se existir)
            if ($row['lugar_id'] !== null) {
                $parques[$p_id]['lugares'][] = [
                    'id' => (int) $row['lugar_id'],
                    'id_p_estacionamento' => (int) $row['id'],
                    'id_tipo_lugares' => (int) $row['id_tipo_lugares'],
                    'identificacao' => (string) $row['identificacao'],
                    'ocupado' => (bool) $row['ocupado'],
                ];
            }
        }

        // Re-indexa para array simples (sem chaves de id)
        return array_values($parques);
    }

    public function findOneComLugares(int $id): ?array
    {
        $sql = "SELECT 
                p.id, p.id_freguesia, p.nome, p.num_max_lugares, p.tipo, p.tarifa, p.longitude, p.latitude,
                l.id AS lugar_id, l.id_p_estacionamento, l.id_tipo_lugares, l.identificacao, l.ocupado
            FROM p_estacionamentos p
            LEFT JOIN lugares l ON l.id_p_estacionamento = p.id
            WHERE p.id = ?
            ORDER BY l.id ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        $parque = null;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Cria o parque apenas uma vez
            if ($parque === null) {
                $parque = [
                    'id' => (int) $row['id'],
                    'id_freguesia' => (int) $row['id_freguesia'],
                    'nome' => (string) $row['nome'],
                    'num_max_lugares' => (int) $row['num_max_lugares'],
                    'tipo' => (string) $row['tipo'],
                    'tarifa' => (float) $row['tarifa'],
                    'longitude' => (float) $row['longitude'],
                    'latitude' => (float) $row['latitude'],
                    'lugares' => [],
                ];
            }

            // Adiciona o lugar (se existir)
            if ($row['lugar_id'] !== null) {
                $parque['lugares'][] = [
                    'id' => (int) $row['lugar_id'],
                    'id_p_estacionamento' => (int) $row['id_p_estacionamento'],
                    'id_tipo_lugares' => (int) $row['id_tipo_lugares'],
                    'identificacao' => (string) $row['identificacao'],
                    'ocupado' => (bool) $row['ocupado'],
                ];
            }
        }

        return $parque; // null se não encontrado
    }

    public function findLugaresLivresByParque(int $parqueId): array
    {
        $sql = "SELECT id, identificacao, id_tipo_lugares
            FROM lugares
            WHERE id_p_estacionamento = ? AND ocupado = 0
            ORDER BY identificacao ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$parqueId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findReservasByLugar(int $lugarId): array
    {
        $sql = "SELECT 
            rl.id AS id_reserva,
            rl.reserved_from,
            rl.reserved_until,
            l.identificacao AS identificacao_lugar,
            pe.id AS id_parque,
            pe.nome AS nome_parque
        FROM reservas_lugares rl
        JOIN lugares l ON rl.id_lugar = l.id
        JOIN p_estacionamentos pe ON l.id_p_estacionamento = pe.id
        WHERE rl.id_lugar = ?
            AND NOW() BETWEEN rl.reserved_from AND rl.reserved_until";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$lugarId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}