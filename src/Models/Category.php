<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

final class Category
{
    public static function all(): array
    {
        return Database::connection()
            ->query('SELECT * FROM categories ORDER BY name')
            ->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $category = $stmt->fetch();
        return $category ?: null;
    }

    public static function create(string $name, string $slug): array
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO categories (name, slug) VALUES (:name, :slug) RETURNING *'
        );
        $stmt->execute(['name' => $name, 'slug' => $slug]);
        return $stmt->fetch();
    }

    public static function update(int $id, string $name, string $slug): bool
    {
        $stmt = Database::connection()->prepare(
            'UPDATE categories SET name = :name, slug = :slug WHERE id = :id'
        );
        return $stmt->execute(['id' => $id, 'name' => $name, 'slug' => $slug]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM categories WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
