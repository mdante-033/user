<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

final class OrderItem
{
    public static function byOrder(int $orderId): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM order_items WHERE order_id = :order_id ORDER BY id');
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll();
    }
}
