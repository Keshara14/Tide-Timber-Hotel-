<?php
require_once __DIR__ . '/includes/db.php';
$pageTitle = 'A coastal stay';
require __DIR__ . '/includes/header.php';
$featured = $pdo->query("SELECT * FROM rooms WHERE room_status = 'active' ORDER BY room_id LIMIT 3")->fetchAll();
?>
<section class="hero">
    <div class="hero-image"></div>
    <div class="hero-content">
        <p class="eyebrow light">Weligama, Sri Lanka</p>
        <h1>Where the coast feels like home</h1>
        <p>Rest beneath tropical skies, steps from the Indian Ocean, in a villa shaped for unhurried island days.</p>
        <a class="button button-light" href="availability.php">Find your room</a>
    </div>
</section>

<section class="intro section" id="villa">
    <div>
        <p class="eyebrow">Our villa</p>
        <h2>A quieter way to stay on the south coast</h2>
    </div>
    <div class="intro-copy">
        <p>Tide &amp; Timber brings local stone, warm timber and generous coastal light together in a small family-run villa.</p>
        <div class="facility-row">
            <span>Ocean frontage</span><span>Poolside garden</span><span>Local breakfast</span><span>Easy surf access</span>
        </div>
    </div>
</section>

<section class="section featured">
    <div class="section-heading">
        <div><p class="eyebrow">Stay your way</p><h2>Featured accommodation</h2></div>
        <a class="text-link" href="rooms.php">View all rooms →</a>
    </div>
    <div class="room-grid">
        <?php foreach ($featured as $room): ?>
            <article class="room-card">
                <a class="room-image" href="room-details.php?id=<?php echo (int)$room['room_id']; ?>">
                    <img src="<?php echo e($room['room_image']); ?>" alt="<?php echo e($room['room_name']); ?>">
                </a>
                <div class="room-card-body">
                    <p class="room-type"><?php echo e($room['room_type']); ?></p>
                    <h3><a href="room-details.php?id=<?php echo (int)$room['room_id']; ?>"><?php echo e($room['room_name']); ?></a></h3>
                    <p class="room-price"><?php echo format_price($room['price_per_night']); ?> <small>/ night</small></p>
                    <a class="button button-outline" href="room-details.php?id=<?php echo (int)$room['room_id']; ?>">View details</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section gallery" id="gallery">
    <p class="eyebrow">Life at Tide &amp; Timber</p>
    <h2>Made for sunlit hours</h2>
    <div class="gallery-grid">
        <div class="gallery-tile gallery-a"></div>
        <div class="gallery-tile gallery-b"></div>
        <div class="gallery-tile gallery-c"></div>
    </div>
</section>

<section class="contact-strip" id="contact">
    <div><p class="eyebrow light">Come find us</p><h2>Weligama, Southern Sri Lanka</h2></div>
    <a class="button button-light" href="availability.php">Check availability</a>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
