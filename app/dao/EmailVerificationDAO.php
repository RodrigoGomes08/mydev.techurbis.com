<?php

require_once __DIR__ . "/../config/DatabaseSingle.php";

class EmailVerificationDAO
{
  private $conn;

  public function __construct()
  {
    $this->conn = DatabaseSingle::connect();
  }

  /** Cria token (5 min) e retorna token "cru" para por no link do email */
  public function createForUser(int $userId, int $ttlSeconds = 300): string
  {
    $token = bin2hex(random_bytes(32));     // token que vai no link
    $tokenHash = hash('sha256', $token);    // o que guardas na BD (mais seguro)

    $sql = "
      INSERT INTO email_verifications (id_user, token_hash, expires_at, used_at, created_at)
      VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ? SECOND), NULL, NOW())
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$userId, $tokenHash, $ttlSeconds]);

    return $token;
  }

  /** Se token válido, devolve user_id; se inválido/expirado/usado devolve null */
 public function validateToken(string $token): ?int
{
    $tokenHash = hash('sha256', $token);

    $sql = "
        SELECT id_user
        FROM email_verifications
        WHERE token_hash = ?
          AND used_at IS NULL
          AND expires_at > NOW()
        ORDER BY id DESC
        LIMIT 1
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$tokenHash]);

    $userId = $stmt->fetchColumn();
    return $userId ? (int)$userId : null;
}

  public function markUsed(string $token): void
  {
    $tokenHash = hash('sha256', $token);

    $stmt = $this->conn->prepare("UPDATE email_verifications SET used_at = NOW() WHERE token_hash = ?");
    $stmt->execute([$tokenHash]);
  }
}
