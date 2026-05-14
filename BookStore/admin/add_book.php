<?php
include '../config/database.php';
require_once '../config/database.php';

if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];

    if($title != "" && $author != "" && $price != ""){
        $query = "INSERT INTO books(title, author, price) VALUES('$title','$author','$price')";
        mysqli_query($conn, $query);
        echo "Book Added Successfully";
    } else {
        echo "Please fill all fields";
    }
}
?>

<h1>Add Book</h1>

<form method="post">
    Title: <input type="text" name="title"><br><br>
    Author: <input type="text" name="author"><br><br>
    Price: <input type="text" name="price"><br><br>

    <input type="submit" name="submit" value="Add Book">
</form>

<br>
<a href="books.php">Back to Books</a>