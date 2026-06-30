<?php
declare(strict_types=1);

$loadLocalEnv = static function (): void {
    static $loaded = false;

    if ($loaded) {
        return;
    }
    $loaded = true;
    $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';

    if (!is_file($path)) {
        return;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = array_map('trim', explode('=', $line, 2));
        $value = trim($value, "\"'");

        $_ENV[$key] = $_SERVER[$key] = $value;
        putenv($key . '=' . $value);
    }
};

return static function (string $key, mixed $default = null) use ($loadLocalEnv): mixed {
    if (function_exists('App\\Helpers\\env')) {
        return \App\Helpers\env($key, $default);
    }

    $loadLocalEnv();

    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    if ($value === false || $value === null || $value === '') {
        return $default;
    }

    return match (strtolower((string) $value)) {
        'true', '(true)' => true,
        'false', '(false)' => false,
        'null', '(null)' => null,
        default => $value,
    };
};
