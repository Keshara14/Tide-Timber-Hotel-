<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth_check.php';

$bookingId = filter_input(INPUT_POST, 'booking_id', FILTER_VALIDATE_INT);
if (!$bookingId) {
    set_flash('Invalid booking.', 'error');
    redirect('../my-bookings.php');
}
$stmt = $pdo->prepare("SELECT * FROM bookings WHERE booking_id = ? AND user_id = ?");
$stmt->execute([$bookingId, $_SESSION['user_id']]);
$booking = $stmt->fetch();
if (!$booking) {
    set_flash('Booking not found.', 'error');
    redirect('../my-bookings.php');
}
if ($booking['booking_status'] !== 'Pending') {
    set_flash('Only Pending bookings can be cancelled.', 'error');
    redirect('../my-bookings.php');
}
$update = $pdo->prepare("UPDATE bookings SET booking_status = 'Cancelled' WHERE booking_id = ?");
$update->execute([$bookingId]);
set_flash('Your booking has been cancelled.');
redirect('../my-bookings.php');
