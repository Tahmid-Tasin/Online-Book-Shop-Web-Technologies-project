<?php
require_once 'admin_check.php';
require_once '../config/database.php';

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM books WHERE id=$id");
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];

    $query = "UPDATE books SET title='$title', author='$author', price='$price' WHERE id=$id";
    mysqli_query($conn, $query);

    echo "Book Updated Successfully";
}
?>

<h1>Edit Book</h1>

<form method="post">
    Title: <input type="text" name="title" value="<?php echo $row['title']; ?>"><br><br>
    Author: <input type="text" name="author" value="<?php echo $row['author']; ?>"><br><br>
    Price: <input type="text" name="price" value="<?php echo $row['price']; ?>"><br><br>

    <input type="submit" name="update" value="Update Book">
</form>

<br>
<a href="books.php">Back</a>