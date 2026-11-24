<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth_check.php';
$pageTitle = 'My bookings';
require __DIR__ . '/includes/header.php';

$stmt = $pdo->prepare(
    'SELECT b.*, r.room_name FROM bookings b
     JOIN rooms r ON r.room_id = b.room_id
     WHERE b.user_id = ? ORDER BY b.booking_date DESC'
);
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll();
?>
<section class="section page-heading">
    <p class="eyebrow">My account</p>
    <h1>Your bookings</h1>
    <?php display_flash(); ?>
</section>
<section class="section">
    <?php if (!$bookings): ?>
        <p class="empty-state">You have no bookings yet. <a href="availability.php">Plan your stay</a>.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>ID</th><th>Room</th><th>Dates</th><th>Guests</th><th>Total</th><th>Booked</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td>#<?php echo (int)$booking['booking_id']; ?></td>
                        <td><?php echo e($booking['room_name']); ?></td>
                        <td><?php echo e($booking['check_in']); ?> → <?php echo e($booking['check_out']); ?></td>
                        <td><?php echo (int)$booking['guests']; ?></td>
                        <td><?php echo format_price($booking['total_amount']); ?></td>
                        <td><?php echo e(date('d M Y', strtotime($booking['booking_date']))); ?></td>
                        <td><span class="status status-<?php echo strtolower($booking['booking_status']); ?>"><?php echo e($booking['booking_status']); ?></span></td>
                        <td>
                            <?php if ($booking['booking_status'] === 'Pending'): ?>
                                <form action="actions/cancel_booking.php" method="post" onsubmit="return confirm('Cancel this Pending booking?');">
                                    <input type="hidden" name="booking_id" value="<?php echo (int)$booking['booking_id']; ?>">
                                    <button class="table-action danger" type="submit">Cancel</button>
                                </form>
                            <?php else: ?>—<?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
