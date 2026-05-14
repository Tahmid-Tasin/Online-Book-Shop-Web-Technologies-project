<?php
include '../config/database.php';

if(isset($_GET['id'])){

    $id = $_GET['id'];

    $query = "DELETE FROM books WHERE id=$id";

    if(mysqli_query($conn, $query)){
        header("Location: books.php");
        exit();
    }
    else{
        echo "Delete Failed";
    }
}
else{
    echo "No ID Found";
}
?>