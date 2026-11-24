<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../register.php');
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$contact = trim($_POST['contact_number'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if ($name === '' || $email === '' || $contact === '' || $password === '' || $confirm === '') {
    set_flash('Please complete every registration field.', 'error');
    redirect('../register.php');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('Please enter a valid email address.', 'error');
    redirect('../register.php');
}
if (strlen($password) < 6) {
    set_flash('Password must contain at least 6 characters.', 'error');
    redirect('../register.php');
}
if ($password !== $confirm) {
    set_flash('Passwords do not match.', 'error');
    redirect('../register.php');
}

$check = $pdo->prepare('SELECT user_id FROM users WHERE email = ?');
$check->execute([$email]);
if ($check->fetch()) {
    set_flash('An account with that email already exists.', 'error');
    redirect('../register.php');
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$insert = $pdo->prepare('INSERT INTO users (name, email, contact_number, password, role) VALUES (?, ?, ?, ?, \'guest\')');
if ($insert->execute([$name, $email, $contact, $hash])) {
    set_flash('Registration successful. You can now log in.');
    redirect('../login.php');
}
