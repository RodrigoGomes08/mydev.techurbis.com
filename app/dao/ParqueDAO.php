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
                    SUM(CASE WHEN tipo = 'Coberto'      THEN 1 ELSE 0 END) AS parques_cobertos,
                    SUM(CASE WHEN tipo = 'Subterrâneo'  THEN 1 ELSE 0 END) AS parques_subterraneos,
                    SUM(CASE WHEN tipo = 'Descoberto'   THEN 1 ELSE 0 END) AS parques_descobertos
                FROM p_estacionamentos";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ CORRIGIDO: calcula a média global de ocupação entre todos os parques
    //    e o nome do método foi normalizado (sem ç) para consistência com o controller
    public function ocupacaoMediaDosParques(): array
    {
        $sql = "SELECT ROUND(AVG(sub.pct), 2) AS ocupacao_percentual
                FROM (
                    SELECT
                        SUM(CASE WHEN l.ocupado = 1 THEN 1 ELSE 0 END) * 100.0
                            / NULLIF(COUNT(l.id), 0) AS pct
                    FROM p_estacionamentos p
                    LEFT JOIN lugares l ON l.id_p_estacionamento = p.id
                    GROUP BY p.id
                ) sub";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['ocupacao_percentual' => 0];
    }

    public function estadoSistemaParques(): array
{
    $sql = "SELECT
                SUM(CASE WHEN sub.pct < 50 THEN 1 ELSE 0 END) AS em_bom_estado,
                COUNT(*) AS total
            FROM (
                SELECT
                    p.id,
                    SUM(CASE WHEN l.ocupado = 1 THEN 1 ELSE 0 END) * 100.0
                        / NULLIF(p.num_max_lugares, 0) AS pct
                FROM p_estacionamentos p
                LEFT JOIN lugares l ON l.id_p_estacionamento = p.id
                GROUP BY p.id, p.num_max_lugares
            ) sub";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['em_bom_estado' => 0, 'total' => 0];
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
            'latitude' => $latitude,
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

            if ($row['lugar_id'] !== null) {
                $parque['lugares'][] = [
                    'id' => (int) $row['lugar_id'],
                    'id_p_estacionamento' => (int) $row['id_p_estacionamento'],
                    'id_tipo_lugares' => (int) $row['id_tipo_lugares'],
                    'identificacao' => (string) $row['identificacao'],
                    'ocupado' => (bool) $row['ocupado'],
                    'reservado' => false,
                ];
            }
        }

        if ($parque !== null) {
            $idsReservados = $this->getLugaresReservadosAgora($id);

            foreach ($parque['lugares'] as &$lugar) {
                $lugar['reservado'] = in_array($lugar['id'], $idsReservados, true);
            }
            unset($lugar);
        }

        return $parque;
    }

    /**
     * Devolve apenas os lugares de um parque específico (sem os dados do parque),
     * usado pelo modal de reserva para popular o spinner de seleção de lugar.
     *
     * @return array<int, array{id:int, id_p_estacionamento:int, id_tipo_lugares:int, identificacao:string, ocupado:bool}>
     */
    public function getLugaresByParque(int $idParque): array
    {
        $sql = "SELECT id, id_p_estacionamento, id_tipo_lugares, identificacao, ocupado
                FROM lugares
                WHERE id_p_estacionamento = ?
                ORDER BY id ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idParque]);

        $lugares = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $lugares[] = [
                'id' => (int) $row['id'],
                'id_p_estacionamento' => (int) $row['id_p_estacionamento'],
                'id_tipo_lugares' => (int) $row['id_tipo_lugares'],
                'identificacao' => (string) $row['identificacao'],
                'ocupado' => (bool) $row['ocupado'],
            ];
        }

        return $lugares;
    }

    /**
     * Devolve os IDs dos lugares de um parque que têm uma reserva ativa neste momento.
     *
     * @return array<int>
     */
    public function getLugaresReservadosAgora(int $idParque): array
    {
        $sql = "SELECT DISTINCT hr.id_lugar
                FROM historico_reservas hr
                INNER JOIN lugares l ON l.id = hr.id_lugar
                WHERE l.id_p_estacionamento = ?
                  AND NOW() BETWEEN hr.reserved_from AND hr.reserved_until";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idParque]);

        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    /**
     * Procura um único lugar pelo id.
     */
    public function findLugarById(int $idLugar): ?array
    {
        $sql = "SELECT id, id_p_estacionamento, id_tipo_lugares, identificacao, ocupado
                FROM lugares
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idLugar]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return [
            'id' => (int) $row['id'],
            'id_p_estacionamento' => (int) $row['id_p_estacionamento'],
            'id_tipo_lugares' => (int) $row['id_tipo_lugares'],
            'identificacao' => (string) $row['identificacao'],
            'ocupado' => (bool) $row['ocupado'],
        ];
    }

    /**
     * Procura um veículo pela matrícula, garantindo que pertence ao utilizador autenticado.
     */
    public function findVeiculoByMatriculaEUser(string $matricula, int $idUser): ?array
    {
        $sql = "SELECT id, id_user, tipo, matricula, modelo, marca, cor, is_eletric
                FROM veiculos
                WHERE matricula = ? AND id_user = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$matricula, $idUser]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /**
     * Cria uma reserva em historico_reservas.
     */
    public function createReserva(int $idLugar, int $idVeiculo, string $reservedFrom, string $reservedUntil): int
    {
        $sql = "INSERT INTO historico_reservas (id_lugar, id_veiculo, reserved_from, reserved_until)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idLugar, $idVeiculo, $reservedFrom, $reservedUntil]);

        return (int) $this->conn->lastInsertId();
    }

    /**
     * Devolve o histórico de reservas de um lugar específico, mais recentes primeiro.
     */
    public function findReservasByLugar(int $idLugar): array
    {
        $sql = "SELECT hr.id, hr.id_lugar, hr.id_veiculo, hr.reserved_from, hr.reserved_until,
                       v.matricula
                FROM historico_reservas hr
                LEFT JOIN veiculos v ON v.id = hr.id_veiculo
                WHERE hr.id_lugar = ?
                ORDER BY hr.reserved_from DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idLugar]);

        $reservas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reservas[] = [
                'id' => (int) $row['id'],
                'id_lugar' => (int) $row['id_lugar'],
                'id_veiculo' => (int) $row['id_veiculo'],
                'matricula' => $row['matricula'] !== null ? (string) $row['matricula'] : null,
                'reserved_from' => $row['reserved_from'],
                'reserved_until' => $row['reserved_until'],
            ];
        }

        return $reservas;
    }
}