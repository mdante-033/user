<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

final class User
{
    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => strtolower($email)]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT id, name, email, phone, role, created_at FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function all(): array
    {
        return Database::connection()
            ->query('SELECT id, name, email, phone, role, created_at FROM users ORDER BY created_at DESC')
            ->fetchAll();
    }

    public static function create(string $name, string $email, string $password, ?string $phone = null, string $role = 'customer'): array
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO users (name, email, phone, password_hash, role)
             VALUES (:name, :email, :phone, :password_hash, :role)
             RETURNING id, name, email, phone, role, created_at'
        );

        $stmt->execute([
            'name' => $name,
            'email' => strtolower($email),
            'phone' => $phone,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
        ]);

        return $stmt->fetch();
    }

    public static function recordFailedLogin(string $email): void
    {
        $stmt = Database::connection()->prepare(
            "UPDATE users
             SET failed_login_attempts = failed_login_attempts + 1,
                 locked_until = CASE
                     WHEN failed_login_attempts + 1 >= 5 THEN NOW() + INTERVAL '15 minutes'
                     ELSE locked_until
                 END
             WHERE email = :email"
        );
        $stmt->execute(['email' => strtolower($email)]);
    }

    public static function resetFailedLogin(int $id): void
    {
        $stmt = Database::connection()->prepare(
            'UPDATE users SET failed_login_attempts = 0, locked_until = NULL WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);
    }
}
