<?php
require_once __DIR__ . '/includes/db.php';
$pageTitle = 'Availability';
require __DIR__ . '/includes/header.php';

$checkIn = $_GET['check_in'] ?? '';
$checkOut = $_GET['check_out'] ?? '';
$guests = filter_input(INPUT_GET, 'guests', FILTER_VALIDATE_INT) ?: 1;
$availableRooms = [];
$searched = isset($_GET['search']);
if ($searched) {
    $errors = [];
    if (!valid_date($checkIn) || !valid_date($checkOut)) $errors[] = 'Please select valid check-in and check-out dates.';
    elseif ($checkOut <= $checkIn) $errors[] = 'Check-out must be after check-in.';
    elseif ($checkIn < date('Y-m-d')) $errors[] = 'Check-in cannot be in the past.';
    if ($guests < 1) $errors[] = 'Please enter at least one guest.';
    if (!$errors) {
        $stmt = $pdo->prepare(
            'SELECT r.* FROM rooms r
             WHERE r.room_status = \'active\' AND r.max_guests >= ?
               AND NOT EXISTS (
                   SELECT 1 FROM bookings b
                   WHERE b.room_id = r.room_id
                     AND b.booking_status = \'Confirmed\'
                     AND b.check_in < ? AND b.check_out > ?
               )
             ORDER BY r.price_per_night'
        );
        $stmt->execute([$guests, $checkOut, $checkIn]);
        $availableRooms = $stmt->fetchAll();
    }
}
?>
<section class="section availability-heading">
    <p class="eyebrow">Plan your stay</p>
    <h1>Check room availability</h1>
    <?php display_flash(); ?>
</section>
<section class="section availability">
    <form class="search-card" method="get">
        <input type="hidden" name="search" value="1">
        <label>Check-in<input type="date" name="check_in" value="<?php echo e($checkIn); ?>" required></label>
        <label>Check-out<input type="date" name="check_out" value="<?php echo e($checkOut); ?>" required></label>
        <label>Guests<input type="number" name="guests" min="1" value="<?php echo e($guests); ?>" required></label>
        <button class="button" type="submit">Search</button>
    </form>
    <?php if (!empty($errors)): foreach ($errors as $error): ?>
        <div class="alert alert-error"><?php echo e($error); ?></div>
    <?php endforeach; endif; ?>
    <?php if ($searched && empty($errors)): ?>
        <?php if (!$availableRooms): ?>
            <p class="empty-state">No rooms are available for your selected dates and guest count.</p>
        <?php else: ?>
            <div class="room-grid">
            <?php foreach ($availableRooms as $room): ?>
                <article class="room-card">
                    <a class="room-image" href="room-details.php?id=<?php echo (int)$room['room_id']; ?>">
                        <img src="<?php echo e($room['room_image']); ?>" alt="<?php echo e($room['room_name']); ?>">
                    </a>
                    <div class="room-card-body">
                        <p class="room-type"><?php echo e($room['room_type']); ?></p>
                        <h3><?php echo e($room['room_name']); ?></h3>
                        <p class="room-price"><?php echo format_price($room['price_per_night']); ?> <small>/ night</small></p>
                        <a class="button" href="booking.php?room_id=<?php echo (int)$room['room_id']; ?>&check_in=<?php echo urlencode($checkIn); ?>&check_out=<?php echo urlencode($checkOut); ?>&guests=<?php echo (int)$guests; ?>">Book now</a>
                    </div>
                </article>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
