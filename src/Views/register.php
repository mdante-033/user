<?php
declare(strict_types=1);

use function App\Helpers\csrf_field;
use function App\Helpers\e;
use function App\Helpers\url;

/** @var array<string, string> $errors */
$errors = $errors ?? [];

/** @var array<string, string> $old */
$old = $old ?? [];
?>
<section class="auth-shell">
    <form class="form-panel auth-panel" action="<?= e(url('/auth/register')) ?>" method="post" novalidate>
        <?= csrf_field() ?>

        <h1>Create account</h1>
        <p class="text-muted">Join Cheryne's for faster ordering and reservations.</p>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-danger" role="alert">
                <?= e($errors['general']) ?>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="reg-name">Full name</label>
            <input
                type="text"
                id="reg-name"
                name="name"
                value="<?= e($old['name'] ?? '') ?>"
                required
                maxlength="120"
                autocomplete="name"
                autofocus
                class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                aria-describedby="name-help"
            >
            <?php if (!empty($errors['name'])): ?>
                <div class="invalid-feedback" id="name-help"><?= e($errors['name']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="reg-phone">Phone number</label>
            <input
                type="tel"
                id="reg-phone"
                name="phone"
                value="<?= e($old['phone'] ?? '') ?>"
                required
                maxlength="30"
                autocomplete="tel"
                inputmode="tel"
                placeholder="e.g. 0795 879797"
                class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                aria-describedby="phone-help"
            >
            <?php if (!empty($errors['phone'])): ?>
                <div class="invalid-feedback" id="phone-help"><?= e($errors['phone']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="reg-email">Email address</label>
            <input
                type="email"
                id="reg-email"
                name="email"
                value="<?= e($old['email'] ?? '') ?>"
                required
                maxlength="160"
                autocomplete="email"
                placeholder="you@example.com"
                class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                aria-describedby="email-help"
            >
            <?php if (!empty($errors['email'])): ?>
                <div class="invalid-feedback" id="email-help"><?= e($errors['email']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="reg-password">Password</label>
            <input
                type="password"
                id="reg-password"
                name="password"
                required
                minlength="8"
                autocomplete="new-password"
                class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                aria-describedby="password-help"
            >
            <small id="password-help" class="form-text text-muted">
                Minimum 8 characters with uppercase, lowercase, number, and special character.
            </small>
            <?php if (!empty($errors['password'])): ?>
                <div class="invalid-feedback"><?= e($errors['password']) ?></div>
            <?php endif; ?>
        </div>

        <button class="btn btn-primary w-100" type="submit">Create account</button>

        <p class="text-center mt-3">
            Already have an account? <a href="<?= e(url('/auth/login')) ?>">Sign in</a>
        </p>
    </form>
</section>