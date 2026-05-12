<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Role.php';

class RoleDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new DataBase())->connect();
    }

    public function getAllRoles(): array
    {
        $sql = "SELECT id, nome, cor FROM roles ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $roles = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = new Role(
                (int) $row['id'],
                $row['nome'],
                $row['cor']
            );
        }

        return $roles;
    }

    public function createRole(string $nome, string $cor): int
    {
        $sqlCheck = "SELECT id FROM roles WHERE nome = :nome";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':nome', $nome);
        $stmtCheck->execute();

        if ($stmtCheck->fetch()) {
            throw new Exception("Já existe uma role com esse nome.");
        }

        $sql = "INSERT INTO roles (nome, cor) VALUES (:nome, :cor)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'nome' => $nome,
            'cor' => $cor,
        ]);

        return (int) $this->conn->lastInsertId();
    }
}