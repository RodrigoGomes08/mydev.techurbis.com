<?php

use Composer\DependencyResolver\Transaction;

require_once __DIR__ . '/../dao/PosteDAO.php';
require_once __DIR__ . '/../config/DatabaseSingle.php';
require_once __DIR__ . '/../dao/FreguesiaDAO.php';

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

            $freguesiaDAO = new FreguesiaDAO();
            $freguesias = $freguesiaDAO->getAllFreguesias();

            $this->view('portalADMPostes', [
                'postes' => $postes,
                'numPostePorEstado' => $numPostePorEstado,
                'estados' => $estados,
                'freguesias' => $freguesias
            ]);

        } catch (Exception $e) {
            $_SESSION['toast'] = ['type' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function createPoste()
    {
        $pdo = DatabaseSingle::connect();
        $pdo->beginTransaction();

        try {

            if (empty($_SESSION['token'])) {
                header("Location: /login");
                exit;
            }

            $id = trim($_POST['id'] ?? '');
            $id_freguesia = trim($_POST['id_freguesia'] ?? '');
            $id_estado = trim($_POST['id_estado'] ?? '');
            $longitude = trim($_POST['longitude'] ?? '');
            $latitude = trim($_POST['latitude'] ?? '');

            if (empty($id) || empty($id_freguesia) || empty($id_estado) || empty($longitude) || empty($latitude)) {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'message' => 'Todos os campos são obrigatórios.'
                ];
                header("Location: /admin/PortalADMPostes");
                exit;
            }
            $posteDAO = new PosteDAO();
            $posteDAO->createPoste($id, $id_freguesia, $id_estado, $longitude, $latitude);

            $_SESSION['toast'] = [
                'type' => 'success',
                'message' => "Poste \"{$id}\" criado com sucesso!"
            ];
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollback();
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
        $pdo = DatabaseSingle::connect();
        $pdo->beginTransaction();
        try {
            $id = trim($_POST["id"] ?? '');
            $id_freguesia = trim($_POST["id_freguesia"] ?? '');
            $id_estado = trim($_POST["id_estado"] ?? '');
            $longitude = trim($_POST["longitude"] ?? '');
            $latitude = trim($_POST["latitude"] ?? '');

            if (empty($id) || empty($id_freguesia) || empty($id_estado) || empty($longitude) || empty($latitude)) {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'message' => 'Todos os campos são obrigatórios.'
                ];
                header("Location: /admin/PortalADMPostes");
                exit;
            }

            $linhasAlteradas = (new PosteDAO())->posteUpdateDAO($id, $id_freguesia, $id_estado, $longitude, $latitude);

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
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollback();
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
        try {
            $posteDAO = new PosteDAO();
            $postes = $posteDAO->numPosteEstado();

            $_SESSION['toast'] = [
                'type' => 'success',
                'message' => "Número de postes por estado obtido com sucesso!"
            ];
            return $postes;

        } catch (Exception $e) {
            throw new Exception("Erro ao obter o número de postes por estado: " . $e->getMessage());
        }
    }

    public function PosteDelete($postId)
    {
        $pdo = DatabaseSingle::connect();
        $pdo->beginTransaction();

        header('Content-Type: application/json');

        try {
            $linhasAlteradas = (new PosteDAO())->posteDeleteDAO($postId);

            if (!$linhasAlteradas) {
                echo json_encode(['success' => false, 'message' => 'Poste não encontrado.']);
            } else {
                echo json_encode(['success' => true, 'message' => 'Poste eliminado com sucesso!']);
            }
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollback();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    //==========================================================
    // API
    //==========================================================

    public function posteListApi()
    {
        $pdo = DatabaseSingle::connect();
        $pdo->beginTransaction();

        try {
            $posteDAO = new PosteDAO();
            $postes = $posteDAO->getAllPostesAPI();

            $pdo->commit();
            Utils::jsonResponse([
                'success' => true,
                'message' => 'Lista de postes obtida com sucesso.',
                'data' => [
                    "postes" => $postes
                ]
            ]);
        } catch (Exception $e) {
            $pdo->rollback();
            Utils::jsonResponse([
                'success' => false,
                'message' => 'Erro ao obter a lista de postes: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function posteDetailApi($id)
    {
        $pdo = DatabaseSingle::connect();
        $pdo->beginTransaction();

        try {
            $postes = (new PosteDAO())->findByID($id);

            if (!$postes) {
                throw new Exception("Poste não encontrado");
            }

            $pdo->commit();
            Utils::jsonResponse([
                'success' => true,
                'message' => 'Detalhe do poste obtido com sucesso.',
                'data' => [
                    "detalhes_postes" => $postes
                ]
            ]);
        } catch (Exception $e) {
            $pdo->rollback();
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
            $postes = $posteDAO->findByID($id);

            $pdo->commit();
            Utils::jsonResponse([
                'success' => true,
                'message' => 'Detalhe do poste obtido com sucesso.',
                'data' => [
                    "detalhes_postes" => $postes
                ]
            ]);


        } catch (Exception $e) {
            $pdo->rollback();
            Utils::jsonResponse([
                'success' => false,
                'message' => 'Poste não encontrado.',
                'data' => []
            ], 404);
        }
    }

    public function insertObsEmPostesApi($id)
{
    $pdo = DatabaseSingle::connect();
    $pdo->beginTransaction();
    try {
        $id = trim($id ?? '');

        
        $input = json_decode(file_get_contents('php://input'), true);
        $texto = trim($input['texto'] ?? '');

        if (empty($id) || empty($texto)) {
            // ... resto igual
                Utils::jsonResponse([
                    'success' => false,
                    'message' => 'ID e texto são obrigatórios.',
                    'data' => []
                ], 400);
                return;
            }

            $posteDAO = new PosteDAO();

            // Verificar se o poste existe ANTES de inserir
            $poste = $posteDAO->findByIdPoste($id);
            if (!$poste) {
                throw new Exception("Poste com id '$id' não encontrado.");
            }

            $posteDAO->insertObs($id, $texto);

            $pdo->commit();
            Utils::jsonResponse([
                'success' => true,
                'message' => 'Observação inserida com sucesso.',
                'data' => []
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

    public function getObsPosteApi($id)
    {
        try {
            $id = trim($id ?? '');

            if (empty($id)) {
                Utils::jsonResponse([
                    'success' => false,
                    'message' => 'ID é obrigatório.',
                    'data' => []
                ], 400);
                return;
            }

            $posteDAO = new PosteDAO();

            // Verificar se o poste existe
            $poste = $posteDAO->findByIdPoste($id);
            if (!$poste) {
                throw new Exception("Poste com id '$id' não encontrado.");
            }

            $observacoesPostes = $posteDAO->getAllObservacoesByPoste($id);

            Utils::jsonResponse([
                'success' => true,
                'message' => 'Observações obtidas com sucesso.',
                'data' => [
                    "observacoesPostes" => $observacoesPostes
                ]
            ]);
        } catch (Exception $e) {
            Utils::jsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}