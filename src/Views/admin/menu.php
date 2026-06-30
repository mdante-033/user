<?php

use function App\Helpers\csrf_field;
use function App\Helpers\e;
use function App\Helpers\money;
use function App\Helpers\url;

/**
 * This view displays the restaurant's menu with filtering and search capabilities.
 *
 * @var array<int, array<string,mixed>> $categories A list of available food categories.
 * @var array<int, array<string,mixed>> $items The list of menu items to display.
 * @var array<string, string|int|null> $filters The current filter/search parameters applied.
 */

// Provide default empty values if variables are not set, preventing errors.
$categories = $categories ?? [];
$items = $items ?? [];
$filters = $filters ?? [];

?>
<section class="page-hero">
    <div class="container text-center">
        <p class="eyebrow">Authentic local foods Nyali Mombasa</p>
        <h1>Cheryne's Menu</h1>
        <p class="subtitle">Browse our delicious local dishes, filter by category or availability, and order with ease.</p>
    </div>
</section>

<section class="section-toolbar">
    <div class="container">
        <form
            id="menu-filter-form"
            method="get"
            action="<?= e(url('/menu')) ?>"
            class="filter-bar"
            data-menu-filter-listener
        >
            <div class="filter-group">
                <label for="category_filter">Category</label>
                <select name="category_id" id="category_filter" onchange="this.closest('form').submit()">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option
                            value="<?= e($category['id']) ?>"
                            <?= (int) ($filters['category_id'] ?? 0) === (int) $category['id'] ? 'selected' : '' ?>
                        >
                            <?= e($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="availability_filter">Availability</label>
                <select name="available" id="availability_filter" onchange="this.closest('form').submit()">
                    <option value="">All</option>
                    <option value="1" <?= ($filters['available'] ?? '') === '1' ? 'selected' : '' ?>>Available</option>
                    <option value="0" <?= ($filters['available'] ?? '') === '0' ? 'selected' : '' ?>>Unavailable</option>
                </select>
            </div>

            <div class="filter-group price-range">
                <label for="min_price_filter">Min Price ($)</label>
                <input
                    type="number"
                    name="min_price"
                    id="min_price_filter"
                    min="0"
                    step="1"
                    value="<?= e($filters['min_price'] ?? '') ?>"
                    placeholder="e.g., 500"
                    onchange="this.closest('form').submit()"
                >
            </div>

            <div class="filter-group price-range">
                <label for="max_price_filter">Max Price ($)</label>
                <input
                    type="number"
                    name="max_price"
                    id="max_price_filter"
                    min="0"
                    step="1"
                    value="<?= e($filters['max_price'] ?? '') ?>"
                    placeholder="e.g., 2000"
                    onchange="this.closest('form').submit()"
                >
            </div>

            <div class="filter-group search-group">
                <label for="search_query">Search</label>
                <input
                    type="search"
                    name="q"
                    id="search_query"
                    value="<?= e($filters['q'] ?? '') ?>"
                    placeholder="Search for dishes..."
                >
                <button type="submit" class="btn btn-secondary btn-icon" aria-label="Search Menu">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 103.24 10.04l3.174 3.175a.75.75 0 101.06-1.06l-3.175-3.174A5.5 5.5 0 009 3.5zM7.5 7a4 4 0 118 0 4 4 0 01-8 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <button type="reset" class="btn btn-outline-danger btn-sm" onclick="window.location.href='<?= e(url('/menu')) ?>'">Clear filters</button>

        </form>
    </div>
</section>

<section class="section-menu-grid">
    <div class="container" id="menu-results">
        <?php if (empty($items)): ?>
            <div class="no-items-found">
                <p>No menu items found matching your criteria.</p>
                <p>Try adjusting your filters or search query.</p>
                <button class="btn btn-outline-primary" onclick="window.location.href='<?= e(url('/menu')) ?>'">View Full Menu</button>
            </div>
        <?php else: ?>
            <?php foreach ($items as $item): ?>
                <article class="menu-card" data-item-id="<?= e($item['id']) ?>">
                    <a href="<?= e(url('/menu/' . $item['id'])) ?>" class="menu-card-image-link">
                        <img src="<?= e($item['image_url']) ?>" alt="<?= e($item['name']) ?>" loading="lazy" class="menu-card-image">
                    </a>
                    <div class="menu-card-body">
                        <span class="menu-card-category"><?= e($item['category_name'] ?? 'Uncategorized') ?></span>
                        <h3 class="menu-card-title"><a href="<?= e(url('/menu/' . $item['id'])) ?>"><?= e($item['name']) ?></a></h3>
                        <p class="menu-card-description"><?= e($item['description']) ?></p>
                        <div class="menu-card-footer">
                            <strong class="menu-card-price"><?= e(money($item['price'])) ?></strong>
                            <?php if (filter_var($item['is_available'], FILTER_VALIDATE_BOOLEAN)): ?>
                                <form action="<?= e(url('/cart/add')) ?>" method="post" class="add-to-cart-form">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="item_id" value="<?= e($item['id']) ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button class="btn btn-sm btn-primary" type="submit" aria-label="Add <?= e($item['name']) ?> to cart">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path d="M2.75 2.75a.75.75 0 000 1.5h4.486a.75.75 0 00.75-.75c0-.292-.127-.574-.347-.783a2.754 2.754 0 00-2.094-1H2.75zM5.679 5.5h9.358c.387 0 .75.292.75.75c0 .107-.04.215-.12.304a1.3 1.3 0 01-.993.42H5.679l-.977 2.658h10.694a.75.75 0 01.748.697l.503 4.5A1.5 1.5 0 0116.172 16H5.933a1.5 1.5 0 01-1.492-1.376l-.503-4.5A.75.75 0 015.679 5.5zM6.399 12a.75.75 0 01.749.749c0 .415-.336.75-.75.75a.75.75 0 01-.75-.75v-1.5a.75.75 0 01.75-.75h.502v2zM10.75 12a.75.75 0 01.75.75v1.5a.75.75 0 01-.75.75a.75.75 0 01-.75-.75v-1.5a.75.75 0 01.75-.75z" />
                                        </svg>
                                        <span>Add</span>
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="status-pill status-pill-unavailable">Unavailable</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php /*
    // If you want to use JavaScript for more dynamic filtering without full page reloads,
    // you would typically have a script tag here or include it in your main JS file.
    // Example of what that might look like (requires jQuery or similar):
    <script>
        $(document).ready(function() {
            $('[data-menu-filter-listener]').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                const formData = $(this).serialize();
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    success: function(response) {
                        $('#menu-results').html($(response).find('#menu-results').html());
                        // Re-initialize lazy loading or other JS components if needed
                    },
                    error: function(xhr, status, error) {
                        console.error("Error filtering menu:", error);
                        // Display an error message to the user
                    }
                });
            });
        });
    </script>
*/ ?>