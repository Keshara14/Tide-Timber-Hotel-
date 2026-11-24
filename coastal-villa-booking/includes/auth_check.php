<?php
require_once __DIR__ . '/functions.php';
if (!is_logged_in()) {
    set_flash('Please log in to continue.', 'error');
    redirect('../login.php');
}
