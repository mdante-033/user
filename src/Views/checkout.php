<?php
declare(strict_types=1);

use function App\Helpers\csrf_field;
use function App\Helpers\current_user;
use function App\Helpers\e;
use function App\Helpers\money;
use function App\Helpers\url;

/** @var array<int, array<string, mixed>> $items */
$items = $items ?? [];

/** @var float $total */
$total = $total ?? 0.0;

$user = current_user();
$isEmpty = empty($items);
?>
<section class="page-head">
    <div class="container">
        <h1>Checkout</h1>
        <p>Complete your order for pickup or confirmation by phone.</p>
    </div>
</section>

<section class="section-band">
    <div class="container checkout-layout">
        <?php if ($isEmpty): ?>
            <div class="empty-state">
                <h2>Your cart is empty</h2>
                <p>Add items to your cart before checking out.</p>
                <a class="btn btn-primary" href="<?= e(url('/menu')) ?>">Browse menu</a>
            </div>
        <?php else: ?>
            <form class="form-panel" action="<?= e(url('/checkout')) ?>" method="post" novalidate>
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="checkout-name">Name</label>
                    <input type="text" id="checkout-name" name="name" required maxlength="120" value="<?= e($user['name'] ?? '') ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label for="checkout-phone">Phone</label>
                    <input type="tel" id="checkout-phone" name="phone" required maxlength="30" value="<?= e($user['phone'] ?? '') ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label for="checkout-email">Email</label>
                    <input type="email" id="checkout-email" name="email" maxlength="160" value="<?= e($user['email'] ?? '') ?>" class="form-control" placeholder="Optional — for order receipt">
                </div>

                <div class="form-group">
                    <label for="checkout-payment">Payment method</label>
                    <select id="checkout-payment" name="payment_method" required class="form-control">
                        <option value="cash" selected>Pay on confirmation</option>
                        <option value="stripe">Stripe card checkout</option>
                        <option value="mpesa">M-Pesa STK Push</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="checkout-notes">Order notes</label>
                    <textarea id="checkout-notes" name="notes" maxlength="500" rows="4" class="form-control" placeholder="Allergies, special requests, preferred pickup time..."></textarea>
                </div>

                <button class="btn btn-primary w-100" type="submit">Place order</button>
            </form>

            <aside class="summary-panel">
                <h2>Order Summary</h2>
                <?php foreach ($items as $item): ?>
                    <?php
                    $itemName = $item['name'] ?? 'Item';
                    $itemQty = (int) ($item['quantity'] ?? 1);
                    $itemPrice = (float) ($item['price'] ?? 0);
                    $lineTotal = $itemPrice * $itemQty;
                    ?>
                    <div class="summary-row">
                        <span><?= e($itemName) ?> x <?= e($itemQty) ?></span>
                        <strong><?= e(money($lineTotal)) ?></strong>
                    </div>
                <?php endforeach; ?>
                <div class="summary-total">
                    <span>Total</span>
                    <strong><?= e(money($total)) ?></strong>
                </div>
            </aside>
        <?php endif; ?>
    </div>
</section>