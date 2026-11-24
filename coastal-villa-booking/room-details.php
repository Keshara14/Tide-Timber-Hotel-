<?php
require_once __DIR__ . '/includes/db.php';
$roomId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stmt = $pdo->prepare('SELECT * FROM rooms WHERE room_id = ?');
$stmt->execute([$roomId]);
$room = $stmt->fetch();
if (!$room) {
    http_response_code(404);
}
$pageTitle = $room ? $room['room_name'] : 'Room not found';
require __DIR__ . '/includes/header.php';
if (!$room) {
    echo '<section class="section"><h1>Room not found</h1><p><a href="rooms.php">Return to rooms</a></p></section>';
} else {
?>
<section class="room-detail section">
    <div class="detail-image"><img src="<?php echo e($room['room_image']); ?>" alt="<?php echo e($room['room_name']); ?>"></div>
    <div class="detail-copy">
        <p class="eyebrow"><?php echo e($room['room_type']); ?></p>
        <h1><?php echo e($room['room_name']); ?></h1>
        <p><?php echo nl2br(e($room['description'])); ?></p>
        <h3>Facilities</h3>
        <ul class="facility-list">
            <?php foreach (explode('|', $room['facilities']) as $facility): ?>
                <li><?php echo e(trim($facility)); ?></li>
            <?php endforeach; ?>
        </ul>
        <p class="room-price"><?php echo format_price($room['price_per_night']); ?> <small>/ night</small></p>
        <p class="capacity">Maximum <?php echo (int)$room['max_guests']; ?> guests</p>
        <?php if ($room['room_status'] === 'active'): ?>
            <a class="button" href="availability.php?room_id=<?php echo (int)$room['room_id']; ?>">Check availability</a>
        <?php else: ?>
            <p class="empty-state">This room is currently unavailable.</p>
        <?php endif; ?>
    </div>
</section>
<?php } require __DIR__ . '/includes/footer.php'; ?>
