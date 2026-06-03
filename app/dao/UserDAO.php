<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';

class UserDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new DataBase())->connect();
    }

    public function getAllUsers()
    {
        $sql = "SELECT users.id, users.id_role, users.nome, users.data_nascimento, users.telefone, users.morada, users.email, users.password, users.ativo, users.tem_mobilidade_reduzida FROM users INNER JOIN roles ON users.id_role = roles.id ORDER BY users.id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $users = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new User(
                $row['id'],
                $row['id_role'],
                $row['nome'],
                $row['data_nascimento'],
                $row['telefone'],
                $row['morada'],
                $row['email'],
                $row['password'],
                $row['ativo'],
                $row['tem_mobilidade_reduzida']
            );
        }

        return $users;
    }

    public function findByID($id)
    {
        $sql = "SELECT users.id, users.id_role, users.nome, users.data_nascimento, users.telefone, users.morada, users.email, users.password, users.ativo, users.tem_mobilidade_reduzida, roles.id AS role_id, roles.nome_role, roles.cor FROM users INNER JOIN roles ON users.id_role = roles.id WHERE users.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function findByEmail($email)
    {
        $sql = "SELECT users.id, users.id_role, users.nome, users.data_nascimento, users.telefone, users.morada, users.email, users.password, users.ativo, users.tem_mobilidade_reduzida, roles.id AS role_id, roles.nome_role, roles.cor FROM users INNER JOIN roles ON users.id_role = roles.id WHERE users.email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $row;
        } else {
            return null;
        }
    }

    public function createUser($nome, $email, $id_role, $morada, $password)
    {
        $sqlCheck = "SELECT id FROM users WHERE email = :email";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':email', $email);
        $stmtCheck->execute();

        if ($stmtCheck->fetch()) {
            throw new Exception("Email já existe");
        }

        $passwordHash = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : '';

        $sql = "
            INSERT INTO users
            (
                id,
                id_role,
                nome,
                data_nascimento,
                telefone,
                morada,
                email,
                password,
                ativo,
                tem_mobilidade_reduzida
            ) VALUES (
                NULL,
                :id_role,
                :nome,
                '2000-01-01',
                '000000000',
                :morada,
                :email,
                :password,
                1,
                0
            )
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'id_role' => (int) $id_role,
            'nome' => $nome,
            'morada' => $morada,
            'email' => $email,
            'password' => $passwordHash,
        ]);

        return (int) $this->conn->lastInsertId();
    }

    public function createPending($nome, $dataNascimento, $telefone, $email, $password, $morada, $ativo, $tem_mobilidade_reduzida)
    {
        $sql = "
            INSERT INTO users
            (
                id,
                id_role,
                nome,
                data_nascimento,
                telefone,
                morada,
                email,
                password,
                ativo,
                tem_mobilidade_reduzida
            ) VALUES (
                NULL,
                2,
                :nome,
                :data_nascimento,
                :telefone,
                :morada,
                :email,
                :password,
                :ativo,
                :tem_mobilidade_reduzida
            )
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'nome' => $nome,
            'data_nascimento' => $dataNascimento,
            'telefone' => $telefone,
            'morada' => $morada,
            'email' => $email,
            'password' => $password,
            'ativo' => $ativo,
            'tem_mobilidade_reduzida' => $tem_mobilidade_reduzida
        ]);

        return (int) $this->conn->lastInsertId();
    }

    public function setPasswordAndVerify($userId, $passwordHash)
    {
        $sql = "
            UPDATE users
            SET password   = ?,
                is_verified = 1,
                verified_at = NOW(),
                updated_at  = NOW()
            WHERE id = ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$passwordHash, $userId]);
    }

    public function userUpdateDAO($dados){
        try{
            $sql = "UPDATE users SET nome = ?, email = ?, id_role = ?, data_nascimento = ?, telefone = ?, morada = ?, ativo = ?, tem_mobilidade_reduzida = ? WHERE id = ?";

            $stmt = $this->conn->prepare($sql);
            $verificar = $stmt->execute([$dados['nome'], $dados['email'], $dados['id_role'], $dados['data_nascimento'], $dados['telefone'], $dados['morada'], $dados['ativo'], $dados['tem_mobilidade_reduzida'], $dados['id']]);

            if (!$verificar) {
                throw new Exception("Erro ao atualizar User");
            }

            return true;

        }catch(Exception $e) {
            throw $e;
        }
    }

    public function userDeleteDAO($userId)
    {
        $sql = "DELETE FROM users WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);

        $resul = $stmt->rowCount();

        return $resul;
    }

    



    // public function countUsersDAO()
    // {
    //     $sql = "
    // SELECT COUNT(*) FROM users;
    // ";

    //     $stmt = $this->conn->prepare($sql);

    //     $stmt->execute();

    //     $numUsers = $stmt->fetch(PDO::FETCH_ASSOC);
    //     return $numUsers;
    // }
}