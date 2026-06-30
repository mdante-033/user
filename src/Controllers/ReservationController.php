<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Reservation;
use App\Services\WhatsAppService;

use function App\Helpers\clean_string;
use function App\Helpers\current_user;
use function App\Helpers\flash;
use function App\Helpers\rate_limit;
use function App\Helpers\redirect;
use function App\Helpers\valid_phone;
use function App\Helpers\verify_csrf_or_fail;
use function App\Helpers\encrypt_data;
use function App\Helpers\get_encryption_key;
use function App\Helpers\view;

final class ReservationController
{
    public function form(): void
    {
        view('reservations', [
            'title' => "Reservations - Cheryne's",
            'description' => "Reserve a table at Cheryne's in Nyali, Mombasa. Call 0795 879797.",
            'reservationLink' => null,
        ]);
    }

    public function store(): void
    {
        verify_csrf_or_fail();
        if (!rate_limit('reservation', 4, 600)) {
            flash('danger', 'Too many reservation attempts. Please try again later.');
            redirect('/reservations');
        }

        $name = clean_string(filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW), 120);
        $phone = clean_string(filter_input(INPUT_POST, 'phone', FILTER_UNSAFE_RAW), 30);
        $date = clean_string(filter_input(INPUT_POST, 'date', FILTER_UNSAFE_RAW), 10);
        $time = clean_string(filter_input(INPUT_POST, 'time', FILTER_UNSAFE_RAW), 5);
        $guests = filter_input(INPUT_POST, 'guests', FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 40],
        ]);
        $notes = clean_string(filter_input(INPUT_POST, 'notes', FILTER_UNSAFE_RAW), 500);

        $dateTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
        if ($name === '' || !valid_phone($phone) || !$dateTime || $dateTime <= new \DateTimeImmutable('now') || !$guests) {
            flash('danger', 'Please provide valid reservation details.');
            redirect('/reservations');
        }

        if (!Reservation::isAvailable($date, $time, (int) $guests)) {
            flash('warning', 'That time is fully booked. Please choose another time.');
            redirect('/reservations');
        }

        $encryptionKey = get_encryption_key();
        // Encrypt sensitive notes
        $encryptedNotes = null;
        if (!empty($notes)) {
            $encryptedNotes = encrypt_data($notes, $encryptionKey);
        }

        $user = current_user();
        $reservation = Reservation::create([
            'user_id' => $user ? (int) $user['id'] : null,
            'name' => $name,
            'phone' => $phone,
            'date' => $date,
            'time' => $time,
            'guests' => (int) $guests,
            'notes' => $encryptedNotes,
        ]);

        flash('success', 'Your reservation request was received.');
        view('reservations', [
            'title' => "Reservations - Cheryne's",
            'description' => "Reserve a table at Cheryne's in Nyali, Mombasa.",
            'reservationLink' => WhatsAppService::reservationLink($reservation),
        ]);
    }
}
