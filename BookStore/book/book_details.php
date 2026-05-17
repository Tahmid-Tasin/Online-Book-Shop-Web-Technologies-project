
<?php
session_start();

if(!isset($_SESSION['user_id'])){
    die("Please login first");
}

/*
if(!isset($_SESSION['user_id'])){
    $_SESSION['user_id']=1;
}
*/
include "../config/database.php";

$id = intval($_GET['id']);
$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM books WHERE id=?"
);
mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$book = mysqli_fetch_assoc($result);
if(!$book){
    die("Book not found");
}

$sql = "SELECT * FROM books";
$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Big+Shoulders:opsz,wght@10..72,100..900&family=Bitcount+Prop+Double+Ink:wght@100..900&family=Boldonse&family=Changa+One:ital@0;1&family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&family=Oswald:wght@200..700&family=Playwrite+IE:wght@100..400&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <title>Book Details</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

   <nav id="topbar">
        <p id="logo">BookStore.com</p>

        <input type="text" id="searchBox" placeholder="Search your book">
        <select id="filter">
           <option value="title">Title</option>
           <option value="author">Author</option>
        </select>
        <button id="searchbtn" onclick="searchBooks()"><ion-icon name="search-outline"></ion-icon></button>
        <span href="" id="aboutus" class="aco"><ion-icon name="information-outline"></ion-icon></span>
        <span href="" id="contactus" class="aco"><ion-icon name="call-outline"></ion-icon></span>
        <span href="" id="location" class="aco"><ion-icon name="location-outline"></ion-icon></span>
        <img src="truck.png" id="truck">
    </nav>
<br><br>
<br><br>
<br><br>
<a href="../cart/cart.php">View Cart</a>

<div class="card">
    <img src="images/<?php echo htmlspecialchars($book['image_path']); ?>">
    <h1><?php echo htmlspecialchars($book['title']); ?></h1>
    <p>Author: <?php echo htmlspecialchars($book['author']); ?></p>
    <p>Price: <?php echo $book['price']; ?> Tk</p>
    <p>Description: <?php echo htmlspecialchars($book['description']); ?></p>
    <p>
        Stock:
        <?php
        if($book['stock'] > 0){
            echo "Available";
            echo $book['stock'];
        }else{
            echo "Out of Stock";
        }
        ?>
    </p>

    <?php if($book['stock'] > 0){ ?>
        <button onclick="addToCart(<?php echo $book['id']; ?>)">Add To Cart</button>
    <?php } ?>
    <br><br>

</div>
<br><br>

<div class="container" id="bookContainer">
<?php while($row=mysqli_fetch_assoc($result)){ ?>
<div class="card">
    <img src="images/<?php echo htmlspecialchars($row['image_path']); ?>">
    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
    <p><?php echo htmlspecialchars($row['author']); ?></p>
    <p><?php echo $row['price']; ?> Tk</p>
    <a href="../book/book_details.php?id=<?php echo $row['id']; ?>">View Details</a>
</div>
<?php } ?>
</div>

<script src="../script.js"></script>
</body>
</html>