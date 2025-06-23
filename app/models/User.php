<?php

class User
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Finds a user by their username or email.
     * @param string $identifier The username or email to search for.
     * @return array|false An associative array of user data, or false if not found.
     */
    public function findByUsernameOrEmail($identifier)
    {
        error_log("User Model: Attempting to find user with identifier: '{$identifier}'");
        try {
            // Include is_admin in the selection
            $stmt = $this->pdo->prepare("SELECT *, is_admin FROM users WHERE username = :username_param OR email = :email_param LIMIT 1");
            $stmt->execute([
                ':username_param' => $identifier,
                ':email_param' => $identifier
            ]);
            $user = $stmt->fetch();
            if ($user) {
                error_log("User Model: Found user: " . $user['username'] . " (ID: " . $user['id'] . ", Admin: " . $user['is_admin'] . ")");
            } else {
                error_log("User Model: No user found for identifier: '{$identifier}'");
            }
            return $user;
        } catch (PDOException $e) {
            error_log("User Model Error: findByUsernameOrEmail failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Creates a new user in the database.
     * @param string $username
     * @param string $email
     * @param string $passwordHash The hashed password.
     * @return int|false The ID of the newly created user, or false on failure.
     */
    public function createUser($username, $email, $passwordHash)
    {
        error_log("User Model: Attempting to create user: username='{$username}', email='{$email}'");
        try {
            // Add is_admin column to insert, default to 0
            $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password, is_admin) VALUES (:username, :email, :password, 0)");
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $passwordHash
            ]);
            $lastId = $this->pdo->lastInsertId();
            if ($lastId) {
                error_log("User Model: User created successfully with ID: {$lastId}");
            } else {
                error_log("User Model: User creation failed, no lastInsertId returned.");
            }
            return $lastId;
        } catch (PDOException $e) {
            error_log("User Model Error: createUser failed: " . $e->getMessage());
            if ($e->getCode() == '23000') {
                error_log("User Model Error: Duplicate entry for username or email.");
            }
            return false;
        }
    }
}
