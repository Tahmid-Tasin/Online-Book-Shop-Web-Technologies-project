<?php
session_start();

if(!isset($_SESSION['user_id'])){
    die("Please login first");
}

include "../config/database.php";

$sql = "SELECT * FROM books";
$result = mysqli_query($conn,$sql);
?>

<html lang="en">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Big+Shoulders:opsz,wght@10..72,100..900&family=Bitcount+Prop+Double+Ink:wght@100..900&family=Boldonse&family=Changa+One:ital@0;1&family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&family=Oswald:wght@200..700&family=Playwrite+IE:wght@100..400&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <title>Book Store</title>
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
        <a href="../cart/history.php" id="aboutus" class="aco"><ion-icon name="information-outline"></ion-icon></a>
        <span href="" id="contactus" class="aco"><ion-icon name="call-outline"></ion-icon></span>
        <span href="" id="location" class="aco"><ion-icon name="location-outline"></ion-icon></span>
        <img src="../truck.png" id="truck">
        <a href="../Admin1-Auth/logout.php" id="login" class="aco">Logout</a>
    </nav>

<div class="slider">
    <div class="slides">

        <img src="../posters/poster1.jpeg" alt="" id="p1" class="posters">
        <img src="../posters/poster2.jpeg" alt="" id="p2" class="posters">
        <img src="../posters/poster3.jpeg" alt="" id="p3" class="posters">
        <img src="../posters/poster4.jpeg" alt="" id="p4" class="posters">
        <img src="../posters/poster5.jpeg" alt="" id="p5" class="posters">
        <img src="../posters/poster6.jpeg" alt="" id="p6" class="posters">

        <img src="../posters/poster1.jpeg" class="posters">
        <img src="../posters/poster2.jpeg" class="posters">
        <img src="../posters/poster3.jpeg" class="posters">
        <img src="../posters/poster4.jpeg" class="posters">
        <img src="../posters/poster5.jpeg" class="posters">
        <img src="../posters/poster6.jpeg" class="posters">

    </div>
</div>

<div class="container" id="bookContainer">
<?php while($row=mysqli_fetch_assoc($result)){ ?>
<div class="card">
    <img src="images/<?php echo htmlspecialchars($row['image_path']); ?>">
    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
    <p><?php echo htmlspecialchars($row['author']); ?></p>
    <p><?php echo $row['price']; ?> Tk</p>
    <a href="./book_details.php?id=<?php echo $row['id']; ?>">View Details</a>
</div>
<?php } ?>
</div>

<div class="locationframe">
    <button id="locationclose"><ion-icon name="close-outline"></ion-icon></button>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3649.900859444374!2d90.4274063!3d23.822124199999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c711d13bbec7%3A0xc47f7c3e8e2263f2!2sAmerican%20International%20University%20-%20Bangladesh%20(AIUB)!5e0!3m2!1sen!2sbd!4v1778690348915!5m2!1sen!2sbd" width="450" height="500"  allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        <p id="loctext">Shop Location:American International University - Bangladesh <br>
             (AIUB) 408, RCCG+VX3, 1 Kuratoli, Dhaka 1229
        </p>
</div>


<script src="../script.js"></script>
</body>
</html>