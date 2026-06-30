<?php
declare(strict_types=1);

use function App\Helpers\csrf_field;
use function App\Helpers\e;
use function App\Helpers\money;
use function App\Helpers\url;

/** @var array<int, array<string, mixed>> $items */
$items = $items ?? [];

/** @var float $total */
$total = $total ?? 0.0;

// Safe WhatsApp link fallback
$whatsappLink = 'https://wa.me/254795879797?text=Hello%20Cheryne%27s%2C%20I%20would%20like%20to%20place%20an%20order';
if (class_exists(\App\Services\WhatsAppService::class)) {
    $whatsappLink = \App\Services\WhatsAppService::defaultOrderLink();
}

$isEmpty = empty($items);
?>
<section class="page-head">
    <div class="container">
        <h1>Your Cart</h1>
        <p>Review your Cheryne's order before checkout.</p>
    </div>
</section>

<section class="section-band">
    <div class="container cart-layout">
        <div class="cart-list">
            <?php if ($isEmpty): ?>
                <div class="empty-state">
                    <h2>Your cart is empty</h2>
                    <a class="btn btn-primary" href="<?= e(url('/menu')) ?>">Browse menu</a>
                </div>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <?php
                    $itemId = $item['id'] ?? 0;
                    $itemName = $item['name'] ?? 'Unnamed item';
                    $itemPrice = $item['price'] ?? 0;
                    $itemQty = $item['quantity'] ?? 1;
                    $itemImg = $item['image_url'] ?? '';
                    ?>
                    <article class="cart-row">
                        <?php if ($itemImg !== ''): ?>
                            <img src="<?= e($itemImg) ?>" alt="<?= e($itemName) ?>" loading="lazy" width="80" height="80">
                        <?php else: ?>
                            <div class="cart-img-placeholder" style="width:80px;height:80px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;border-radius:6px;color:#999;font-size:0.75rem;">
                                No image
                            </div>
                        <?php endif; ?>
                        <div>
                            <h2><?= e($itemName) ?></h2>
                            <p><?= e(money($itemPrice)) ?></p>
                        </div>
                        <form action="<?= e(url('/cart/update')) ?>" method="post" class="inline-form">
                            <?= csrf_field() ?>
                            <input type="hidden" name="item_id" value="<?= e($itemId) ?>">
                            <input type="number" name="quantity" min="1" max="20" value="<?= e($itemQty) ?>" aria-label="Quantity for <?= e($itemName) ?>">
                            <button class="btn btn-sm btn-outline-dark" type="submit">Update</button>
                        </form>
                        <form action="<?= e(url('/cart/remove')) ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="item_id" value="<?= e($itemId) ?>">
                            <button class="btn btn-sm btn-link text-danger" type="submit">Remove</button>
                        </form>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (!$isEmpty): ?>
            <aside class="summary-panel">
                <h2>Total</h2>
                <strong><?= e(money($total)) ?></strong>
                <a class="btn btn-primary w-100" href="<?= e(url('/checkout')) ?>">Checkout</a>
                <a class="btn btn-outline-dark w-100" href="<?= e($whatsappLink) ?>" target="_blank" rel="noopener">Order via WhatsApp</a>
            </aside>
        <?php endif; ?>
    </div>
</section>