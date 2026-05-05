<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ .'/../models/User.php';

class UserDAO
{

    private $conn;
    public function __construct()
    {
        $this->conn = (new DataBase())->connect();
    }

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users INNER JOIN roles ON users.id_roles = roles.id WHERE users.email = :email AND roles.id = 1;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        var_dump($row);

        if ($row) {
            $user = new User(
                $row['id'],
                $row['id_roles'],
                $row['nome'],
                $row['data_nascimento'],
                $row['telefone'],
                $row['morada'],
                $row['email'],
                $row['password'],
                $row['ativo'],
                $row['tem_mobilidade_reduzida'],
                $row['created_at'],
                $row['updated_at'],
                $row['deleted_at']
            );

            return $row;
        } else {
            return null;
        }
    }
}