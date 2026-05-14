<?php

require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/RoleDAO.php';

class UserController
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

        $userDAO = new UserDAO();
        $users = $userDAO->getAllUsers();

        $roleDAO = new RoleDAO();
        $roles = $roleDAO->getAllRoles();

        $this->view('portalADMUtilizadores', [
            'users' => $users,
            'roles' => $roles
        ]);
    }

    public function createUtilizador()
    {
        if (empty($_SESSION['token'])) {
            header("Location: /login");
            exit;
        }

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $id_role = trim($_POST['id_role'] ?? '');
        $morada = trim($_POST['morada'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($nome) || empty($email) || empty($id_role)) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => 'Nome, email e cargo são obrigatórios.'
            ];
            header("Location: /admin/PortalADMUtilizadores");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => 'Email inválido.'
            ];
            header("Location: /admin/PortalADMUtilizadores");
            exit;
        }

        try {
            $userDAO = new UserDAO();
            $userDAO->createUser($nome, $email, $id_role, $morada, $password);

            $_SESSION['toast'] = [
                'type' => 'success',
                'message' => "Utilizador \"{$nome}\" criado com sucesso!"
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

    public function userUpdate($userId)
    {

        $id = trim($_POST["id"] ?? '');
        $id_role = trim($_POST["id_role"] ?? '');
        $nome = trim($_POST["nome"] ?? '');
        $data_nascimento = trim($_POST["data_nascimento"] ?? '');
        $telefone = trim($_POST["telefone"] ?? '');
        $morada = trim($_POST["morada"] ?? '');
        $email = trim($_POST["email"] ?? '');
        $ativo = trim($_POST["ativo"] ?? '');
        $tem_mobilidade_reduzida = trim($_POST["tem_mobilidade_reduzida"] ?? '');

        if (empty($email) || empty($nome)) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => 'Email e nome são obrigatórios.'
            ];
            header("Location: /admin/PortalADMUtilizadores");
            exit;
        }

        try {
            $linhasAlteradas = (new UserDAO())->userUpdateDAO($id, $id_role, $nome, $data_nascimento, $telefone, $morada, $email, $ativo, $tem_mobilidade_reduzida);

            if (!$linhasAlteradas) {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'message' => 'Nenhuma alteração foi feita (dados iguais aos existentes).'
                ];
            } else {
                $_SESSION['toast'] = [
                    'type' => 'success',
                    'message' => "Utilizador \"{$nome}\" atualizado com sucesso!"
                ];
            }
        } catch (Exception $e) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => $e->getMessage()
            ];
        }

        header("Location: /admin/PortalADMUtilizadores");
        exit;
    }

    public function userDelete($userId) {
        $linhasAlteradas = (new UserDAO())->userDeleteDAO($userId);

        if (!$linhasAlteradas) {
            throw new Exception("Erro ao alterar os dados");
        }
    }
}