<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/admin_check.php';
$adminPage = true;
$pageTitle = 'Manage bookings';
require __DIR__ . '/../includes/header.php';

$status = $_GET['status'] ?? '';
$sql = 'SELECT b.*, u.name, u.email, r.room_name FROM bookings b
        JOIN users u ON u.user_id=b.user_id JOIN rooms r ON r.room_id=b.room_id';
$params = [];
if (in_array($status, ['Pending','Confirmed','Cancelled'], true)) {
    $sql .= ' WHERE b.booking_status = ?';
    $params[] = $status;
}
$sql .= ' ORDER BY b.booking_date DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$bookings = $stmt->fetchAll();
?>
<section class="section admin-heading">
    <p class="eyebrow">Administration</p>
    <h1>Manage bookings</h1>
    <?php display_flash(); ?>
</section>
<section class="section">
    <form class="filter-bar compact" method="get">
        <label>Booking status
            <select name="status">
                <option value="">All</option>
                <?php foreach (['Pending','Confirmed','Cancelled'] as $item): ?>
                    <option value="<?php echo $item; ?>" <?php echo $status === $item?'selected':''; ?>><?php echo $item; ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button class="button" type="submit">Filter</button>
    </form>
    <div class="table-wrap admin-table-card">
        <table>
            <thead><tr><th>ID</th><th>Guest</th><th>Room</th><th>Dates</th><th>Guests</th><th>Total</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td>#<?php echo (int)$booking['booking_id']; ?></td>
                    <td><?php echo e($booking['name']); ?><br><small><?php echo e($booking['email']); ?></small></td>
                    <td><?php echo e($booking['room_name']); ?></td>
                    <td><?php echo e($booking['check_in']); ?> → <?php echo e($booking['check_out']); ?></td>
                    <td><?php echo (int)$booking['guests']; ?></td>
                    <td><?php echo format_price($booking['total_amount']); ?></td>
                    <td><span class="status status-<?php echo strtolower($booking['booking_status']); ?>"><?php echo e($booking['booking_status']); ?></span></td>
                    <td>
                        <?php if ($booking['booking_status'] === 'Pending'): ?>
                            <form class="inline-status" action="../actions/update_booking_status.php" method="post">
                                <input type="hidden" name="booking_id" value="<?php echo (int)$booking['booking_id']; ?>">
                                <button class="table-action confirm" name="status" value="Confirmed" type="submit">Confirm</button>
                                <button class="table-action danger" name="status" value="Cancelled" type="submit">Cancel</button>
                            </form>
                        <?php elseif ($booking['booking_status'] === 'Confirmed'): ?>
                            <form action="../actions/update_booking_status.php" method="post" onsubmit="return confirm('Cancel this confirmed booking?');">
                                <input type="hidden" name="booking_id" value="<?php echo (int)$booking['booking_id']; ?>">
                                <button class="table-action danger" name="status" value="Cancelled" type="submit">Cancel</button>
                            </form>
                        <?php else: ?>—<?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
