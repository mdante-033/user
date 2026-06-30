<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Order;
use App\Models\Payment;

use function App\Helpers\flash;
use function App\Helpers\redirect;
use function App\Helpers\view;

final class PaymentController
{
    public function success(): void
    {
        $orderId = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);
        $cash = filter_input(INPUT_GET, 'cash', FILTER_VALIDATE_BOOLEAN);
        $mpesa = filter_input(INPUT_GET, 'mpesa', FILTER_VALIDATE_BOOLEAN);
        if ($orderId && !$cash && !$mpesa) {
            Payment::markPaid((int) $orderId, 'WEB-' . strtoupper(bin2hex(random_bytes(4))));
            Order::updateStatus((int) $orderId, 'confirmed');
        }

        view('payment-success', [
            'title' => "Payment Received - Cheryne's",
            'description' => "Your Cheryne's order is confirmed.",
            'orderId' => $orderId,
        ]);
    }

    public function cancel(): void
    {
        flash('warning', 'Payment was cancelled. You can place the order again when ready.');
        redirect('/cart');
    }
}