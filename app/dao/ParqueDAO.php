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

    public function numParqueEstatisticas()
    {
        $sql = "SELECT
                    COUNT(*) AS total_parques,
                    COALESCE(SUM(num_max_lugares), 0) AS total_lugares,
                    SUM(CASE WHEN tipo = 'Coberto' THEN 1 ELSE 0 END) AS parques_cobertos,
                    SUM(CASE WHEN tipo = 'Subterrâneo' THEN 1 ELSE 0 END) AS parques_subterraneos,
                    SUM(CASE WHEN tipo = 'Descoberto' THEN 1 ELSE 0 END) AS parques_descobertos
                FROM p_estacionamentos";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByIdParque($id)
    {
        $sql = "SELECT id, id_freguesia, nome, num_max_lugares, tipo, tarifa, longitude, latitude FROM p_estacionamentos WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function createParque($id_freguesia, $nome, $num_max_lugares, $tipo, $tarifa, $longitude, $latitude)
    {
        $sql = "
            INSERT INTO p_estacionamentos
                (id_freguesia, nome, num_max_lugares, tipo, tarifa, longitude, latitude)
            VALUES
                (:id_freguesia, :nome, :num_max_lugares, :tipo, :tarifa, :longitude, :latitude)
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
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

    public function findReservasByLugar(int $lugarId): array
    {
        $sql = "SELECT 
            hr.id AS id_reserva,
            hr.reserved_from,
            hr.reserved_until,
            l.identificacao AS identificacao_lugar,
            pe.id AS id_parque,
            pe.nome AS nome_parque
        FROM historico_reservas hr
        JOIN lugares l ON hr.id_lugar = l.id
        JOIN p_estacionamentos pe ON l.id_p_estacionamento = pe.id
        WHERE hr.id_lugar = ?
            AND NOW() BETWEEN hr.reserved_from AND hr.reserved_until";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$lugarId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertReserva($id_lugar, $id_veiculo, $reserved_from, $reserved_until)
    {
        try {
            $sql = "INSERT INTO historico_reservas (id_lugar, id_veiculo, reserved_from, reserved_until)
                VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id_lugar, $id_veiculo, $reserved_from, $reserved_until]);
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Erro ao inserir reserva: " . $e->getMessage());
        }
    }
}