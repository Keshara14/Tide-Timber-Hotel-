<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/admin_check.php';
$adminPage = true;
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['room_name'] ?? '');
    $type = trim($_POST['room_type'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $facilities = trim($_POST['facilities'] ?? '');
    $price = filter_input(INPUT_POST, 'price_per_night', FILTER_VALIDATE_FLOAT);
    $maxGuests = filter_input(INPUT_POST, 'max_guests', FILTER_VALIDATE_INT);
    $image = trim($_POST['room_image'] ?? '');
    $status = $_POST['room_status'] ?? '';
    if ($name === '' || $type === '' || $description === '' || $facilities === '' || $image === '') $errors[] = 'Please complete all text fields.';
    if ($price === false || $price <= 0) $errors[] = 'Enter a valid price.';
    if ($maxGuests === false || $maxGuests < 1) $errors[] = 'Enter a valid guest capacity.';
    if (!in_array($status, ['active', 'inactive'], true)) $errors[] = 'Select a valid room status.';
    if (!$errors) {
        $insert = $pdo->prepare(
            'INSERT INTO rooms (room_name, room_type, description, facilities, price_per_night, max_guests, room_image, room_status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $insert->execute([$name, $type, $description, $facilities, $price, $maxGuests, $image, $status]);
        set_flash('Room added successfully.');
        redirect('rooms.php');
    }
}
$pageTitle = 'Add room';
require __DIR__ . '/../includes/header.php';
?>
<section class="section admin-heading"><p class="eyebrow">Administration</p><h1>Add a room</h1></section>
<section class="section">
    <div class="admin-form-card">
        <?php foreach ($errors as $error): ?><div class="alert alert-error"><?php echo e($error); ?></div><?php endforeach; ?>
        <form class="form-grid" method="post">
            <label>Room name<input type="text" name="room_name" value="<?php echo e($_POST['room_name'] ?? ''); ?>" required></label>
            <label>Room type<input type="text" name="room_type" value="<?php echo e($_POST['room_type'] ?? ''); ?>" required></label>
            <label class="wide">Description<textarea name="description" rows="5" required><?php echo e($_POST['description'] ?? ''); ?></textarea></label>
            <label class="wide">Facilities (separate with |)<input type="text" name="facilities" value="<?php echo e($_POST['facilities'] ?? ''); ?>" required></label>
            <label>Price per night (LKR)<input type="number" name="price_per_night" min="1" step="0.01" value="<?php echo e($_POST['price_per_night'] ?? ''); ?>" required></label>
            <label>Maximum guests<input type="number" name="max_guests" min="1" value="<?php echo e($_POST['max_guests'] ?? ''); ?>" required></label>
            <label>Room image filename/path<input type="text" name="room_image" value="<?php echo e($_POST['room_image'] ?? 'assets/images/placeholder.svg'); ?>" required></label>
            <label>Status<select name="room_status"><option value="active">Active</option><option value="inactive">Inactive</option></select></label>
            <button class="button" type="submit">Save room</button>
        </form>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
