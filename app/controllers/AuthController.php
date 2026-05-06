<?php

require_once __DIR__ . '/../dao/UserDao.php';
//require_once __DIR__ . '/../dao/EmailVerificationDao.php';


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

        if (empty($email) || empty($password)) {
            die('Email e pass são obrigatórios');
        }

        $user = (new UserDAO())->findByEmail($email);
        if (!$user) {
            die("Email ou password inválidos");
        }

        //Verificar password corretamente
        //password_verify() compara a password fornecida com o hash armazenado na base de dados
        // password_verify() esta função já vem incluída no PHP
        if (!password_verify($password, $user['password'])) {
            die("Email ou password inválidos");
        }

        // Serve para criar a session token
        // que valida se o user está ou não logado
        $_SESSION['token'] = [
            'id' => $user['id'],
            'id_role' => $user['id_role'],
            'nome' => $user['nome'],
            'data_nascimento' => $user['data_nascimento'],
            'telefone' => $user['telefone'],
            'morada' => $user['morada'],
            'email' => $user['email'],
            'password' => $user['password'],
            'ativo' => $user['ativo'],
            'tem_mobilidade_reduzida' => $user['tem_mobilidade_reduzida'],
            // 'created_at' => $user->getCreatedAt(),
            // 'updated_at' => $user->getUpdatedAt(),
            // 'deleted_at' => $user->getDeletedAt()
        ];

        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Login Efetuado com sucesso'
        ];

        header("Location: /admin/");

    }

    public function verifyEmailForm()
    {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            header("Location: /bad-request");
            exit();
        }

        // TOken válido
        $this->view('verify-email', [
            'token' => $token,
            'userId' => 1
        ]);
    }

    public function verifyEmailSubmit()
    {
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($token) || empty($password)) {
            throw new Exception("Token e password são obrigatórios");
        }

        $verDao = new EmailVerificationDAO();

        $userId = $verDao->validateToken($token);

        if (!$userId) {
            throw new Exception("Token inválido ou expirado");
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $userDAO = new UserDAO();
        // Atualizar a password do utilizador e marcar como verificado
        $userDAO->setPasswordAndVerify($userId, $hash);
        // Desativar o token para não ser usado novamente

        $verDao->markUsed($token);


        $_SESSION['flash_success'] = "Email verificado e password definida. Já podes fazer login.";
        header("Location: /login");
        exit;
    }
}