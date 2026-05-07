<?php

require_once __DIR__ . '/../dao/UserDao.php';

class UserController
{
    private function view($name, $data = [])
    {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../../public/views/' . $name . '.php';
    }

    public function createUtilizador()
    {
        if (empty($_SESSION['token'])) {
            header("Location: /login");
            exit;
        }
        $nome     = trim($_POST['nome']     ?? '');
        $email    = trim($_POST['email']    ?? '');
        $id_role  = trim($_POST['id_role']  ?? '');
        $morada   = trim($_POST['morada']   ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($nome) || empty($email) || empty($id_role)) {
            $_SESSION['toast'] = [
                'type'    => 'error',
                'message' => 'Nome, email e cargo são obrigatórios.'
            ];
            header("Location: /admin/PortalADMUtilizadores");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['toast'] = [
                'type'    => 'error',
                'message' => 'Email inválido.'
            ];
            header("Location: /admin/PortalADMUtilizadores");
            exit;
        }

        try {
            $userDAO = new UserDAO();
            $userDAO->createUser($nome, $email, $id_role, $morada, $password);

            $_SESSION['toast'] = [
                'type'    => 'success',
                'message' => "Utilizador \"{$nome}\" criado com sucesso!"
            ];
        } catch (Exception $e) {
            $_SESSION['toast'] = [
                'type'    => 'error',
                'message' => $e->getMessage()
            ];
        }

        header("Location: /admin/PortalADMUtilizadores");
        exit;
    }
}