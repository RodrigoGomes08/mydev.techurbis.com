<?php

require_once __DIR__ . '/../dao/RoleDAO.php';

class RoleController
{
    private function view($name, $data = [])
    {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../../public/admin/views/' . $name . '.php';
    }

    public function showPortalADMUtilizadores()
    {
        if (empty($_SESSION['token'])) {
            header("Location: /login");
            exit;
        }

        $roleDAO = new RoleDAO();
        $roles = $roleDAO->getAllRoles();

        $this->view('portalADMUtilizadores', ['roles' => $roles]);
    }

    public function createRole()
    {
        if (empty($_SESSION['token'])) {
            header("Location: /login");
            exit;
        }

        $nome = trim($_POST['nome'] ?? '');
        $cor = trim($_POST['cor'] ?? '#000000');

        if (empty($nome)) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => 'O nome da role é obrigatório.'
            ];
            header("Location: /admin/PortalADMUtilizadores");
            exit;
        }

        try {
            $roleDAO = new RoleDAO();
            $roleDAO->createRole($nome, $cor);

            $_SESSION['toast'] = [
                'type' => 'success',
                'message' => "Role \"{$nome}\" criada com sucesso!"
            ];
        } catch (Exception $e) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => $e->getMessage()
            ];
        }

        header("Location: /admin/PortalADMUtilizadores");
        exit;
    }
}