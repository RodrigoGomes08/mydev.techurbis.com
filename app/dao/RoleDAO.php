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
        $sql = "SELECT id, nome_role, cor FROM roles ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $roles = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = new Role(
                (int) $row['id'],
                $row['nome_role'],
                $row['cor']
            );
        }
    
        return $roles;
    }

    public function createRole(string $nome, string $cor): int
    {
        $sqlCheck = "SELECT id FROM roles WHERE nome_role = :nome_role";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':nome_role', $nome);
        $stmtCheck->execute();

        if ($stmtCheck->fetch()) {
            throw new Exception("Já existe uma role com esse nome.");
        }

        $sql = "INSERT INTO roles (nome_role, cor) VALUES (:nome_role, :cor)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'nome_role' => $nome,
            'cor' => $cor,
        ]);

        return (int) $this->conn->lastInsertId();
    }
}