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
        $sql = "SELECT  * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        var_dump($row);

        if ($row) {
            $user = new User(
                $row['id'],
                $row['username']
            );

            return $row;
        } else {
            return null;
        }
    }
}