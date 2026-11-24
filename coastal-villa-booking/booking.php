<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth_check.php';

$roomId = filter_input(INPUT_GET, 'room_id', FILTER_VALIDATE_INT);
$checkIn = $_GET['check_in'] ?? '';
$checkOut = $_GET['check_out'] ?? '';
$guests = filter_input(INPUT_GET, 'guests', FILTER_VALIDATE_INT) ?: 1;
$stmt = $pdo->prepare('SELECT * FROM rooms WHERE room_id = ?');
$stmt->execute([$roomId]);
$room = $stmt->fetch();
$nights = valid_date($checkIn) && valid_date($checkOut) ? nights_between($checkIn, $checkOut) : 0;
$nights = max(0, $nights);
$pageTitle = 'Complete booking';
require __DIR__ . '/includes/header.php';
?>
<section class="section booking-layout">
    <?php if (!$room || $room['room_status'] !== 'active'): ?>
        <div><h1>Room unavailable</h1><a href="rooms.php">Browse rooms</a></div>
    <?php else: ?>
        <div class="booking-summary">
            <img src="<?php echo e($room['room_image']); ?>" alt="<?php echo e($room['room_name']); ?>">
            <p class="room-type"><?php echo e($room['room_type']); ?></p>
            <h1><?php echo e($room['room_name']); ?></h1>
            <p class="room-price"><?php echo format_price($room['price_per_night']); ?> <small>/ night</small></p>
        </div>
        <div class="booking-form-card">
            <?php display_flash(); ?>
            <h2>Your stay</h2>
            <form id="booking-form" class="form-grid" action="actions/booking_process.php" method="post">
                <input type="hidden" name="room_id" value="<?php echo (int)$room['room_id']; ?>">
                <label>Check-in<input id="check-in" type="date" name="check_in" value="<?php echo e($checkIn); ?>" required></label>
                <label>Check-out<input id="check-out" type="date" name="check_out" value="<?php echo e($checkOut); ?>" required></label>
                <label>Number of guests<input id="guest-count" type="number" name="guests" min="1" max="<?php echo (int)$room['max_guests']; ?>" value="<?php echo (int)$guests; ?>" required></label>
                <div class="form-readonly"><span>Guest name</span><strong><?php echo e($_SESSION['name']); ?></strong></div>
                <div class="form-readonly"><span>Nights</span><strong id="nights-count"><?php echo $nights; ?></strong></div>
                <div class="booking-total"><span>Estimated total</span><strong id="booking-total" data-price="<?php echo e($room['price_per_night']); ?>">LKR <?php echo number_format($nights * $room['price_per_night'], 2); ?></strong></div>
                <button class="button" type="submit">Submit booking request</button>
            </form>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
