<?php
include 'auth_common.php';
$_SESSION = [];
session_destroy();
session_unset();
setcookie('remember_email', '', time() - 3600, '/');
header('Location: login.php');
exit;
?>
