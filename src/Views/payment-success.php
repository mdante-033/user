<?php

use function App\Helpers\e;
use function App\Helpers\url;

/**
 * This view displays the order confirmation page after a successful payment.
 *
 * @var ?string $orderId The unique identifier for the order. Null if not provided.
 * @var ?string $customerName The name of the customer. Null if not provided.
 * @var ?string $estimatedDeliveryTime The estimated time for delivery. Null if not provided.
 * @var string $restaurantPhoneNumber The contact phone number for the restaurant.
 */

// --- Define default values or fallback for variables ---
$displayOrderId = $orderId ?? null;
$displayName = $customerName ?? null;
$displayDeliveryTime = $estimatedDeliveryTime ?? null;
$phoneToCall = $restaurantPhoneNumber ?? '0795879797'; // Fallback phone number

?>

<section class="page-confirmation py-8">
    <div class="container mx-auto px-4 text-center">

        <!-- Success Icon -->
        <div class="mb-6 animate-bounce">
            <svg class="w-16 h-16 mx-auto text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <h1 class="text-3xl font-bold mb-3 text-gray-800">Order Received!</h1>

        <?php if ($displayName): ?>
            <p class="text-xl text-gray-700 mb-2">Thank you, <?= e($displayName) ?>!</p>
        <?php endif; ?>

        <p class="text-lg text-gray-600 mb-6">
            Your order<?= $displayOrderId ? ' #' . e($displayOrderId) : '' ?> has been successfully placed and is now in our kitchen's queue.
        </p>

        <?php if ($displayDeliveryTime): ?>
            <div class="bg-blue-100 border-t-4 border-blue-500 rounded-b text-blue-900 px-4 py-3 shadow mb-6" role="alert">
                <p class="font-bold">Estimated Delivery/Pickup Time:</p>
                <p><?= e($displayDeliveryTime) ?></p>
            </div>
        <?php endif; ?>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a
                href="<?= e(url('/menu')) ?>"
                class="btn btn-primary btn-large w-full sm:w-auto"
            >
                Order More Items
            </a>
            <a
                href="tel:<?= \App\Helpers\e($phoneToCall) ?>"
                class="btn btn-outline-dark btn-large w-full sm:w-auto"
                aria-label="Call Cheryne's at <?= \App\Helpers\e($phoneToCall) ?>"
            >
                Call Us
            </a>
        </div>

        <p class="mt-8 text-sm text-gray-500">
            We'll notify you once your order is ready for pickup or out for delivery.
        </p>

    </div>
</section>