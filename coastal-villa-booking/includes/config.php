<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'coastal_villa_db');
define('DB_USER', 'root');
define('DB_PASS', '');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Colombo');
