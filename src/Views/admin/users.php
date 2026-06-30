<?php

use function App\Helpers\e;
use function App\Helpers\url;

/** @var array<int, array<string,mixed>> $users */
$users = $users ?? [];
?>
<section class="admin-shell container">
    <div class="admin-head">
        <div>
            <p class="eyebrow">Admin</p>
            <h1>Users</h1>
        </div>
        <div class="admin-tabs">
            <a href="<?= e(url('/admin')) ?>">Dashboard</a>
            <a href="<?= e(url('/admin/orders')) ?>">Orders</a>
        </div>
    </div>
    <section class="admin-panel">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Created</th></tr></thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= e($user['name']) ?></td>
                        <td><?= e($user['email']) ?></td>
                        <td><?= e($user['phone']) ?></td>
                        <td><?= e($user['role']) ?></td>
                        <td><?= e($user['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</section>
