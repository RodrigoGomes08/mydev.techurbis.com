<?php

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
        if (empty($_SESSION['token'])) {
            header("Location: /login");
            exit;
        }

        $posteDAO = new PosteDAO();
        $postes = $posteDAO->getAllPostes();

        $this->view('portalADMPostes', [
            'postes' => $postes
        ]);
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
        $observacao = trim($_POST['observacao'] ?? '');

        if (empty($id) || empty($id_cidade) || empty($id_estado) || empty($longitude) || empty($latitude) || empty($observacao)) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => 'Todos os campos são obrigatórios.'
            ];
            header("Location: /admin/PortalADMPostes");
            exit;
        }

        try {
            $posteDAO = new PosteDAO();
            $posteDAO->createPoste($id, $id_cidade, $id_estado, $longitude, $latitude, $observacao);

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

    public function PosteUpdate($id)
    {

        $id = trim($_POST["id"] ?? '');
        $id_cidade = trim($_POST["id_cidade"] ?? '');
        $id_estado = trim($_POST["id_estado"] ?? '');
        $longitude = trim($_POST["longitude"] ?? '');
        $latitude = trim($_POST["latitude"] ?? '');
        $observacao = trim($_POST["observacao"] ?? '');

        if (empty($id) || empty($id_cidade) || empty($id_estado) || empty($longitude) || empty($latitude) || empty($observacao)) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => 'Todos os campos são obrigatórios.'
            ];
            header("Location: /admin/PortalADMPostes");
            exit;
        }

        try {
            $linhasAlteradas = (new PosteDAO())->posteUpdateDAO($id, $id_cidade, $id_estado, $longitude, $latitude, $observacao);

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

    public function PosteDelete($postId) {
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
}