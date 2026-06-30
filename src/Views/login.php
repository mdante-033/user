<?php

use function App\Helpers\csrf_field;
use function App\Helpers\e;
use function App\Helpers\url;
?>
<section class="auth-shell">
    <form class="form-panel auth-panel" action="<?= e(url('/auth/login')) ?>" method="post">
        <?= csrf_field() ?>
        <h1>Login</h1>
        <label>Email <input type="email" name="email" required></label>
        <label>Password <input type="password" name="password" required></label>
        <button class="btn btn-primary" type="submit">Sign in</button>
        <p><a href="<?= e(url('/auth/register')) ?>">Create an account</a></p>
    </form>
</section>
