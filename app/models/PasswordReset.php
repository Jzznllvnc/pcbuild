<?php

class PasswordReset
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Creates a new password reset token record.
     * @param int $userId The ID of the user.
     * @param string $token The unique reset token.
     * @param string $expiresAt The expiration timestamp for the token (YYYY-MM-DD HH:MM:SS).
     * @return bool True on success, false on failure.
     */
    public function createToken($userId, $token, $expiresAt)
    {
        try {
            $this->pdo->prepare("DELETE FROM password_resets WHERE user_id = :user_id")->execute([':user_id' => $userId]);

            $stmt = $this->pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
            return $stmt->execute([
                ':user_id' => $userId,
                ':token' => $token,
                ':expires_at' => $expiresAt
            ]);
        } catch (PDOException $e) {
            error_log("PasswordReset Model Error: createToken failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Finds a password reset token and checks if it's valid (not expired).
     * @param string $token The token to search for.
     * @return array|false The token data (user_id, expires_at), or false if not found or expired.
     */
    public function findByToken($token)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT user_id, expires_at FROM password_resets WHERE token = :token AND expires_at > NOW() LIMIT 1");
            $stmt->execute([':token' => $token]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("PasswordReset Model Error: findByToken failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletes a password reset token.
     * @param string $token The token to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteToken($token)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM password_resets WHERE token = :token");
            return $stmt->execute([':token' => $token]);
        } catch (PDOException $e) {
            error_log("PasswordReset Model Error: deleteToken failed: " . $e->getMessage());
            return false;
        }
    }
}