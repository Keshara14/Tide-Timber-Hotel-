<?php
require_once __DIR__ . '/functions.php';
if (!is_admin()) {
    set_flash('Administrator access is required.', 'error');
    redirect('../login.php');
}
