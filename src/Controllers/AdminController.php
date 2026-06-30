<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Config\Database;
use function App\Helpers\is_admin;
use function App\Helpers\redirect;
use function App\Helpers\view;

class AdminController
{
    public function dashboard(): void
    {
        if (!is_admin()) {
            redirect('/auth/login');
        }

        $pdo = Database::connection();

        $orders30 = $pdo->query(
            "SELECT COUNT(*) AS cnt, COALESCE(SUM(total_amount), 0) AS revenue
             FROM orders
             WHERE created_at >= NOW() - INTERVAL '30 days'"
        )->fetch();

        $upcomingReservations = $pdo->query(
            "SELECT COUNT(*) AS cnt
             FROM reservations
             WHERE reservation_date >= CURRENT_DATE"
        )->fetch();

        $stats = [
            'orders_30_days' => (int) ($orders30['cnt'] ?? 0),
            'revenue_30_days' => (float) ($orders30['revenue'] ?? 0),
            'upcoming_reservations' => (int) ($upcomingReservations['cnt'] ?? 0),
        ];

        $orders = $pdo->query(
            "SELECT id, customer_name, status, total_amount
             FROM orders
             ORDER BY created_at DESC
             LIMIT 10"
        )->fetchAll();

        $reservations = $pdo->query(
            "SELECT name, reservation_date, reservation_time, guests, status
             FROM reservations
             WHERE reservation_date >= CURRENT_DATE
             ORDER BY reservation_date ASC, reservation_time ASC
             LIMIT 10"
        )->fetchAll();

        view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
            'orders' => $orders,
            'reservations' => $reservations,
        ]);
    }

    public function menuManage(): void
    {
        if (!is_admin()) {
            redirect('/auth/login');
        }

        $pdo = Database::connection();

        $categories = $pdo->query(
            "SELECT id, name FROM categories ORDER BY name ASC"
        )->fetchAll();

        $items = $pdo->query(
            "SELECT id, name, description, price, image_url, category_id, is_available
             FROM menu_items
             ORDER BY name ASC"
        )->fetchAll();

        view('admin/menu', [
            'title' => 'Menu Management',
            'categories' => $categories,
            'items' => $items,
        ]);
    }

    public function orders(): void
    {
        if (!is_admin()) {
            redirect('/auth/login');
        }

        $pdo = Database::connection();

        $orders = $pdo->query(
            "SELECT id, customer_name, phone, payment_method, total_amount, status
             FROM orders
             ORDER BY created_at DESC"
        )->fetchAll();

        $reservations = $pdo->query(
            "SELECT id, name, phone, reservation_date, reservation_time, guests, status
             FROM reservations
             ORDER BY reservation_date DESC, reservation_time DESC"
        )->fetchAll();

        view('admin/orders', [
            'title' => 'Orders & Reservations',
            'orders' => $orders,
            'reservations' => $reservations,
        ]);
    }

    public function reservations(): void
    {
        if (!is_admin()) {
            redirect('/auth/login');
        }

        // Currently reusing the same view as orders(); split out if you want
        // a dedicated reservations-only admin screen later.
        $this->orders();
    }

    public function users(): void
    {
        if (!is_admin()) {
            redirect('/auth/login');
        }

        $pdo = Database::connection();

        $users = $pdo->query(
            "SELECT name, email, phone, role, created_at
             FROM users
             ORDER BY created_at DESC"
        )->fetchAll();

        view('admin/users', [
            'title' => 'Users',
            'users' => $users,
        ]);
    }
}