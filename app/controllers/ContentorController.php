<?php

require_once __DIR__ . '/../dao/ContentorDAO.php';

class ContentorController
{
    private function view($name, $data = [])
    {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../../public/admin/views/' . $name . '.php';
    }

    public function showPortalADMContentores()
    {
        try {
            if (empty($_SESSION['token'])) {
                header("Location: /login");
                exit;
            }

            $contentorDAO = new ContentorDAO();
            $contentores = $contentorDAO->getAllContentores();
            $numContentorPorEstado = $contentorDAO->numContentorEstado();

            $this->view('portalADMContentores', [
                'contentores' => $contentores,
                'numContentorPorEstado' => $numContentorPorEstado,
            ]);
        } catch (Exception $e) {
            $_SESSION['toast'] = ['type' => 'error', 'message' => $e->getMessage()];
            header("Location: /admin/PortalADMContentores");
            exit;
        }

    }

    public function createContentor()
    {
        try {
            if (empty($_SESSION['token'])) {
                header("Location: /login");
                exit;
            }

            $id = trim($_POST['id'] ?? '');
            $id_cidade = trim($_POST['id_cidade'] ?? '');
            $id_estado = trim($_POST['id_estado'] ?? '');
            $capacidade_max = trim($_POST['capacidade_max'] ?? '');
            $longitude = trim($_POST['longitude'] ?? '');
            $latitude = trim($_POST['latitude'] ?? '');
            $tipo = trim($_POST['tipo'] ?? '');
            $identificacao = trim($_POST['identificacao'] ?? '');
            $observacao = trim($_POST['observacao'] ?? '');

            if (empty($id) || empty($id_cidade) || empty($id_estado) || empty($capacidade_max) || empty($longitude) || empty($latitude) || empty($tipo) || empty($identificacao) || empty($observacao)) {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'message' => 'Todos os campos são obrigatórios.'
                ];
                header("Location: /admin/PortalADMContentores");
                exit;
            }


            $contentorDAO = new ContentorDAO();
            $contentorDAO->createContentor($id, $id_cidade, $id_estado, $capacidade_max, $longitude, $latitude, $tipo, $identificacao, $observacao);

            $_SESSION['toast'] = [
                'type' => 'success',
                'message' => "Contentor \"{$id}\" criado com sucesso!"
            ];
        } catch (Exception $e) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => $e->getMessage()
            ];
        }

        header("Location: /admin/PortalADMContentores");
        exit;
    }

    public function ContentorUpdate($id)
    {
        try {
            $id = trim($_POST["id"] ?? '');
            $id_cidade = trim($_POST["id_cidade"] ?? '');
            $id_estado = trim($_POST["id_estado"] ?? '');
            $capacidade_max = trim($_POST["capacidade_max"] ?? '');
            $longitude = trim($_POST["longitude"] ?? '');
            $latitude = trim($_POST["latitude"] ?? '');
            $tipo = trim($_POST["tipo"] ?? '');
            $identificacao = trim($_POST["identificacao"] ?? '');
            $observacao = trim($_POST["observacao"] ?? '');

            if (empty($id) || empty($id_cidade) || empty($id_estado) || empty($capacidade_max) || empty($longitude) || empty($latitude) || empty($tipo) || empty($identificacao) || empty($observacao)) {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'message' => 'Todos os campos são obrigatórios.'
                ];
                header("Location: /admin/PortalADMContentores");
                exit;
            }


            $linhasAlteradas = (new ContentorDAO())->contentorUpdateDAO($id, $id_cidade, $id_estado, $capacidade_max, $longitude, $latitude, $tipo, $identificacao, $observacao);

            if (!$linhasAlteradas) {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'message' => 'Nenhuma alteração foi feita (dados iguais aos existentes).'
                ];
            } else {
                $_SESSION['toast'] = [
                    'type' => 'success',
                    'message' => "Contentor \"{$id}\" atualizado com sucesso!"
                ];
            }
        } catch (Exception $e) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => $e->getMessage()
            ];
        }

        header("Location: /admin/PortalADMContentores");
        exit;
    }

    public function ContentorDelete($contentorId)
    {
        header('Content-Type: application/json');
        try {
            $linhasAlteradas = (new ContentorDAO())->contentorDeleteDAO($contentorId);

            if (!$linhasAlteradas) {
                echo json_encode(['success' => false, 'message' => 'Contentor não encontrado.']);
            } else {
                echo json_encode(['success' => true, 'message' => 'Contentor eliminado com sucesso!']);
            }

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function numContentorEstado()
    {
        try {
            $contentorDAO = new ContentorDAO();
            $contentores = $contentorDAO->numContentorEstado();

            return $contentores;
        } catch (Exception $e) {
            throw new Exception("Erro ao obter número de contentores por estado: " . $e->getMessage());
        }
    }

    //==================================================
    // API
    //==================================================

    public function contentorListApi(): void
    {
        $pdo = DatabaseSingle::connect();
        $pdo->beginTransaction();

        try {
            // if (empty($_SESSION['token'])) {
            //     header("Location: /login");
            //     exit;
            // }

            $contentorDAO = new ContentorDAO();
            $contentores = $contentorDAO->getAllContentores();

            $pdo->commit();
            Utils::jsonResponse([
                'success' => true,
                'message' => 'Lista de contentores obtida com sucesso.',
                'data' => [
                    "contentores" => $contentores
                ]
            ]);
        } catch (Exception $e) {
            $pdo->rollBack();
            Utils::jsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function contentorDetailApi($id)
    {
        $pdo = DatabaseSingle::connect();
        $pdo->beginTransaction();

        try {
            $contentores = (new ContentorDAO())->findByID($id);

            if (!$contentores) {
                throw new Exception("Contentor não encontrado");
            }

            $pdo->commit();
            Utils::jsonResponse([
                'success' => true,
                'message' => 'Detalhe do contentor obtido com sucesso.',
                'data' => [
                    "detalhes_contentor" => $contentores
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

    public function insertObsEmContentorApi($id)
    {
        $pdo = DatabaseSingle::connect();
        $pdo->beginTransaction();
        try {
            $id = trim($id ?? '');
            $texto = trim($_POST["texto"] ?? '');

            if (empty($id) || empty($texto)) {
                Utils::jsonResponse([
                    'success' => false,
                    'message' => 'ID e texto são obrigatórios.',
                    'data' => []
                ], 400);
                return;
            }

            $contentorDAO = new ContentorDAO();

            // Verificar se o poste existe ANTES de inserir
            $contentor = $contentorDAO->findByIdContentor($id);
            if (!$contentor) {
                throw new Exception("Contentor com id '$id' não encontrado.");
            }

            $contentorDAO->insertObs($id, $texto);

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

    public function getObsContentorApi($id)
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

            $contentorDAO = new ContentorDAO();

            // Verificar se o contentor existe
            $contentor = $contentorDAO->findByIdContentor($id);
            if (!$contentor) {
                throw new Exception("Contentor com id '$id' não encontrado.");
            }

            $observacoesContentores = $contentorDAO->getAllObservacoesByContentor($id);

            Utils::jsonResponse([
                'success' => true,
                'message' => 'Observações obtidas com sucesso.',
                'data' => [
                    "observacoesContentores" => $observacoesContentores
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