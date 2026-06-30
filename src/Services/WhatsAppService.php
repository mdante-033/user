<?php
declare(strict_types=1);

namespace App\Services;

final class WhatsAppService
{
    private const PHONE = '254795879797';
    private const DEFAULT_MESSAGE = "Hello I'd like to order from Cheryne's menu";

    public static function defaultOrderLink(): string
    {
        return self::link(self::DEFAULT_MESSAGE);
    }

    public static function itemLink(array $item): string
    {
        return self::link(sprintf(
            "Hello I'd like to order %s from Cheryne's menu. Price: KSh %s",
            $item['name'] ?? 'an item',
            number_format((float) ($item['price'] ?? 0), 2)
        ));
    }

    public static function orderLink(array $items, float $total): string
    {
        $lines = ["Hello I'd like to order from Cheryne's menu:"];
        foreach ($items as $item) {
            $lines[] = sprintf('- %s x %d', $item['name'], (int) $item['quantity']);
        }
        $lines[] = 'Total: KSh ' . number_format($total, 2);
        return self::link(implode("\n", $lines));
    }

    public static function reservationLink(array $reservation): string
    {
        return self::link(sprintf(
            "Hello Cheryne's, I'd like to confirm a reservation for %s guests on %s at %s. Name: %s. Phone: %s",
            $reservation['guests'] ?? '',
            $reservation['reservation_date'] ?? $reservation['date'] ?? '',
            $reservation['reservation_time'] ?? $reservation['time'] ?? '',
            $reservation['name'] ?? '',
            $reservation['phone'] ?? ''
        ));
    }

    public static function link(string $message): string
    {
        return 'https://wa.me/' . self::PHONE . '?text=' . rawurlencode($message);
    }
}
