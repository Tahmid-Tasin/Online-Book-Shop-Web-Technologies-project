<?php


session_start();    
if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}
if ($_SESSION['role'] !== 'admin') {
    echo "Access Denied.";
    exit();
}
?>