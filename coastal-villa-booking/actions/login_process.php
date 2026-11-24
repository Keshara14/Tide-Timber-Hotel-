<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../login.php');
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    set_flash('Incorrect email or password.', 'error');
    redirect('../login.php');
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int)$user['user_id'];
$_SESSION['name'] = $user['name'];
$_SESSION['role'] = $user['role'];

if ($user['role'] === 'admin') {
    redirect('../admin/dashboard.php');
}
redirect('../my-bookings.php');
