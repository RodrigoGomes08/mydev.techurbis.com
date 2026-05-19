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
        if (empty($_SESSION['token'])) {
            header("Location: /login");
            exit;
        }

        $contentorDAO = new ContentorDAO();
        $contentores = $contentorDAO->getAllContentores();

        $this->view('portalADMContentores', [
            'contentores' => $contentores
        ]);
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
        $capacidade_max = trim($_POST['capacidade_max'] ??'');
        $longitude = trim($_POST['longitude'] ?? '');
        $latitude = trim($_POST['latitude'] ?? '');
        $tipo = trim($_POST['tipo'] ?? '');
        $identificacao = trim($_POST['identificacao'] ??'');
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

    public function ContentorDelete($contentorId) {
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
}