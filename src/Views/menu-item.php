<?php

/**
 * @var array<string, mixed> $item
 * @var string $whatsappLink
 */

use function App\Helpers\csrf_field;
use function App\Helpers\e;
use function App\Helpers\money;
use function App\Helpers\url;
?>
<section class="detail-layout container">
    <div class="detail-media">
        <img src="<?= e($item['image_url']) ?>" alt="<?= e($item['name']) ?>" loading="lazy">
    </div>
    <div class="detail-copy">
        <p class="eyebrow"><?= e($item['category_name'] ?? 'Menu') ?></p>
        <h1><?= e($item['name']) ?></h1>
        <p><?= e($item['description']) ?></p>
        <strong class="detail-price"><?= e(money($item['price'])) ?></strong>
        <div class="detail-actions">
            <form action="<?= e(url('/cart/add')) ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="item_id" value="<?= e($item['id']) ?>">
                <label class="qty-label">Qty <input type="number" name="quantity" min="1" max="20" value="1"></label>
                <button class="btn btn-primary" type="submit">Add to cart</button>
            </form>
            <a class="btn btn-outline-dark" href="<?= e($whatsappLink) ?>" target="_blank" rel="noopener">Order via WhatsApp</a>
            <a class="btn btn-outline-dark" href="tel:0795879797" aria-label="Call Cheryne's on 0795 879797">Call 0795 879797</a>
        </div>
    </div>
</section>
