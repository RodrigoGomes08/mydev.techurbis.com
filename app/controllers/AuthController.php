<?php

require_once __DIR__ . '/../dao/UserDao.php';

class AuthController
{

    private function view($name)
    {
        require __DIR__ . '/../../public/views/' . $name . '.php';
    }

    public function loginWeb()
    {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $passwordEncript = password_hash($password, PASSWORD_DEFAULT);

        var_dump($passwordEncript);

        if (empty($email) || empty($password)) {
            die('Email e pass são obrigatórios');
        }

        $user = (new UserDAO())->findByEmail($email);

        if (!$user) {
            die('Email ou password inválidos');
        }
    }
}