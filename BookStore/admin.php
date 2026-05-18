<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// DEVELOPER BACKDOOR: Force admin session login automatically
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['role'] = 'admin';
    $_SESSION['username'] = 'Developer_Tanmoy';
}

// 1. ABSOLUTE PATH: Pull exact database file from config folder
require_once __DIR__ . '/config/database.php'; 

if (!$conn) {
    die("Database Connection from config/database.php failed.");
}

// Global Core Front-Controller Router
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'admin';
$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

if ($controller === 'admin') {
    require_once __DIR__ . '/app/controllers/AdminController.php';
    $controllerObj = new AdminController($conn);
    
    if (method_exists($controllerObj, $action)) {
        $controllerObj->$action();
    } else {
        die("Routing Error: Requested action does not exist.");
    }
} else {
    die("Routing Error: Main module controller not found.");
}
?>