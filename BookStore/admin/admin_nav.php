<?php
// TURN ON ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Safely start the session only if one isn't already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Admin Gate - Protects the folder
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../Admin1-Auth/login.php");
    exit;
}

// Force the database connection to load here
require_once '../config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <style>
        /* =========================
           GLOBAL STYLES
        ========================= */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #F5F1E8; 
            color: #1F3B2D; 
        }

        /* =========================
           TOP NAVIGATION BAR & BUTTONS
        ========================= */
        .admin-nav {
            background: #1F3B2D; 
            padding: 15px 20px;
            display: flex;
            gap: 15px;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .admin-nav h2 {
            color: #FFFDF8;
            margin: 0;
            margin-right: 20px;
            font-size: 22px;
        }

        .nav-btn {
            background: #B7E778; 
            color: #1F3B2D;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 16px;
            border-radius: 6px;
            transition: 0.3s;
            display: inline-block;
            border: 2px solid transparent;
        }

        .nav-btn:hover {
            background: #FFFDF8; 
            transform: scale(1.05);
            border: 2px solid #B7E778;
        }

        .logout-btn {
            margin-left: auto;
            background: #dc3545; 
            color: white;
        }

        .logout-btn:hover {
            background: #c82333;
            color: white;
            border-color: #c82333;
        }

        /* =========================
           DASHBOARD CARDS
        ========================= */
        .dashboard-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            padding: 20px;
        }

        .card {
            padding: 20px;
            border-radius: 12px;
            width: 220px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            color: #1F3B2D;
            background: #FFFDF8;
            border-left: 6px solid #B7E778;
        }
        
        .card h3 { margin: 0; font-size: 16px; }
        .card h2 { margin-top: 10px; font-size: 28px; }

        /* =========================
           TABLES
        ========================= */
        table {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #FFFDF8;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        table th {
            background: #1F3B2D;
            color: #FFFDF8;
            padding: 12px;
            text-align: left;
        }

        table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2ddcf;
        }

        table tr:hover { background: #eef8d8; } 

        /* =========================
           GENERAL BUTTONS (CRUD)
        ========================= */
        button, .btn, input[type="submit"] {
            background: #B7E778;
            border: none;
            color: #1F3B2D;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        button:hover, .btn:hover, input[type="submit"]:hover {
            background: #1F3B2D;
            color: #FFFDF8;
        }

        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; }

        /* =========================
           FORMS
        ========================= */
        form {
            background: #FFFDF8;
            padding: 25px;
            width: 450px;
            margin: 20px;
            border-radius: 10px;
            border: 1px solid #e2ddcf;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #B7E778;
            box-shadow: 0 0 5px rgba(183, 231, 120, 0.5);
        }

        form label { display: block; margin-top: 10px; font-weight: bold; color: #1F3B2D; }

        /* =========================
           HEADERS & MESSAGES
        ========================= */
        .page-header { padding: 20px; padding-bottom: 0; }
        .page-header h1 { margin-bottom: 5px; color: #1F3B2D; }
        .page-header p { color: #7A5C3E; margin-top: 0; }
        .success-msg { color: #155724; background-color: #d4edda; border-color: #c3e6cb; padding: 10px 20px; font-weight: bold; border-radius: 5px; margin: 20px; width: fit-content; }
        .error-msg { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; padding: 10px 20px; font-weight: bold; border-radius: 5px; margin: 20px; width: fit-content; }
    </style>
</head>
<body>

    <div class="admin-nav">
        <h2>BookStore Admin</h2>
        <a href="dashboard.php" class="nav-btn">📊 Dashboard</a>
        <a href="books.php" class="nav-btn">📚 Manage Books</a>
        <a href="orders.php" class="nav-btn">🛒 Manage Orders</a>
        <a href="users.php" class="nav-btn">👥 Manage Users</a>
        
        <a href="../Admin1-Auth/logout.php" class="nav-btn logout-btn">🚪 Logout</a>
    </div>