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
                'nome' => (String) ($data['nome'] ?? ''),
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

            // Faz o update dos dados do user
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

            $dataResponse = [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ];

            Utils::jsonResponse($dataResponse, 500);
        }
    }











    // public function getAllDataToHome($userId)
//     {
//         try {
//             $users = (new UserDAO())->arrayUsersDAO();
//             $emailVerifications = (new EmailVerificationDAO())->getEmailVerificationsByUserId($userId);
//             // Contar os users
//             $countUsers = (new UserDAO()->countUsersDAO());

    //             $dataResponse = [
//                 'success' => true,
//                 'message' => "Operação realizada com sucesso.",
//                 'data' => [
//                     'users' => $users,
//                     'emails_verifification' => $emailVerifications,
//                     'num_utilizadore' => 10,
//                     'num_emails' => 10
//                 ]
//             ];

    //             Utils::jsonResponse($dataResponse, 200);

    //         } catch (Exception $e) {
//             $dataResponse = [
//                 'success' => false,
//                 'message' => $e->getMessage(),
//                 'data' => []
//             ];

    //             Utils::jsonResponse($dataResponse, 401);

    //             exit;
//         }
//     }
}