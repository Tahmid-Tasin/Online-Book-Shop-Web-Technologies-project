<?php 
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'admin_nav.php'; 

// Force the database connection to load right here so $conn is guaranteed to exist
require_once '../config/database.php';

// Helper function to safely execute queries and avoid fatal errors
function safe_query_value($conn, $query) {
    // Check if $conn is actually valid before running the query
    if (!$conn) {
        return 0;
    }
    
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            // Get the first column's value safely
            $value = array_values($row)[0];
            return $value ? $value : 0;
        }
    }
    return 0;
}

// Fetch stats safely
$books   = safe_query_value($conn, "SELECT COUNT(*) FROM books");
$users   = safe_query_value($conn, "SELECT COUNT(*) FROM users WHERE role='customer'");
$orders  = safe_query_value($conn, "SELECT COUNT(*) FROM orders");
$revenue = safe_query_value($conn, "SELECT SUM(total_amount) FROM orders WHERE status='delivered'");
?>

<div class="page-header">
    <h1>Admin Dashboard</h1>
    <p>Overview of bookstore operations.</p>
</div>

<div class="dashboard-container">
    <div class="card">
        <h3>Total Books</h3>
        <h2><?php echo intval($books); ?></h2>
    </div>
    <div class="card">
        <h3>Total Customers</h3>
        <h2><?php echo intval($users); ?></h2>
    </div>
    <div class="card">
        <h3>Total Orders</h3>
        <h2><?php echo intval($orders); ?></h2>
    </div>
    <div class="card">
        <h3>Total Revenue (Delivered)</h3>
        <h2><?php echo number_format((float)$revenue, 2); ?> Tk</h2>
    </div>
</div>

</body>
</html>