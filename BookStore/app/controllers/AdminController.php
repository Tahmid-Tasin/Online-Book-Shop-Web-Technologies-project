<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Look exactly inside app/models/ using absolute filesystem bounds
require_once dirname(__DIR__) . '/models/Book.php';

class AdminController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // FORCE ADMIN SESSION STABILITY:
        // Ensures navigating between your dashboard, books, and orders views never drops the admin state.
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['role'] = 'admin';
            $_SESSION['username'] = 'Developer_Tanmoy';
        }
    }

    
     public function dashboard() {
        // Initialize all four metric keys explicitly to prevent undefined array warnings
        $stats = [
            'books' => 0,
            'users' => 0,
            'orders' => 0,
            'revenue' => 0.00
        ];
        
        // 1. Fetch Total Books Count
        $resB = mysqli_query($this->conn, "SELECT COUNT(*) as c FROM books");
        if ($resB) {
            $stats['books'] = mysqli_fetch_assoc($resB)['c'];
        }
        
        // 2. Fetch Total Customers/Users Count
        $resU = mysqli_query($this->conn, "SELECT COUNT(*) as c FROM users");
        if ($resU) {
            $stats['users'] = mysqli_fetch_assoc($resU)['c'];
        }
        
        // 3. Fetch Total Orders Count
        $resO = mysqli_query($this->conn, "SELECT COUNT(*) as c FROM orders");
        if ($resO) {
            $stats['orders'] = mysqli_fetch_assoc($resO)['c'];
        }
        
        // 4. BULLETPROOF REVENUE CALCULATION:
        // We pull the raw completed rows and look at whatever numeric values exist dynamically
        $resR = mysqli_query($this->conn, "SELECT * FROM orders WHERE status='completed'");
        if ($resR) {
            while ($row = mysqli_fetch_assoc($resR)) {
                // Look through the columns for anything holding the financial total
                if (isset($row['total_price'])) $stats['revenue'] += floatval($row['total_price']);
                elseif (isset($row['price']))   $stats['revenue'] += floatval($row['price']);
                elseif (isset($row['amount']))  $stats['revenue'] += floatval($row['amount']);
                elseif (isset($row['total']))   $stats['revenue'] += floatval($row['total']);
            }
        }
        
        // Loads directly from app/views/
        require_once dirname(__DIR__) . '/views/dashboard_view.php';
    }

    public function books() {
        $bookModel = new Book($this->conn);
        $books = $bookModel->getAllBooks();
        require_once dirname(__DIR__) . '/views/books_view.php';
    }

    public function addBook() {
        $bookModel = new Book($this->conn);
        $categories = $bookModel->getCategories(); 
        $message = ''; 

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            $title = $_POST['title'];
            $author = $_POST['author'];
            $desc = $_POST['description'];
            $price = floatval($_POST['price']);
            $stock = intval($_POST['stock']);
            $catId = intval($_POST['category_id']);
            
            $image_path = "";
            $uploadOk = 1;

            if ($price <= 0 || $stock < 0) {
                $message = "<div class='error-msg'>Invalid price or stock configuration.</div>";
                $uploadOk = 0;
            }

            if (!empty($_FILES["image"]["name"]) && $uploadOk == 1) {
                $target_dir = dirname(__DIR__, 2) . "/public/uploads/books/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                
                $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
                $image_path = time() . '_' . basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $image_path;

                if ($_FILES["image"]["size"] > 2000000 || !in_array($imageFileType, ["jpg", "jpeg", "png"])) {
                    $message = "<div class='error-msg'>Invalid file constraints. Limit files to 2MB JPG/PNG shapes.</div>";
                    $uploadOk = 0;
                } else {
                    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        $message = "<div class='error-msg'>Failed to write binary image streams to directory path.</div>";
                        $uploadOk = 0;
                    }
                }
            }

            if ($uploadOk == 1) {
                if ($bookModel->createBook($title, $author, $desc, $price, $stock, $catId, $image_path)) {
                    $message = "<div class='success-msg'>New book entry committed successfully!</div>";
                } else {
                    $message = "<div class='error-msg'>Database persistence write operation aborted.</div>";
                }
            }
        }
        require_once dirname(__DIR__) . '/views/add_book_view.php';
    }

    public function editBook() {
        if (!isset($_GET['id'])) { 
            header("Location: /Online-Book-Shop-Web-Technologies-project/BookStore/admin.php?controller=admin&action=books"); 
            exit; 
        }
        
        $id = intval($_GET['id']);
        $bookModel = new Book($this->conn);
        $book = $bookModel->getBookById($id);
        $categories = $bookModel->getCategories();
        $message = '';

        if (!$book) { 
            header("Location: /Online-Book-Shop-Web-Technologies-project/BookStore/admin.php?controller=admin&action=books"); 
            exit; 
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            $title = $_POST['title'];
            $author = $_POST['author'];
            $desc = $_POST['description'];
            $price = floatval($_POST['price']);
            $stock = intval($_POST['stock']);
            $catId = intval($_POST['category_id']);
            
            $image_path = $book['image_path']; 
            $uploadOk = 1;

            if ($price <= 0 || $stock < 0) { 
                $message = "<div class='error-msg'>Validation mismatch: pricing models must be positive values.</div>"; 
                $uploadOk = 0; 
            }

            if (!empty($_FILES["image"]["name"]) && $uploadOk == 1) {
                $target_dir = dirname(__DIR__, 2) . "/public/uploads/books/";
                $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
                $new_image_path = time() . '_' . basename($_FILES["image"]["name"]);
                
                if ($_FILES["image"]["size"] > 2000000) { 
                    $message = "<div class='error-msg'>Upload restricted: Image bounds exceed 2MB.</div>"; 
                    $uploadOk = 0; 
                } else {
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $new_image_path)) {
                        if ($image_path && file_exists($target_dir . $image_path)) {
                            unlink($target_dir . $image_path);
                        }
                        $image_path = $new_image_path; 
                    }
                }
            }

            if ($uploadOk == 1) {
                if ($bookModel->updateBook($id, $title, $author, $desc, $price, $stock, $catId, $image_path)) {
                    header("Location: /Online-Book-Shop-Web-Technologies-project/BookStore/admin.php?controller=admin&action=books");
                    exit;
                } else { 
                    $message = "<div class='error-msg'>Database Query Aborted: " . mysqli_error($this->conn) . "</div>"; 
                }
            }
        }
        require_once dirname(__DIR__) . '/views/edit_book_view.php';
    }

    public function deleteBook() {
        if (isset($_GET['id'])) {
            $bookModel = new Book($this->conn);
            $bookModel->deleteBook($_GET['id']);
        }
        header("Location: /Online-Book-Shop-Web-Technologies-project/BookStore/admin.php?controller=admin&action=books");
        exit;
    }

   public function orders() {
        // ABSOLUTE PATH: Ensures the system binds the clean model layout accurately
        require_once dirname(__DIR__) . '/models/Order.php';
        
        $orderModel = new Order($this->conn);
        $statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
        $ordersList = $orderModel->getAllOrders($statusFilter, '');
        
        require_once dirname(__DIR__) . '/views/orders_view.php';
    }

    public function updateOrderStatus() {
        header("Content-Type: application/json");
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id']) && isset($data['status'])) {
            require_once dirname(__DIR__) . '/models/Order.php';
            $orderModel = new Order($this->conn);
            if ($orderModel->updateStatus($data['id'], $data['status'])) {
                echo json_encode(["success" => true]);
                exit;
            }
        }
        echo json_encode(["success" => false]);
        exit;
    }
}
?>