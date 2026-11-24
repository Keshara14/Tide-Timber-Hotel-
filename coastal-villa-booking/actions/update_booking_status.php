<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/admin_check.php';

$bookingId = filter_input(INPUT_POST, 'booking_id', FILTER_VALIDATE_INT);
$status = $_POST['status'] ?? '';
if (!$bookingId || !in_array($status, ['Confirmed', 'Cancelled'], true)) {
    set_flash('Invalid status update.', 'error');
    redirect('../admin/bookings.php');
}

$stmt = $pdo->prepare('SELECT * FROM bookings WHERE booking_id = ?');
$stmt->execute([$bookingId]);
$booking = $stmt->fetch();
if (!$booking) {
    set_flash('Booking not found.', 'error');
    redirect('../admin/bookings.php');
}

if ($status === 'Confirmed') {
    $overlap = $pdo->prepare(
        'SELECT booking_id FROM bookings
         WHERE room_id = ? AND booking_status = \'Confirmed\'
           AND booking_id <> ? AND check_in < ? AND check_out > ? LIMIT 1'
    );
    $overlap->execute([$booking['room_id'], $bookingId, $booking['check_out'], $booking['check_in']]);
    if ($overlap->fetch()) {
        set_flash('Cannot confirm: another confirmed booking overlaps these dates.', 'error');
        redirect('../admin/bookings.php');
    }
}

$update = $pdo->prepare('UPDATE bookings SET booking_status = ? WHERE booking_id = ?');
$update->execute([$status, $bookingId]);
set_flash('Booking status updated to ' . $status . '.');
redirect('../admin/bookings.php');
