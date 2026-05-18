<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Read: Get users list
    public function getAllUsers() {
        $query = "SELECT * FROM users ORDER BY id DESC";
        $result = mysqli_query($this->conn, $query);
        
        $users = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }
        }
        return $users;
    }

    // Delete: Secure parameterized sub-cascades for clearing out associated rows
    public function deleteCustomer($userId) {
        $userId = intval($userId);

        // 1. Clear cart
        $stmt1 = mysqli_prepare($this->conn, "DELETE FROM cart WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt1, "i", $userId);
        mysqli_stmt_execute($stmt1);

        // 2. Clear ordered items
        $stmt2 = mysqli_prepare($this->conn, "DELETE FROM order_items WHERE order_id IN (SELECT id FROM orders WHERE user_id = ?)");
        mysqli_stmt_bind_param($stmt2, "i", $userId);
        mysqli_stmt_execute($stmt2);

        // 3. Clear payment records
        $stmt3 = mysqli_prepare($this->conn, "DELETE FROM payments WHERE order_id IN (SELECT id FROM orders WHERE user_id = ?)");
        mysqli_stmt_bind_param($stmt3, "i", $userId);
        mysqli_stmt_execute($stmt3);

        // 4. Clear orders
        $stmt4 = mysqli_prepare($this->conn, "DELETE FROM orders WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt4, "i", $userId);
        mysqli_stmt_execute($stmt4);

        // 5. Delete final account profile
        $stmt5 = mysqli_prepare($this->conn, "DELETE FROM users WHERE id = ? AND role = 'customer'");
        mysqli_stmt_bind_param($stmt5, "i", $userId);
        return mysqli_stmt_execute($stmt5);
    }
}
?>