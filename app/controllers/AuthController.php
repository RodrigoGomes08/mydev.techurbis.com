<?php
require_once __DIR__ . "/../config/DatabaseSingle.php";
require_once __DIR__ . "/../utils/Utils.php";
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/EmailVerificationDao.php';
require_once __DIR__ . "/../config/jwtConfig.php";
require_once __DIR__ . '/../services/Mailer.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;


class AuthController
{

    private function view($name, $data = [])
    {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../../public/views/' . $name . '.php';
    }

    public function loginWeb()
    {
        try {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($email) || empty($password)) {
                throw new Exception('Email e password são obrigatórios');
            }

            $user = (new UserDAO())->findByEmail($email);

            if (!$user || !password_verify($password, $user['password'])) {
                throw new Exception("Email ou password inválidos");
            }

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
            ];

            $_SESSION['toast'] = [
                'type' => 'success',
                'message' => 'Login efetuado com sucesso'
            ];

            header("Location: /admin/");
            exit;

        } catch (Exception $e) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'message' => $e->getMessage()
            ];
            header("Location: /login");
            exit;
        }
    }

    public function loginApi()
    {
        try {
            $email = $_POST["email"] ?? null;
            $password = $_POST["password"] ?? null;

            if (empty($email) || empty($password)) {
                throw new Exception("Email e password são obrigatórios");
            }

            $user = (new UserDAO())->findByEmail($email);

            if (!$user || !password_verify($password, $user['password'])) {
                throw new Exception("Login inválido");
            }

            $role = (new RoleDAO())->findRoleById($user['id_role']);

            $payload = [
                "iat" => time(),
                "exp" => time() + 3600,
                "data" => [
                    "id" => $user['id'],
                    "role" => $role['id'],
                ]
            ];

            $jwt = JWT::encode($payload, JwtConfig::$secret, 'HS256');

            $responseData = [
                'success' => true,
                'message' => 'Login realizado com sucesso',
                'data' => [
                    'user' => $user,
                    'role' => $role,
                    'jwt' => $jwt
                ],
            ];

            Utils::jsonResponse($responseData, 200);

        } catch (Exception $e) {
            Utils::jsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ], 401);
        }
    }

    public function signupWeb()
    {
        try {
            $nome = trim($_POST["nome"] ?? '');
            $email = trim($_POST["email"] ?? '');
            $password = trim($_POST["password"] ?? '');

            if ($nome === '' || $email === '' || $password === '') {
                throw new Exception("Todos os campos são obrigatórios");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email inválido");
            }

            $userDAO = new UserDAO();

            if ($userDAO->findByEmail($email)) {
                throw new Exception("Email já existe");
            }

            $userId = $userDAO->createPending($nome, $email);

            $verDAO = new EmailVerificationDAO();
            $token = $verDAO->createForUser($userId, 300);

            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $baseUrl = $scheme . '://' . $host;
            $link = $baseUrl . "/verify-email?token=" . urlencode($token);

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

            $_SESSION['flash_success'] = "Conta criada. Enviámos um email para verificares (link expira em 5 min).";
            header("Location: /login");
            exit;

        } catch (Exception $e) {
            $_SESSION['flash_error'] = $e->getMessage();
            header("Location: /signup");
            exit;
        }
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
            $tem_mobilidade_reduzida = trim($_POST['tem_mobilidade_reduzida'] ?? 1);

            if ($nome === '' || $dataNascimento === '' || $telefone === '' || $email === '' || $password === '' || $morada === '') {
                throw new Exception("Todos os dados são obrigatórios.");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email inválido.");
            }

            $userDao = new UserDAO();

            if ($userDao->findByEmail($email)) {
                throw new Exception("Já existe conta com este email.");
            }

            $userId = $userDao->createPending($nome, $dataNascimento, $telefone, $email, $password, $morada, 0, $tem_mobilidade_reduzida);

            $verDao = new EmailVerificationDAO();
            $token = $verDao->createForUser($userId, 300);

            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $baseUrl = $scheme . '://' . $host;
            $link = $baseUrl . "/verify-email?token=" . urlencode($token);

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

            $pdo->commit();

            Utils::jsonResponse([
                'success' => true,
                'message' => 'Signup realizado com sucesso',
                'data' => [],
            ], 200);

        } catch (Exception $e) {
            $pdo->rollback();

            Utils::jsonResponse([
                'success' => false,
                'message' => 'Erro ao efetuar a operação: ' . $e->getMessage(),
                'data' => [],
            ], 400);
        }
    }

    public function verifyEmailForm()
    {
        // DEBUG
        file_put_contents(
            'C:/laragon/www/mydev.techurbis.com/debug.log',
            date('H:i:s') . " GET token: " . ($_GET['token'] ?? 'VAZIO') . "\n",
            FILE_APPEND
        );

        try {
            $token = $_GET['token'] ?? '';

            if (empty($token)) {
                throw new Exception("Token em falta");
            }

            $this->view('verify-email', [
                'token' => $token,
                'userId' => 1
            ]);

        } catch (Exception $e) {
            file_put_contents(
                'C:/laragon/www/mydev.techurbis.com/debug.log',
                date('H:i:s') . " ERRO verifyEmailForm: " . $e->getMessage() . "\n",
                FILE_APPEND
            );
            header("Location: /bad-request");
            exit;
        }
    }

    public function verifyEmailSubmit()
    {
        // DEBUG TEMPORÁRIO
        file_put_contents(
            'C:/laragon/www/mydev.techurbis.com/debug.log',
            date('H:i:s') . " POST: " . print_r($_POST, true) . "\n",
            FILE_APPEND
        );

        try {
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';

            file_put_contents(
                'C:/laragon/www/mydev.techurbis.com/debug.log',
                date('H:i:s') . " token=$token | pass=$password\n",
                FILE_APPEND
            );

            if (empty($token) || empty($password)) {
                throw new Exception("Token e password são obrigatórios");
            }

            $verDao = new EmailVerificationDAO();
            $userId = $verDao->validateToken($token);

            file_put_contents(
                'C:/laragon/www/mydev.techurbis.com/debug.log',
                date('H:i:s') . " userId=" . var_export($userId, true) . "\n",
                FILE_APPEND
            );

            if (!$userId) {
                throw new Exception("Token inválido ou expirado");
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $userDAO = new UserDAO();
            $userDAO->setPasswordAndVerify($userId, $hash);
            $verDao->markUsed($token);

            $_SESSION['flash_success'] = "Password definida com sucesso. Já podes fazer login.";
            header("Location: /login");
            exit;

        } catch (Exception $e) {
            file_put_contents(
                'C:/laragon/www/mydev.techurbis.com/debug.log',
                date('H:i:s') . " ERRO: " . $e->getMessage() . "\n",
                FILE_APPEND
            );

            $_SESSION['flash_error'] = $e->getMessage();
            header("Location: /bad-request");
            exit;
        }
    }
}