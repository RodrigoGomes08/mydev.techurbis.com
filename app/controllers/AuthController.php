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

        var_dump($passwordEncript);

        if (empty($email) || empty($password)) {
            die('Email e pass são obrigatórios');
        }

        $user = (new UserDAO())->findByEmail($email);

        if (!$user) {
            die("Email ou password inválidos");
        }
        // Serve para criar a session token
        // que valida se o user está ou não logado
        $_SESSION['token'] = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'is_admin' => $user->isAdmin(),
            'is_verified' => $user->isVerified(),
            'verified_at' => $user->getVerifiedAt(),
            'created_at' => $user->getCreatedAt(),
            'updated_at' => $user->getUpdatedAt(),
            'deleted_at' => $user->getDeletedAt()
        ];

        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Login Efetuado com sucesso'
        ];

        header("Location: /");

    }

    public function signupWeb()
    {
        /**
         * @TODO validar se existe utilizador logado
         */
        $username = trim($_POST['username']) ?? '';
        $email = trim($_POST['email']) ?? '';
        $password = trim($_POST['password']) ?? '';

        if ($username === '' || $email === '') {
            throw new Exception("Username, email e password são obrigatórios");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido");
        }
        // validar se o email já existe
        $user = (new UserDAO())->findByEmail($email);

        if ($user) {
            throw new Exception("Email já existe na base de dados seu malandro");
        }

        // Criar o utilizador no estado pendente
        $userDao = new UserDAO();

        $userId = $userDao->createPending($username, $email);

        $verDao = new EmailVerificationDAO();

        $token = $verDao->createForUser($userId, 300);

        // 3) baseUrl dinâmico (vhosts)
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $baseUrl = $scheme . '://' . $host;

        // 4) link para clicar no email
        $link = $baseUrl . "/verify-email?token=" . urlencode($token);

        // 5) envia email via Mailer (PHPMailer/Mailtrap)
        $subject = "Verifica o teu email (expira em 5 min)";
        $html = "
        <div style='font-family: Arial, sans-serif;'>
        <h2>Olá, " . htmlspecialchars($username) . "!</h2>
        <p>Para ativares a tua conta e definires a tua password, clica no link abaixo (válido por <b>5 minutos</b>):</p>
        <p><a href='{$link}'>{$link}</a></p>
        <p>Se o link expirar, faz signup novamente (ou pede reenvio do link).</p>
        </div>
    ";

        (new Mailer())->send($email, $subject, $html);

        // 6) redirect com toast
        $_SESSION['flash_success'] = "Conta criada. Enviámos um email para verificares (link expira em 5 min).";
        header("Location: /login");
        exit;
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