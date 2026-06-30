<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

final class MenuItem
{
    public static function all(array $filters = []): array
    {
        $sql = 'SELECT menu_items.*, categories.name AS category_name
                FROM menu_items
                LEFT JOIN categories ON categories.id = menu_items.category_id
                WHERE 1 = 1';
        $params = [];

        if (!empty($filters['category_id'])) {
            $sql .= ' AND menu_items.category_id = :category_id';
            $params['category_id'] = (int) $filters['category_id'];
        }

        if (array_key_exists('available', $filters) && $filters['available'] !== '') {
            $sql .= ' AND menu_items.is_available = :available';
            $params['available'] = filter_var($filters['available'], FILTER_VALIDATE_BOOLEAN);
        }

        if (!empty($filters['min_price'])) {
            $sql .= ' AND menu_items.price >= :min_price';
            $params['min_price'] = (float) $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $sql .= ' AND menu_items.price <= :max_price';
            $params['max_price'] = (float) $filters['max_price'];
        }

        if (!empty($filters['q'])) {
            $sql .= ' AND (menu_items.name ILIKE :q OR menu_items.description ILIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        $sql .= ' ORDER BY categories.name, menu_items.name';
        $stmt = Database::connection()->prepare($sql);
        foreach ($params as $key => $value) {
            $type = is_bool($value) ? PDO::PARAM_BOOL : (is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            $stmt->bindValue(':' . $key, $value, $type);
        }
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT menu_items.*, categories.name AS category_name
             FROM menu_items
             LEFT JOIN categories ON categories.id = menu_items.category_id
             WHERE menu_items.id = :id'
        );
        $stmt->execute(['id' => $id]);
        $item = $stmt->fetch();
        return $item ?: null;
    }

    public static function create(array $data): array
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO menu_items (category_id, name, slug, description, price, image_url, is_available)
             VALUES (:category_id, :name, :slug, :description, :price, :image_url, :is_available)
             RETURNING *'
        );
        $stmt->execute(self::payload($data));
        return $stmt->fetch();
    }

    public static function update(int $id, array $data): bool
    {
        $payload = self::payload($data);
        $payload['id'] = $id;

        $stmt = Database::connection()->prepare(
            'UPDATE menu_items
             SET category_id = :category_id, name = :name, slug = :slug, description = :description,
                 price = :price, image_url = :image_url, is_available = :is_available, updated_at = NOW()
             WHERE id = :id'
        );

        return $stmt->execute($payload);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::connection()->prepare('DELETE FROM menu_items WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    private static function payload(array $data): array
    {
        return [
            'category_id' => empty($data['category_id']) ? null : (int) $data['category_id'],
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'],
            'price' => (float) $data['price'],
            'image_url' => $data['image_url'],
            'is_available' => (bool) $data['is_available'],
        ];
    }
}
