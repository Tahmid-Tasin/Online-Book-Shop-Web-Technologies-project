<?php
class Dashboard {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fetches all the statistics safely
    public function getStats() {
        $stats = [
            'books' => 0,
            'users' => 0,
            'orders' => 0,
            'revenue' => 0
        ];

        // Total Books
        $res = mysqli_query($this->conn, "SELECT COUNT(*) as total FROM books");
        if($res && $row = mysqli_fetch_assoc($res)) $stats['books'] = $row['total'];

        // Total Customers
        $res = mysqli_query($this->conn, "SELECT COUNT(*) as total FROM users WHERE role='customer'");
        if($res && $row = mysqli_fetch_assoc($res)) $stats['users'] = $row['total'];

        // Total Orders
        $res = mysqli_query($this->conn, "SELECT COUNT(*) as total FROM orders");
        if($res && $row = mysqli_fetch_assoc($res)) $stats['orders'] = $row['total'];

        // Total Revenue (Only delivered orders)
        $res = mysqli_query($this->conn, "SELECT SUM(total_amount) as total FROM orders WHERE status='delivered'");
        if($res && $row = mysqli_fetch_assoc($res)) $stats['revenue'] = $row['total'] ? $row['total'] : 0;

        return $stats;
    }
}
?>