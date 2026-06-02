<?php

require_once __DIR__ . '/../dao/ParqueDAO.php';

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

        $this->view('portalADMParques', [
            'parques' => $parques,
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
        $id_cidade = trim($_POST['id_cidade'] ?? '');
        $nome = trim($_POST['nome'] ?? '');
        $num_max_lugares = trim($_POST['num_max_lugares'] ?? '');
        $tipo = trim($_POST['tipo'] ?? '');
        $tarifa = trim($_POST['tarifa'] ?? '');
        $longitude = trim($_POST['longitude'] ?? '');
        $latitude = trim($_POST['latitude'] ?? '');

        if (empty($id) || empty($id_cidade) || empty($nome) || empty($num_max_lugares) || empty($tipo) || empty($longitude) || empty($latitude)) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => 'Todos os campos obrigatórios devem ser preenchidos.'
            ];
            header("Location: /admin/PortalADMParques");
            exit;
        }

            $parqueDAO = new ParqueDAO();
            $parqueDAO->createParque($id, $id_cidade, $nome, $num_max_lugares, $tipo, $tarifa ?: 0, $longitude, $latitude);

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
        $id_cidade = trim($_POST['id_cidade'] ?? '');
        $nome = trim($_POST['nome'] ?? '');
        $num_max_lugares = trim($_POST['num_max_lugares'] ?? '');
        $tipo = trim($_POST['tipo'] ?? '');
        $tarifa = trim($_POST['tarifa'] ?? '');
        $longitude = trim($_POST['longitude'] ?? '');
        $latitude = trim($_POST['latitude'] ?? '');

        if (empty($id) || empty($id_cidade) || empty($nome) || empty($num_max_lugares) || empty($tipo) || empty($longitude) || empty($latitude)) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => 'Todos os campos obrigatórios devem ser preenchidos.'
            ];
            header("Location: /admin/PortalADMParques");
            exit;
        }

            $linhasAlteradas = (new ParqueDAO())->parqueUpdateDAO($id, $id_cidade, $nome, $num_max_lugares, $tipo, $tarifa ?: 0, $longitude, $latitude);

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
        $parques = $parqueDAO->getAllParques();

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

    public function parqueDetailApi($id)
    {
        $pdo = DatabaseSingle::connect();
        $pdo->beginTransaction();

        try {
            $parques = (new ParqueDAO())->findByID($id);

            if (!$parques) {
                throw new Exception("Parque não encontrado");
            }

            $pdo->commit();
            Utils::jsonResponse([
                'success' => true,
                'message' => 'Detalhe do parque obtido com sucesso.',
                'data' => [
                    "detalhes_parque" => $parques
                ]
            ]);

        } catch (Exception $e) {
            $pdo->rollback();
            Utils::jsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 404);
        }
    }
}