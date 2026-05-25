<?php
require_once __DIR__ . "/../config/DatabaseSingle.php";
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/EmailVerificationDao.php';
require_once __DIR__ . '/../services/MyMailerService.php';


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

    public function signupWeb()
    {

        /*
         * @TODO validar se existe utilizador logado
         */

        $nome = trim($_POST["nome"] ?? '');
        $email = trim($_POST["email"] ?? '');
        $password = trim($_POST["password"] ?? '');

        if ($nome === '' || $email === '') {
            die("Todos os campos são obrigatórios");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email inválido");
        }

        $user = (new UserDAO())->findByEmail($email);

        if ($user) {
            throw new Exception("Email já existe");
        }

        //Criar um utilizador no estad o pendente
        $userDAO = new UserDAO();

        $userId = $userDAO->createPending($nome, $email);

        $verDAO = new emailVerificationDAO();

        $token = $verDAO->createForUser($userId, 300);

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
            <h2>Olá, " . htmlspecialchars($nome) . "!</h2>
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

    public function singupApi()
    {
        $pdo = DatabaseSingle::connect();

        $pdo->beginTransaction();

        try {
            $nome = trim($_POST['nome'] ?? '');
            $dataNascimento = trim($_POST['data_nascimento'] ?? '');
            $telefone = trim($_POST['telefone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $morada = trim($_POST['morada'] ?? '');
            $tem_mobilidade_reduzida = trim($_POST['tem_mobilidade_reduzida'] ?? '');

            if ($nome === '' || $dataNascimento === '' || $telefone === '' || $email === '' || $password === '' || $morada === '' || $tem_mobilidade_reduzida === '') {
                throw new Exception("Todos os dados são obrigatórios.");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email inválido.");
            }

            $userDao = new UserDAO();

            if ($userDao->findByEmail($email)) {
                throw new Exception("Já existe conta com este email.");
            }

            //-------------:)

            $userId = $userDao->createPending($nome, $dataNascimento, $telefone, $email, $password, $morada, 0,  $tem_mobilidade_reduzida);
                
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
            <h2>Olá, " . htmlspecialchars($nome) . "!</h2>
            <p>Para ativares a tua conta e definires a tua password, clica no link abaixo (válido por <b>5 minutos</b>):</p>
            <p><a href='{$link}'>{$link}</a></p>
            <p>Se o link expirar, faz signup novamente (ou pede reenvio do link).</p>
          </div>
        ";

            (new MyMailerService())->send($email, $subject, $html);

            $responseData = [
                'success' => true,
                'message' => 'Signup realizado com sucesso',
                'data' => [],
            ];

            $pdo->commit();

            Utils::jsonResponse($responseData, 200);

        } catch (Exception $e) {
            $pdo->rollback();

            $responseData = [
                'success' => false,
                'message' => 'Erro ao efetuar a operação.',
                'data' => [],
            ];

            Utils::jsonResponse($responseData, 400);
        }
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