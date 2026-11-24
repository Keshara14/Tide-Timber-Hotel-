<?php
require_once __DIR__ . '/includes/db.php';
$pageTitle = 'Rooms';
require __DIR__ . '/includes/header.php';

$type = trim($_GET['type'] ?? '');
$maxPrice = filter_input(INPUT_GET, 'max_price', FILTER_VALIDATE_FLOAT);
$sql = "SELECT * FROM rooms WHERE room_status = 'active'";
$params = [];
if ($type !== '') {
    $sql .= ' AND room_type = ?';
    $params[] = $type;
}
if ($maxPrice !== false && $maxPrice !== null) {
    $sql .= ' AND price_per_night <= ?';
    $params[] = $maxPrice;
}
$sql .= ' ORDER BY price_per_night';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rooms = $stmt->fetchAll();
$types = $pdo->query("SELECT DISTINCT room_type FROM rooms WHERE room_status = 'active' ORDER BY room_type")->fetchAll();
?>
<section class="section page-heading">
    <p class="eyebrow">Accommodation</p>
    <h1>Rooms for every kind of getaway</h1>
</section>
<section class="section room-page">
    <form class="filter-bar" method="get">
        <label>Room type
            <select name="type">
                <option value="">All rooms</option>
                <?php foreach ($types as $item): ?>
                    <option value="<?php echo e($item['room_type']); ?>" <?php echo $type === $item['room_type'] ? 'selected' : ''; ?>>
                        <?php echo e($item['room_type']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Maximum price (LKR)<input type="number" name="max_price" min="0" value="<?php echo e($_GET['max_price'] ?? ''); ?>"></label>
        <button class="button" type="submit">Filter</button>
    </form>
    <?php if (!$rooms): ?>
        <p class="empty-state">No rooms match these filters.</p>
    <?php endif; ?>
    <div class="room-grid">
        <?php foreach ($rooms as $room): ?>
            <article class="room-card">
                <a class="room-image" href="room-details.php?id=<?php echo (int)$room['room_id']; ?>">
                    <img src="<?php echo e($room['room_image']); ?>" alt="<?php echo e($room['room_name']); ?>">
                </a>
                <div class="room-card-body">
                    <p class="room-type"><?php echo e($room['room_type']); ?></p>
                    <h3><?php echo e($room['room_name']); ?></h3>
                    <p><?php echo e(mb_strimwidth($room['description'], 0, 120, '…')); ?></p>
                    <p class="room-price"><?php echo format_price($room['price_per_night']); ?> <small>/ night</small></p>
                    <p class="capacity">Sleeps <?php echo (int)$room['max_guests']; ?> guests</p>
                    <div class="card-actions">
                        <a class="button button-outline" href="room-details.php?id=<?php echo (int)$room['room_id']; ?>">View details</a>
                        <a class="button" href="availability.php?room_id=<?php echo (int)$room['room_id']; ?>">Book now</a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
