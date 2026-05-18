<?php
class Book {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllBooks() {
        $query = "SELECT * FROM books ORDER BY id DESC";
        $result = mysqli_query($this->conn, $query);
        $books = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) { 
                $books[] = $row; 
            }
        }
        return $books;
    }

    public function getCategories() {
        $query = "SELECT * FROM categories ORDER BY name ASC";
        $result = mysqli_query($this->conn, $query);
        $cats = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) { 
                $cats[] = $row; 
            }
        }
        return $cats;
    }

    public function getBookById($id) {
        $id = intval($id);
        $stmt = mysqli_prepare($this->conn, "SELECT * FROM books WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    }

    public function createBook($title, $author, $desc, $price, $stock, $catId, $image) {
        $stmt = mysqli_prepare($this->conn, "INSERT INTO books (title, author, description, price, stock, category_id, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssdiis", $title, $author, $desc, $price, $stock, $catId, $image);
        $res = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $res;
    }

    public function updateBook($id, $title, $author, $desc, $price, $stock, $catId, $image) {
        $query = "UPDATE books SET title = ?, author = ?, description = ?, price = ?, stock = ?, category_id = ?, image_path = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssdiisi", $title, $author, $desc, $price, $stock, $catId, $image, $id);
            $res = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        }
        return false;
    }

    public function deleteBook($id) {
        $id = intval($id);
        $book = $this->getBookById($id);
        if ($book && $book['image_path']) {
            $path = dirname(__DIR__, 2) . "/public/uploads/books/" . $book['image_path'];
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $stmt = mysqli_prepare($this->conn, "DELETE FROM books WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        $res = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $res;
    }
}
?>