<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
$baseUrl = isset($adminPage) && $adminPage
    ? rtrim(str_replace('/admin', '', $scriptDir), '/')
    : $scriptDir;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle ?? 'Tide & Timber'); ?> | Tide & Timber</title>
    <meta name="description" content="Boutique coastal villa accommodation in Weligama, Sri Lanka.">
    <link rel="stylesheet" href="<?php echo e($baseUrl); ?>/assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="nav-wrap">
        <a class="brand" href="<?php echo e($baseUrl); ?>/index.php" aria-label="Tide and Timber home">
            <span class="brand-mark">T&amp;T</span>
            <span><strong>Tide &amp; Timber</strong><small>Weligama, Sri Lanka</small></span>
        </a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-nav">Menu</button>
        <nav id="main-nav" class="main-nav">
            <?php if (!empty($adminPage) && is_admin()) { ?>
                <a href="<?php echo e($baseUrl); ?>/admin/dashboard.php">Dashboard</a>
                <a href="<?php echo e($baseUrl); ?>/admin/rooms.php">Rooms</a>
                <a href="<?php echo e($baseUrl); ?>/admin/bookings.php">Bookings</a>
            <?php } else { ?>
                <a href="<?php echo e($baseUrl); ?>/index.php">Home</a>
                <a href="<?php echo e($baseUrl); ?>/rooms.php">Rooms</a>
                <a href="<?php echo e($baseUrl); ?>/index.php#villa">Our Villa</a>
                <a href="<?php echo e($baseUrl); ?>/index.php#gallery">Gallery</a>
                <a href="<?php echo e($baseUrl); ?>/index.php#contact">Contact</a>
                <?php if (is_logged_in()) { ?>
                    <a href="<?php echo e($baseUrl); ?>/my-bookings.php">My Bookings</a>
                    <?php if (is_admin()) { ?><a href="<?php echo e($baseUrl); ?>/admin/dashboard.php">Admin</a><?php } ?>
                <?php } ?>
            <?php } ?>
        </nav>
        <div class="nav-actions">
            <?php if (is_logged_in()) { ?>
                <span class="account-name"><?php echo e($_SESSION['name']); ?></span>
                <a class="text-link" href="<?php echo e($baseUrl); ?>/logout.php">Logout</a>
            <?php } else { ?>
                <a class="text-link" href="<?php echo e($baseUrl); ?>/login.php">Login</a>
                <a class="button button-small" href="<?php echo e($baseUrl); ?>/register.php">Register</a>
            <?php } ?>
            <?php if (empty($adminPage)) { ?>
                <a class="button button-small" href="<?php echo e($baseUrl); ?>/availability.php">Book Now</a>
            <?php } ?>
        </div>
    </div>
</header>
<main>
