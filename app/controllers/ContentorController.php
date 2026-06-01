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

        try {
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

        try {
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
        $contentorDAO = new ContentorDAO();
        $contentores = $contentorDAO->numContentorEstado();

        return $contentores;
    }

    public function contentorListApi(): void
    {
        // if (empty($_SESSION['token'])) {
        //     header("Location: /login");
        //     exit;
        // }

        $contentorDAO = new ContentorDAO();
        $contentores = $contentorDAO->getAllContentores();

        Utils::jsonResponse([
            'success' => true,
            'message' => 'Lista de contentores obtida com sucesso.',
            'data' => $contentores
        ]);
    }

    public function contentorDetailApi($id)
    {
        try {
            $contentores = (new ContentorDAO())->findByID($id);

            if (!$contentores) {
                throw new Exception("Contentor não encontrado");
            }

            Utils::jsonResponse([
                'success' => true,
                'message' => 'Detalhe do contentor obtido com sucesso.',
                'data' => $contentores
            ]);

        } catch (Exception $e) {

        }
    }

    public function insertObsEmContentorApi($id)
    {
        // $id já vem da URL, não precisas de o ler do POST
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
            $contentoresObs = (new ContentorDAO())->insertObs($id, $texto);

            Utils::jsonResponse([
                'success' => true,
                'message' => 'Observação inserida com sucesso.',
                'data' => $contentoresObs
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