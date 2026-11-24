<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth_check.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../availability.php');
}

$roomId = filter_input(INPUT_POST, 'room_id', FILTER_VALIDATE_INT);
$checkIn = trim($_POST['check_in'] ?? '');
$checkOut = trim($_POST['check_out'] ?? '');
$guests = filter_input(INPUT_POST, 'guests', FILTER_VALIDATE_INT);

if (!$roomId || !$guests) {
    set_flash('Invalid booking information.', 'error');
    redirect('../availability.php');
}
if (!valid_date($checkIn) || !valid_date($checkOut) || $checkOut <= $checkIn) {
    set_flash('Check-out must be after check-in.', 'error');
    redirect('../booking.php?room_id=' . $roomId . '&check_in=' . urlencode($checkIn) . '&check_out=' . urlencode($checkOut) . '&guests=' . $guests);
}
if ($checkIn < date('Y-m-d')) {
    set_flash('Check-in cannot be in the past.', 'error');
    redirect('../availability.php');
}

$roomStmt = $pdo->prepare('SELECT * FROM rooms WHERE room_id = ?');
$roomStmt->execute([$roomId]);
$room = $roomStmt->fetch();
if (!$room || $room['room_status'] !== 'active') {
    set_flash('This room is not available for booking.', 'error');
    redirect('../rooms.php');
}
if ($guests > (int)$room['max_guests']) {
    set_flash('This room cannot accommodate that many guests.', 'error');
    redirect('../room-details.php?id=' . $roomId);
}

$overlap = $pdo->prepare(
    'SELECT booking_id FROM bookings
     WHERE room_id = ? AND booking_status = \'Confirmed\'
       AND check_in < ? AND check_out > ? LIMIT 1'
);
$overlap->execute([$roomId, $checkOut, $checkIn]);
if ($overlap->fetch()) {
    set_flash('The room is no longer available for those dates.', 'error');
    redirect('../availability.php');
}

$nights = nights_between($checkIn, $checkOut);
$total = $nights * (float)$room['price_per_night'];
$insert = $pdo->prepare(
    'INSERT INTO bookings (user_id, room_id, check_in, check_out, guests, total_amount, booking_status)
     VALUES (?, ?, ?, ?, ?, ?, \'Pending\')'
);
$insert->execute([$_SESSION['user_id'], $roomId, $checkIn, $checkOut, $guests, $total]);
set_flash('Your booking request was submitted successfully and is pending confirmation.');
redirect('../my-bookings.php');
