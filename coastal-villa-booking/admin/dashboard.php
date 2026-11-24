<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/admin_check.php';
$adminPage = true;
$pageTitle = 'Admin dashboard';
require __DIR__ . '/../includes/header.php';

$totalRooms = $pdo->query('SELECT COUNT(*) FROM rooms')->fetchColumn();
$activeRooms = $pdo->query("SELECT COUNT(*) FROM rooms WHERE room_status = 'active'")->fetchColumn();
$totalBookings = $pdo->query('SELECT COUNT(*) FROM bookings')->fetchColumn();
$pending = $pdo->query("SELECT COUNT(*) FROM bookings WHERE booking_status = 'Pending'")->fetchColumn();
$confirmed = $pdo->query("SELECT COUNT(*) FROM bookings WHERE booking_status = 'Confirmed'")->fetchColumn();
$cancelled = $pdo->query("SELECT COUNT(*) FROM bookings WHERE booking_status = 'Cancelled'")->fetchColumn();
$recent = $pdo->query(
    'SELECT b.*, u.name, r.room_name FROM bookings b
     JOIN users u ON u.user_id = b.user_id
     JOIN rooms r ON r.room_id = b.room_id
     ORDER BY b.booking_date DESC LIMIT 8'
)->fetchAll();
?>
<section class="section admin-heading">
    <p class="eyebrow">Administration</p>
    <h1>Villa overview</h1>
    <?php display_flash(); ?>
</section>
<section class="section">
    <div class="stat-grid">
        <div class="stat-card"><span>Total rooms</span><strong><?php echo $totalRooms; ?></strong></div>
        <div class="stat-card"><span>Active rooms</span><strong><?php echo $activeRooms; ?></strong></div>
        <div class="stat-card"><span>Total bookings</span><strong><?php echo $totalBookings; ?></strong></div>
        <div class="stat-card"><span>Pending</span><strong><?php echo $pending; ?></strong></div>
        <div class="stat-card"><span>Confirmed</span><strong><?php echo $confirmed; ?></strong></div>
        <div class="stat-card"><span>Cancelled</span><strong><?php echo $cancelled; ?></strong></div>
    </div>
    <div class="admin-table-card">
        <h2>Recent bookings</h2>
        <div class="table-wrap">
            <table>
                <thead><tr><th>ID</th><th>Guest</th><th>Room</th><th>Dates</th><th>Amount</th><th>Status</th></tr></thead>
                <tbody>
                <?php foreach ($recent as $booking): ?>
                    <tr>
                        <td>#<?php echo (int)$booking['booking_id']; ?></td>
                        <td><?php echo e($booking['name']); ?></td>
                        <td><?php echo e($booking['room_name']); ?></td>
                        <td><?php echo e($booking['check_in']); ?> → <?php echo e($booking['check_out']); ?></td>
                        <td><?php echo format_price($booking['total_amount']); ?></td>
                        <td><span class="status status-<?php echo strtolower($booking['booking_status']); ?>"><?php echo e($booking['booking_status']); ?></span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
