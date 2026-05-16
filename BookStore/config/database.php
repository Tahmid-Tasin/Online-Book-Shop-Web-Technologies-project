<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "online_bookstore";

$conn = mysqli_connect($servername, $username, $password, $database , 3306);

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

?>