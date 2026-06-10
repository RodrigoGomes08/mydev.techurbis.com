<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Sensor.php';
require_once __DIR__ . '/../models/SensorLeituras.php';

class SensorDAO
{

    private $conn;

    public function __construct()
    {
        $this->conn = (new DataBase())->connect();
    }

    public function getAllSensores()
    {
        $sql = "SELECT s.id AS sensor_id, s.id_cidade, s.nome, sl.id AS leitura_id, sl.data_leitura, sl.leitura_string, sl.leitura_num
                FROM sensores s
                LEFT JOIN sensor_leituras sl ON s.id = sl.id_sensor
                ORDER BY s.id ASC, sl.data_leitura DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $sensoresMap = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sensorId = (int) $row['sensor_id'];

            // Cria o sensor só uma vez por id
            if (!isset($sensoresMap[$sensorId])) {
                $sensoresMap[$sensorId] = new Sensor(
                    $sensorId,
                    (int) $row['id_cidade'],
                    $row['nome']
                );
            }

            // Adiciona a leitura ao sensor (se existir)
            if ($row['leitura_id'] !== null) {
                $leitura = new SensorLeituras(
                    (int) $row['leitura_id'],
                    $sensorId,
                    new DateTime($row['data_leitura']),
                    $row['leitura_string'],
                    (float) $row['leitura_num']
                );
                $sensoresMap[$sensorId]->addLeitura($leitura);
            }
        }

        return array_values($sensoresMap);
    }

    public function findByID($id)
    {
        $sql = "SELECT s.id AS sensor_id, s.id_cidade, s.nome, sl.id AS leitura_id, sl.data_leitura, sl.leitura_string, sl.leitura_num
                FROM sensores s
                LEFT JOIN sensor_leituras sl ON s.id = sl.id_sensor
                WHERE s.id = :id
                ORDER BY sl.data_leitura DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $sensor = null;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($sensor === null) {
                $sensor = new Sensor(
                    (int) $row['sensor_id'],
                    (int) $row['id_cidade'],
                    $row['nome']
                );
            }

            if ($row['leitura_id'] !== null) {
                $leitura = new SensorLeituras(
                    (int) $row['leitura_id'],
                    (int) $row['sensor_id'],
                    new DateTime($row['data_leitura']),
                    $row['leitura_string'],
                    (float) $row['leitura_num']
                );
                $sensor->addLeitura($leitura);
            }
        }

        return $sensor;
    }

    public function getLast15ValoresSensor($id)
    {
        $sql = "SELECT id, id_sensor, data_leitura, leitura_string, leitura_num FROM sensor_leituras 
            WHERE id_sensor = :id 
            ORDER BY id DESC 
            LIMIT 15";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $valores = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $valores[] = $row;
        }
        return $valores;
    }

    public function getLugarParaOcupar(int $lugarId): array
{
    
        $sql = "SELECT id, identificacao, ocupado
            FROM lugares
            WHERE id = ?
            LIMIT 1
            FOR UPDATE
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$lugarId]);
        $lugar = $stmt->fetch(PDO::FETCH_ASSOC);

        return $lugar;
}

public function marcarComoOcupado(int $lugarId): void
{
    $sql = "UPDATE lugares
            SET ocupado = 1
            WHERE id = ?";
    $this->conn->prepare($sql)->execute([$lugarId]);

    $sql = "INSERT INTO use_his_ocu_lug (
                id_lugar, id_p_estacionamento_user, entered_at
            )
            SELECT l.id, ps.id, NOW()
            FROM lugares l
            INNER JOIN p_estacionamento_users ps 
                ON ps.id_p_estacionamento = l.id_p_estacionamento
            WHERE l.id = ?
            LIMIT 1";
    $this->conn->prepare($sql)->execute([$lugarId]);
}

// 3. Busca o lugar atualizado para devolver
public function getLugarFinal(int $lugarId): array
{
    $sql = "SELECT 
                l.id,
                l.identificacao,
                l.ocupado,
                h.entered_at,
                h.left_at
            FROM lugares l
            LEFT JOIN use_his_ocu_lug h 
                ON h.id_lugar = l.id
            WHERE l.id = ?
            ORDER BY h.id DESC
            LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$lugarId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function getLugarParaDesocupar(int $lugarId): array|false
{
    $sql = "SELECT id, identificacao, ocupado
            FROM lugares
            WHERE id = ?
            LIMIT 1
            FOR UPDATE";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$lugarId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function getHistoricoAberto(int $lugarId): array|false
{
    $sql = "SELECT id
            FROM use_his_ocu_lug
            WHERE id_lugar = ?
              AND left_at IS NULL
            ORDER BY id DESC
            LIMIT 1
            FOR UPDATE";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$lugarId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function marcarComoLivre(int $lugarId, int $historicoId): void
{
    $sql = "UPDATE use_his_ocu_lug
            SET left_at = NOW()
            WHERE id = ?";
    $this->conn->prepare($sql)->execute([$historicoId]);

    $sql = "UPDATE lugares
            SET ocupado = 0
            WHERE id = ?";
    $this->conn->prepare($sql)->execute([$lugarId]);
}

public function getHistorico(int $lugarId): array
{
    $sql = "SELECT
                id,
                id_lugar,
                id_p_estacionamento_user,
                entered_at,
                left_at
            FROM use_his_ocu_lug
            WHERE id_lugar = ?
            ORDER BY id DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$lugarId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function findContentorById(int $id): array|false
{
    $sql = "SELECT id, id_cidade, id_estado, capacidade_max, is_full
            FROM contentores
            WHERE id = ?
            LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function insertLeituraContentor(int $contentorId, float $valor, bool $isFull): void
{
    // Insere a leitura
    $sql = "INSERT INTO contentor_leituras (id_contentor, valor, data_leitura)
            VALUES (?, ?, NOW())";
    $this->conn->prepare($sql)->execute([$contentorId, $valor]);

    // Atualiza is_full no contentor
    $sql = "UPDATE contentores
            SET is_full = ?
            WHERE id = ?";
    $this->conn->prepare($sql)->execute([$isFull ? 1 : 0, $contentorId]);
}
}
