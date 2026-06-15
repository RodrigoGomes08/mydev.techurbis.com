<?php

require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/RoleDAO.php';
require_once __DIR__ . '/../dao/EmailVerificationDao.php';
require_once __DIR__ . '/../services/Mailer.php';

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
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Nome, email e cargo são obrigatórios.'];
            header("Location: /admin/PortalADMUtilizadores");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Email inválido.'];
            header("Location: /admin/PortalADMUtilizadores");
            exit;
        }

        try {
            $userDAO = new UserDAO();
            $userId = $userDAO->createUser($nome, $email, $id_role, $morada, $password);

            error_log(">>> userId obtido: " . var_export($userId, true));
            error_log(">>> password está vazia? " . var_export(empty($password), true));

            if (empty($password)) {

                $verDAO = new EmailVerificationDAO();
                $token = $verDAO->createForUser($userId, 86400);
                error_log(">>> token criado: " . $token);

                $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $link = $scheme . '://' . $host . '/verify-email?token=' . urlencode($token);
                error_log(">>> link gerado: " . $link);

                $subject = "Bem-vindo(a)! Define a tua password";
                $html = "<p>Olá " . htmlspecialchars($nome) . "! <a href='{$link}'>Define a tua password</a></p>";

                error_log(">>> A tentar enviar email para: " . $email);
                (new MyMailerService())->send($email, $subject, $html);
                error_log(">>> Email enviado com sucesso!");

                $_SESSION['toast'] = ['type' => 'success', 'message' => "Email enviado para \"{$email}\" definir a password."];
            } else {
                $_SESSION['toast'] = ['type' => 'success', 'message' => "Utilizador \"{$nome}\" criado com sucesso!"];
            }

        } catch (Exception $e) {
            error_log(">>> ERRO: " . $e->getMessage());
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'ERRO: ' . $e->getMessage()];
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
            $dados = [
                'id' => $id,
                'id_role' => $id_role,
                'nome' => $nome,
                'data_nascimento' => $data_nascimento,
                'telefone' => $telefone,
                'morada' => $morada,
                'email' => $email,
                'ativo' => $ativo,
                'tem_mobilidade_reduzida' => $tem_mobilidade_reduzida,
            ];

            $linhasAlteradas = (new UserDAO())->userUpdateDAO($dados);

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

    public function userDelete($userId)
    {
        header('Content-Type: application/json');
        try {
            $linhasAlteradas = (new UserDAO())->userDeleteDAO($userId);

            if (!$linhasAlteradas) {
                echo json_encode(['success' => false, 'message' => 'Utilizador não encontrado.']);
            } else {
                echo json_encode(['success' => true, 'message' => 'Utilizador eliminado com sucesso!']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function profileUserAPI($id)
    {
        $pdo = DatabaseSingle::connect();
        $pdo->beginTransaction();

        try {
            $user = (new UserDAO())->findByID($id);

            if (!$user) {
                throw new Exception("Utilizador não encontrado");
            }

            $pdo->commit();
            Utils::jsonResponse([
                'success' => true,
                'message' => 'Detalhes do utilizador obtido com sucesso.',
                'data' => [
                    "utilizador" => $user
                ]
            ]);

        } catch (Exception $e) {
            $pdo->rollback();
            Utils::jsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 404);
        }
    }

    public function editProfileUserAPI($userId)
    {
        try {
            $raw = file_get_contents("php://input");
            $data = json_decode($raw, true);

            if (!is_array($data)) {
                throw new Exception("JSON inválido");
            }

            $userAtual = (new UserDAO())->findByID($userId);
            if (!$userAtual) {
                throw new Exception("Utilizador não encontrado");
            }


            $idNoBody = (int) ($data['id'] ?? 0);
            if ($idNoBody !== 0 && $idNoBody !== $userId) {
                throw new Exception("Não tens permissão para atualizar este perfil");
            }

            $dados = [
                'id' => $userId,
                'id_role' => $userAtual['id_role'],   // preserva role
                'nome' => (string) ($data['nome'] ?? ''),
                'data_nascimento' => $data['data_nascimento'] ?? null,
                'telefone' => (string) ($data['telefone'] ?? ''),
                'morada' => (string) ($data['morada'] ?? ''),
                'email' => (string) ($data['email'] ?? ''),
                'ativo' => $userAtual['ativo'],               // preserva ativo
                'tem_mobilidade_reduzida' => (int) ($data['tem_mobilidade_reduzida'] ?? $userAtual['tem_mobilidade_reduzida']),
            ];

            $userAtualizado = (new UserDAO())->userUpdateDAO($dados);

            if (!$userAtualizado) {
                throw new Exception("Erro ao atualizar perfil");
            }

            Utils::jsonResponse([
                "success" => true,
                "message" => "Perfil atualizado com sucesso",
                "data" => []
            ], 201);
            exit;

        } catch (Exception $e) {
            Utils::jsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function enviarEmailEditarPassword($userId)
    {
        try {
            $user = (new UserDAO())->findByID($userId);
            if (!$user) {
                throw new Exception("Utilizador não encontrado");
            }

            $verDAO = new EmailVerificationDAO();
            $token = $verDAO->createForUser($userId, 3600); // token válido por 1 hora

            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $link = $scheme . '://' . $host . '/reset-password?token=' . urlencode($token);

            $subject = "Redefinir a tua password";
            $html = "<p>Olá " . htmlspecialchars($user['nome']) . "! <a href='{$link}'>Redefine a tua password</a></p>";

            (new MyMailerService())->send($user['email'], $subject, $html);

            Utils::jsonResponse([
                "success" => true,
                "message" => "Email enviado para \"{$user['email']}\" redefinir a password.",
                "data" => []
            ], 200);
            exit;

        } catch (Exception $e) {
            Utils::jsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}