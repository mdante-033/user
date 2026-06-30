<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

final class Reservation
{
    private const CAPACITY_PER_SLOT = 40;

    public static function all(int $limit = 100): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM reservations ORDER BY reservation_date DESC, reservation_time DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function create(array $data): array
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO reservations (user_id, name, phone, reservation_date, reservation_time, guests, notes)
             VALUES (:user_id, :name, :phone, :reservation_date, :reservation_time, :guests, :notes)
             RETURNING *'
        );
        $stmt->execute([
            'user_id' => $data['user_id'] ?? null,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'reservation_date' => $data['date'],
            'reservation_time' => $data['time'],
            'guests' => (int) $data['guests'],
            'notes' => $data['notes'] ?? null,
        ]);

        return $stmt->fetch();
    }

    public static function updateStatus(int $id, string $status): bool
    {
        $stmt = Database::connection()->prepare('UPDATE reservations SET status = :status WHERE id = :id');
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }

    public static function isAvailable(string $date, string $time, int $guests): bool
    {
        $stmt = Database::connection()->prepare(
            "SELECT COALESCE(SUM(guests), 0) AS total
             FROM reservations
             WHERE reservation_date = :date
               AND reservation_time = :time
               AND status IN ('pending', 'confirmed')"
        );
        $stmt->execute(['date' => $date, 'time' => $time]);
        $reserved = (int) ($stmt->fetch()['total'] ?? 0);
        return ($reserved + $guests) <= self::CAPACITY_PER_SLOT;
    }
}
