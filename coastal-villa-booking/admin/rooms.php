<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/admin_check.php';
$adminPage = true;
$pageTitle = 'Manage rooms';
require __DIR__ . '/../includes/header.php';
$rooms = $pdo->query('SELECT * FROM rooms ORDER BY room_id DESC')->fetchAll();
?>
<section class="section admin-heading">
    <p class="eyebrow">Administration</p>
    <div class="heading-action"><h1>Manage rooms</h1><a class="button" href="add-room.php">Add room</a></div>
    <?php display_flash(); ?>
</section>
<section class="section">
    <div class="table-wrap admin-table-card">
        <table>
            <thead><tr><th>ID</th><th>Room</th><th>Type</th><th>Price/night</th><th>Capacity</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($rooms as $room): ?>
                <tr>
                    <td>#<?php echo (int)$room['room_id']; ?></td>
                    <td><?php echo e($room['room_name']); ?></td>
                    <td><?php echo e($room['room_type']); ?></td>
                    <td><?php echo format_price($room['price_per_night']); ?></td>
                    <td><?php echo (int)$room['max_guests']; ?></td>
                    <td><span class="status status-<?php echo $room['room_status'] === 'active' ? 'confirmed' : 'cancelled'; ?>"><?php echo e(ucfirst($room['room_status'])); ?></span></td>
                    <td class="table-actions">
                        <a class="table-action" href="../room-details.php?id=<?php echo (int)$room['room_id']; ?>">View</a>
                        <a class="table-action" href="edit-room.php?id=<?php echo (int)$room['room_id']; ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
