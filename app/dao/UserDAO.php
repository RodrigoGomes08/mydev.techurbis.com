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

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users INNER JOIN roles ON users.id_role = roles.id WHERE users.email = :email AND roles.id = 1;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        //var_dump($row);

        if ($row) {
            $user = new User(
                $row['id'],
                $row['id_role'],
                $row['nome'],
                $row['data_nascimento'],
                $row['telefone'],
                $row['morada'],
                $row['email'],
                $row['password'],
                $row['ativo'],
                $row['tem_mobilidade_reduzida'],
                // $row['created_at'],
                // $row['updated_at'],
                // $row['deleted_at']
            );

            return $row;
        } else {
            return null;
        }
    }

    public function createPending($username, $email)
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
      VALUES (
        NULL,
        2,
        :username,
        '2000-01-01',
        '000000000',
        'N/A',
        :email,
        '',
        0,
        0
      )
    ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute(['username' => $username, 'email' => $email]);

        return (int) $this->conn->lastInsertId();
    }
}