<?php

declare(strict_types=1);

use App\Models\Order;
use PHPUnit\Framework\TestCase;

use function App\Helpers\env;

final class OrderCreationTest extends TestCase
{
    public function testOrderCreationIntegrationExample(): void
    {
        if (!env('DB_DSN')) {
            $this->markTestSkipped('Set DB_DSN, DB_USER, and DB_PASS to run the order creation integration test.');
        }

        $order = Order::createFromCart(null, [
            'name' => 'Test Customer',
            'phone' => '0795879797',
            'email' => 'customer@example.com',
            'notes' => 'PHPUnit order',
        ], [[
            'id' => 1,
            'name' => 'Kachumbari Bowl',
            'price' => 250.00,
            'quantity' => 1,
            'image_url' => '',
        ]], 'cash');

        $this->assertArrayHasKey('id', $order);
        $this->assertSame('Test Customer', $order['customer_name']);
    }
}
