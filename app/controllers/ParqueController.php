<?php

require_once __DIR__ . '/../dao/ParqueDAO.php';
require_once __DIR__ . '/../dao/VeiculoDAO.php';
require_once __DIR__ . '/../dao/FreguesiaDAO.php';

class ParqueController
{
    private function view($name, $data = [])
    {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../../public/admin/views/' . $name . '.php';
    }

    public function showPortalADMParques()
    {
        if (empty($_SESSION['token'])) {
            header("Location: /login");
            exit;
        }

        $parqueDAO = new ParqueDAO();
        $parques = $parqueDAO->getAllParques();
        $estatisticasParques = $parqueDAO->numParqueEstatisticas();

        $freguesiaDAO = new FreguesiaDAO();
        $freguesias = $freguesiaDAO->getAllFreguesias();

        $this->view('portalADMParques', [
            'parques' => $parques,
            'estatisticasParques' => $estatisticasParques,
            'freguesias' => $freguesias,
        ]);
    }

    public function createParque()
    {
        try {
            if (empty($_SESSION['token'])) {
                header("Location: /login");
                exit;
            }

            $id = trim($_POST['id'] ?? '');
            $id_freguesia = trim($_POST['id_freguesia'] ?? '');
            $nome = trim($_POST['nome'] ?? '');
            $num_max_lugares = trim($_POST['num_max_lugares'] ?? '');
            $tipo = trim($_POST['tipo'] ?? '');
            $tarifa = trim($_POST['tarifa'] ?? '');
            $longitude = trim($_POST['longitude'] ?? '');
            $latitude = trim($_POST['latitude'] ?? '');

            if (empty($id_freguesia) || empty($nome) || empty($num_max_lugares) || empty($tipo) || empty($longitude) || empty($latitude)) {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'message' => 'Todos os campos obrigatórios devem ser preenchidos.'
                ];
                header("Location: /admin/PortalADMParques");
                exit;
            }

            $parqueDAO = new ParqueDAO();
            $parqueDAO->createParque($id_freguesia, $nome, $num_max_lugares, $tipo, $tarifa ?: 0, $longitude, $latitude);

            $_SESSION['toast'] = [
                'type' => 'success',
                'message' => "Parque \"{$nome}\" criado com sucesso!"
            ];
        } catch (Exception $e) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => $e->getMessage()
            ];
        }

        header("Location: /admin/PortalADMParques");
        exit;
    }

    public function parqueUpdate($id)
    {
        try {
            if (empty($_SESSION['token'])) {
                header("Location: /login");
                exit;
            }

            $id = trim($_POST['id'] ?? '');
            $id_freguesia = trim($_POST['id_freguesia'] ?? '');
            $nome = trim($_POST['nome'] ?? '');
            $num_max_lugares = trim($_POST['num_max_lugares'] ?? '');
            $tipo = trim($_POST['tipo'] ?? '');
            $tarifa = trim($_POST['tarifa'] ?? '');
            $longitude = trim($_POST['longitude'] ?? '');
            $latitude = trim($_POST['latitude'] ?? '');

            if (empty($id_freguesia) || empty($nome) || empty($num_max_lugares) || empty($tipo) || empty($longitude) || empty($latitude)) {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'message' => 'Todos os campos obrigatórios devem ser preenchidos.'
                ];
                header("Location: /admin/PortalADMParques");
                exit;
            }

            $linhasAlteradas = (new ParqueDAO())->parqueUpdateDAO($id, $id_freguesia, $nome, $num_max_lugares, $tipo, $tarifa ?: 0, $longitude, $latitude);

            if (!$linhasAlteradas) {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'message' => 'Nenhuma alteração foi feita (dados iguais aos existentes).'
                ];
            } else {
                $_SESSION['toast'] = [
                    'type' => 'success',
                    'message' => "Parque \"{$nome}\" atualizado com sucesso!"
                ];
            }
        } catch (Exception $e) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => $e->getMessage()
            ];
        }

        header("Location: /admin/PortalADMParques");
        exit;
    }

    public function parqueDelete($parqueId)
    {
        header('Content-Type: application/json');
        try {
            $linhasAlteradas = (new ParqueDAO())->parqueDeleteDAO($parqueId);

            if (!$linhasAlteradas) {
                echo json_encode(['success' => false, 'message' => 'Parque não encontrado.']);
            } else {
                echo json_encode(['success' => true, 'message' => 'Parque eliminado com sucesso!']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    // API
    public function parqueListApi()
    {
        $pdo = DatabaseSingle::connect();
        $pdo->beginTransaction();

        try {
            // if (empty($_SESSION['token'])) {
            //     header("Location: /login");
            //     exit;
            // }

            $parqueDAO = new ParqueDAO();
            $parques = $parqueDAO->getAllParquesComLugaresApi();

            $pdo->commit();
            Utils::jsonResponse([
                'success' => true,
                'message' => 'Lista de parques obtida com sucesso.',
                'data' => [
                    'parques' => $parques
                ]
            ]);
        } catch (Exception $e) {
            $pdo->rollback();
            Utils::jsonResponse([
                'success' => false,
                'message' => 'Erro ao obter a lista de parques: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function parqueDetailApi(int $id): void
    {
        try {
            $parque = (new ParqueDAO())->findOneComLugares($id);

            if (!$parque) {
                throw new Exception("Parque não encontrado");
            }

            Utils::jsonResponse([
                'success' => true,
                'message' => 'Detalhe do parque obtido com sucesso.',
                'data' => [
                    'parque' => $parque
                ]
            ]);
            exit;

        } catch (Exception $e) {
            Utils::jsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 404);
            exit;
        }
    }

    public function getLugaresByParque(int $parqueId): void
    {
        try {
            $lugares = (new ParqueDAO())->findLugaresLivresByParque($parqueId);

             Utils::jsonResponse([
                'success' => true,
                'message' => 'Lugares livres do parque obtidos com sucesso.',
                'data' => [
                    'lugares' => $lugares
                ]
            ]);
            exit;

        } catch (Exception $e) {
            Utils::jsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
            exit;
        }
    }

    public function getReservasByLugar(int $lugarId): void
    {
        try {
            $parqueDAO = new ParqueDAO();
            $reservas = $parqueDAO->findReservasByLugar($lugarId);

            Utils::jsonResponse([
                'success' => true,
                'message' => 'Reservas do lugar obtidas com sucesso.',
                'data' => [
                    'reservas' => $reservas
                ]
            ]);
            exit;

        } catch (Exception $e) {
            Utils::jsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
            exit;
        }
    }

    public function insertReservaEmParqueAPI($id)
    {
        $pdo = DatabaseSingle::connect();
        $pdo->beginTransaction();
        try {
            $id = trim($id ?? '');
            $input = json_decode(file_get_contents('php://input'), true);
            $matricula = trim($input["matricula"] ?? '');
            $reserved_from = trim($input["reserved_from"] ?? '');
            $reserved_until = trim($input["reserved_until"] ?? '');

            if (empty($id) || empty($matricula) || empty($reserved_from) || empty($reserved_until)) {
                Utils::jsonResponse([
                    'success' => false,
                    'message' => 'ID, matrícula, reserved_from e reserved_until são obrigatórios.',
                    'data' => []
                ], 400);
                return;
            }

            $parqueDAO = new ParqueDAO();

            // Verificar se o lugar/parque existe
            $parque = $parqueDAO->findByIdParque($id);
            if (!$parque) {
                throw new Exception("Parque com id '$id' não encontrado.");
            }

            // Obter o veículo pela matrícula
            $veiculoDAO = new VeiculoDAO(); // ajusta ao nome real da tua DAO
            $veiculo = $veiculoDAO->findByMatricula($matricula);
            if (!$veiculo) {
                throw new Exception("Veículo com matrícula '$matricula' não encontrado.");
            }

            $idReserva = $parqueDAO->insertReserva($id, $veiculo->getId(), $reserved_from, $reserved_until);

            $pdo->commit();
            Utils::jsonResponse([
                'success' => true,
                'message' => 'Reserva inserida com sucesso.',
                'data' => ['id_reserva' => $idReserva]
            ]);
        } catch (Exception $e) {
            $pdo->rollback();
            Utils::jsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}