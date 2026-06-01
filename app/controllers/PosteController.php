<?php

use Composer\DependencyResolver\Transaction;

require_once __DIR__ . '/../dao/PosteDAO.php';

class PosteController
{
    private function view($name, $data = [])
    {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../../public/admin/views/' . $name . '.php';
    }

    public function showPortalADMPostes()
    {
        try {
            if (empty($_SESSION['token'])) {
                header("Location: /login");
                exit;
            }

            $posteDAO = new PosteDAO();
            $postes = $posteDAO->getAllPostes();
            $numPostePorEstado = $posteDAO->numPosteEstado();

            $estadoDAO = new EstadoDAO();
            $estados = $estadoDAO->getAllEstados();

            $this->view('portalADMPostes', [
                'postes' => $postes,
                'numPostePorEstado' => $numPostePorEstado,
                'estados' => $estados
            ]);

        } catch (Exception $e) {

        }
    }

    public function createPoste()
    {
        if (empty($_SESSION['token'])) {
            header("Location: /login");
            exit;
        }

        $id = trim($_POST['id'] ?? '');
        $id_cidade = trim($_POST['id_cidade'] ?? '');
        $id_estado = trim($_POST['id_estado'] ?? '');
        $longitude = trim($_POST['longitude'] ?? '');
        $latitude = trim($_POST['latitude'] ?? '');

        if (empty($id) || empty($id_cidade) || empty($id_estado) || empty($longitude) || empty($latitude)) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => 'Todos os campos são obrigatórios.'
            ];
            header("Location: /admin/PortalADMPostes");
            exit;
        }

        try {
            $posteDAO = new PosteDAO();
            $posteDAO->createPoste($id, $id_cidade, $id_estado, $longitude, $latitude);

            $_SESSION['toast'] = [
                'type' => 'success',
                'message' => "Poste \"{$id}\" criado com sucesso!"
            ];
        } catch (Exception $e) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => $e->getMessage()
            ];
        }

        header("Location: /admin/PortalADMPostes");
        exit;
    }

    public function posteUpdate($id)
    {
        try {
            $id = trim($_POST["id"] ?? '');
            $id_cidade = trim($_POST["id_cidade"] ?? '');
            $id_estado = trim($_POST["id_estado"] ?? '');
            $longitude = trim($_POST["longitude"] ?? '');
            $latitude = trim($_POST["latitude"] ?? '');

            if (empty($id) || empty($id_cidade) || empty($id_estado) || empty($longitude) || empty($latitude)) {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'message' => 'Todos os campos são obrigatórios.'
                ];
                header("Location: /admin/PortalADMPostes");
                exit;
            }

            $linhasAlteradas = (new PosteDAO())->posteUpdateDAO($id, $id_cidade, $id_estado, $longitude, $latitude);

            if (!$linhasAlteradas) {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'message' => 'Nenhuma alteração foi feita (dados iguais aos existentes).'
                ];
            } else {
                $_SESSION['toast'] = [
                    'type' => 'success',
                    'message' => "Poste \"{$id}\" atualizado com sucesso!"
                ];
            }
        } catch (Exception $e) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => $e->getMessage()
            ];
        }

        header("Location: /admin/PortalADMPostes");
        exit;
    }

    public function numPosteEstado()
    {
        $posteDAO = new PosteDAO();
        $postes = $posteDAO->numPosteEstado();

        return $postes;
    }

    public function PosteDelete($postId)
    {
        header('Content-Type: application/json');

        try {
            $linhasAlteradas = (new PosteDAO())->posteDeleteDAO($postId);

            if (!$linhasAlteradas) {
                echo json_encode(['success' => false, 'message' => 'Poste não encontrado.']);
            } else {
                echo json_encode(['success' => true, 'message' => 'Poste eliminado com sucesso!']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    // API
    public function posteListApi()
    {
        $posteDAO = new PosteDAO();
        $postes = $posteDAO->getAllPostesAPI();

        Utils::jsonResponse([
            'success' => true,
            'message' => 'Lista de postes obtida com sucesso.',
            'data' => [
                "postes" => $postes
            ]
        ]);
    }

    public function posteDetailApi($id)
    {
        try {
            $postes = (new PosteDAO())->findByID($id);

            if (!$postes) {
                throw new Exception("Poste não encontrado");
            }

            Utils::jsonResponse([
                'success' => true,
                'message' => 'Detalhe do poste obtido com sucesso.',
                'data' => [
                    "postes" => $postes
                ]
            ]);

        } catch (Exception $e) {
            Utils::jsonResponse([
                'success' => false,
                'message' => 'Poste não encontrado.',
                'data' => []
            ], 404);
        }
    }

    public function posteDetailObsApi($id)
    {
        $pdo = DatabaseSingle::connect();
        $pdo->beginTransaction();

        try {
            $posteDAO = new PosteDAO();
            $postes = (new PosteDAO())->findByID($id);

            $pdo->commit();

        } catch (Exception $e) {
            Utils::jsonResponse([
                'success' => false,
                'message' => 'Poste não encontrado.',
                'data' => []
            ], 404);
        }
    }

    public function insertObsEmPostesApi($id)
    {
        $texto = trim($_POST["texto"] ?? '');

        if (empty($id) || empty($texto)) {
            Utils::jsonResponse([
                'success' => false,
                'message' => 'ID e texto são obrigatórios.',
                'data' => null
            ], 400);
            return;
        }

        try {
            $postesObs = (new PosteDAO())->insertObs($id, $texto);

            Utils::jsonResponse([
                'success' => true,
                'message' => 'Observação inserida com sucesso.',
                'data' => [
                    "postes" => $postesObs
                ]
            ]);
        } catch (Exception $e) {
            Utils::jsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}