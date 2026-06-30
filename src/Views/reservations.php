<?php

/**
 * @var string|null $reservationLink
 */

use function App\Helpers\csrf_field;
use function App\Helpers\e;
use function App\Helpers\url;
?>
<section class="page-head">
    <div class="container">
        <p class="eyebrow">Nyali restaurant</p>
        <h1>Reserve at Cheryne's</h1>
        <p>Book a table for local Kenyan meals in Nyali, Mombasa.</p>
    </div>
</section>
<section class="section-band">
    <div class="container checkout-layout">
        <form class="form-panel" action="<?= e(url('/reservation')) ?>" method="post">
            <?= csrf_field() ?>
            <label>Name <input name="name" required maxlength="120"></label>
            <label>Phone <input name="phone" required maxlength="30"></label>
            <label>Date <input type="date" name="date" required></label>
            <label>Time <input type="time" name="time" required></label>
            <label>Guests <input type="number" name="guests" min="1" max="40" required></label>
            <label>Notes <textarea name="notes" maxlength="500" rows="4"></textarea></label>
            <button class="btn btn-primary" type="submit">Request reservation</button>
        </form>
        <aside class="summary-panel">
            <h2>Call to reserve</h2>
            <p>0795 879797</p>
            <a class="btn btn-outline-dark w-100" href="tel:0795879797" aria-label="Call Cheryne's on 0795 879797">Call now</a>
            <?php if ($reservationLink): ?>
                <a class="btn btn-primary w-100" href="<?= e($reservationLink) ?>" target="_blank" rel="noopener">Confirm on WhatsApp</a>
            <?php endif; ?>
        </aside>
    </div>
</section>
