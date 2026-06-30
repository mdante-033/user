<?php
declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;
use Throwable;

final class Order
{
    public static function createFromCart(?int $userId, array $customer, array $cart, string $paymentMethod): array
    {
        $pdo = Database::connection();
        $total = array_reduce($cart, static fn (float $sum, array $item): float => $sum + ((float) $item['price'] * (int) $item['quantity']), 0.0);

        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare(
                'INSERT INTO orders (user_id, customer_name, phone, email, payment_method, total_amount, notes)
                 VALUES (:user_id, :customer_name, :phone, :email, :payment_method, :total_amount, :notes)
                 RETURNING *'
            );
            $stmt->execute([
                'user_id' => $userId,
                'customer_name' => $customer['name'],
                'phone' => $customer['phone'],
                'email' => $customer['email'],
                'payment_method' => $paymentMethod,
                'total_amount' => $total,
                'notes' => $customer['notes'] ?? null,
            ]);
            $order = $stmt->fetch();

            $itemStmt = $pdo->prepare(
                'INSERT INTO order_items (order_id, menu_item_id, item_name, quantity, unit_price, line_total)
                 VALUES (:order_id, :menu_item_id, :item_name, :quantity, :unit_price, :line_total)'
            );
            foreach ($cart as $item) {
                $qty = (int) $item['quantity'];
                $price = (float) $item['price'];
                $itemStmt->execute([
                    'order_id' => $order['id'],
                    'menu_item_id' => $item['id'],
                    'item_name' => $item['name'],
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'line_total' => $qty * $price,
                ]);
            }

            $pdo->commit();
            return $order;
        } catch (Throwable $throwable) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $throwable;
        }
    }

    public static function all(int $limit = 100): array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM orders ORDER BY created_at DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findWithItems(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM orders WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch();
        if (!$order) {
            return null;
        }

        $items = Database::connection()->prepare('SELECT * FROM order_items WHERE order_id = :id ORDER BY id');
        $items->execute(['id' => $id]);
        $order['items'] = $items->fetchAll();
        return $order;
    }

    public static function updateStatus(int $id, string $status): bool
    {
        $stmt = Database::connection()->prepare('UPDATE orders SET status = :status, updated_at = NOW() WHERE id = :id');
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }

    public static function dashboardStats(): array
    {
        $pdo = Database::connection();
        $orders = $pdo->query("SELECT COUNT(*) AS count FROM orders WHERE created_at >= NOW() - INTERVAL '30 days'")->fetch();
        $revenue = $pdo->query("SELECT COALESCE(SUM(total_amount), 0) AS total FROM orders WHERE status IN ('confirmed','preparing','ready','completed') AND created_at >= NOW() - INTERVAL '30 days'")->fetch();
        $reservations = $pdo->query("SELECT COUNT(*) AS count FROM reservations WHERE reservation_date >= CURRENT_DATE")->fetch();

        return [
            'orders_30_days' => (int) ($orders['count'] ?? 0),
            'revenue_30_days' => (float) ($revenue['total'] ?? 0),
            'upcoming_reservations' => (int) ($reservations['count'] ?? 0),
        ];
    }
}
