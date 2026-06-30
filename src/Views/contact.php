<?php

use function App\Helpers\e;
?>
<section class="page-head">
    <div class="container">
        <p class="eyebrow">Nyali, Mombasa</p>
        <h1>Contact Cheryne's</h1>
        <p>Call 0795 879797 for orders, reservations, and local food in Nyali.</p>
    </div>
</section>
<section class="section-band">
    <div class="container contact-grid">
        <div class="form-panel">
            <h2>Direct contact</h2>
            <p><strong>Phone:</strong> <a href="tel:0795879797" aria-label="Call Cheryne's on 0795 879797">0795 879797</a></p>
            <p><strong>Area served:</strong> Nyali, Mombasa</p>
            <p><strong>Cuisine:</strong> Local, Kenyan</p>
        </div>
        <div class="map-panel">
            <?php if ($mapsKey !== ''): ?>
                <iframe title="Cheryne's map" loading="lazy" allowfullscreen src="https://www.google.com/maps/embed/v1/place?key=<?= e($mapsKey) ?>&q=Nyali,Mombasa,Kenya"></iframe>
            <?php else: ?>
                <div class="empty-state">
                    <h2>Map ready</h2>
                    <p>Add GOOGLE_MAPS_API_KEY in .env to show the Google Maps embed.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
