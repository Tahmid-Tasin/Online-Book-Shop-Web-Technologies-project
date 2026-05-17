<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../config/database.php';

function clean($value) {
    return htmlspecialchars(trim((string)$value), ENT_QUOTES, 'UTF-8');
}

function logged_in() {
    return isset($_SESSION['user_id']);
}

function current_role() {
    return $_SESSION['role'] ?? '';
}

function require_login() {
    if (!logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function upload_profile_picture($file) {
    if (empty($file['name'])) {
        return '';
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        return false;
    }

    $type = mime_content_type($file['tmp_name']);
    if ($type !== 'image/jpeg' && $type !== 'image/png') {
        return false;
    }

    $extension = $type === 'image/png' ? '.png' : '.jpg';
    $fileName = 'profile_' . time() . '_' . rand(1000, 9999) . $extension;
    $target = __DIR__ . '/uploads/profiles/' . $fileName;

    if (move_uploaded_file($file['tmp_name'], $target)) {
        return 'Admin1-Auth/uploads/profiles/' . $fileName;
    }

    return false;
}
?>
