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

            // DEBUG — apaga depois
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

            $dados = [
                'id' => (int) ($data['id'] ?? 0),
                'id_role' => (int) ($data['id_role'] ?? 0),
                'nome' => (string) ($data['nome'] ?? ''),
                'data_nascimento' => $data['data_nascimento'] ?? null,
                'telefone' => (int) ($data['telefone'] ?? 0),
                'morada' => (string) ($data['morada'] ?? ''),
                'email' => (string) ($data['email'] ?? ''),
                'ativo' => (int) ($data['ativo'] ?? 0),
                'tem_mobilidade_reduzida' => (int) ($data['tem_mobilidade_reduzida'] ?? 0)
            ];

            if ($userId !== $dados['id']) {
                throw new Exception("Não tens permissão para atualizar este perfil");
            }

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
}