<?php include 'admin_nav.php'; ?>

<?php
if(isset($_POST['submit'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category_id = intval($_POST['category_id']);
    
    $image_path = "";
    $uploadOk = 1;

    // PHP Validations for Price and Stock
    if($price <= 0) {
        echo "<div class='error-msg'>Price must be greater than 0.</div>";
        $uploadOk = 0;
    }
    if($stock < 0) {
        echo "<div class='error-msg'>Stock quantity cannot be negative.</div>";
        $uploadOk = 0;
    }

    // File Validation & Upload
    if(!empty($_FILES["image"]["name"]) && $uploadOk == 1){
        $target_dir = "../public/uploads/books/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
        
        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $image_path = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_path;

        if($_FILES["image"]["size"] > 2000000) { 
            echo "<div class='error-msg'>File too large (Max 2MB).</div>"; 
            $uploadOk = 0; 
        }
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") { 
            echo "<div class='error-msg'>Only JPG, JPEG, PNG allowed.</div>"; 
            $uploadOk = 0; 
        }
        
        if($uploadOk == 1) {
            if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // File successfully moved
            } else {
                echo "<div class='error-msg'>Error: Failed to save the image. Please check folder permissions.</div>";
                $uploadOk = 0; // Stop the database insert
            }
        }
    }

    // Insert to database if all checks pass
    if($uploadOk == 1){
        $query = "INSERT INTO books (title, author, description, price, stock, category_id, image_path) 
                  VALUES ('$title', '$author', '$description', '$price', '$stock', '$category_id', '$image_path')";
        if(mysqli_query($conn, $query)){
            echo "<div class='success-msg'>Book Added Successfully!</div>";
        } else {
            echo "<div class='error-msg'>Database Error: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<div class="page-header">
    <h1>Add Book</h1>
</div>

<form method="post" enctype="multipart/form-data" onsubmit="return validateBookForm()">
    <label>Title:</label> 
    <input type="text" name="title" id="title" required>
    
    <label>Author:</label> 
    <input type="text" name="author" id="author" required>
    
    <label>Category:</label>
    <select name="category_id" id="category_id" required>
        <option value="" disabled selected>-- Select a Category --</option>
        <?php 
        // Safely fetch categories. If empty, provides a fallback.
        $cats = mysqli_query($conn, "SELECT * FROM categories ORDER BY name ASC");
        if($cats && mysqli_num_rows($cats) > 0) {
            while($c = mysqli_fetch_assoc($cats)) { 
                echo "<option value='{$c['id']}'>{$c['name']}</option>"; 
            }
        } else {
            echo "<option value='0'>No categories found in database</option>";
        }
        ?>
    </select>
    
    <label>Description:</label> 
    <textarea name="description" rows="4"></textarea>
    
    <label>Price (Tk):</label> 
    <input type="number" step="0.01" name="price" id="price" required min="0.01">
    
    <label>Stock Quantity:</label> 
    <input type="number" name="stock" id="stock" required min="0">
    
    <label>Image (JPEG/PNG, Max 2MB):</label> 
    <input type="file" name="image" id="image" accept="image/png, image/jpeg, image/jpg" required>

    <input type="submit" name="submit" value="Add Book" class="btn">
</form>

<script>
// Final Javascript safety net
function validateBookForm() {
    let price = parseFloat(document.getElementById('price').value);
    if(price <= 0) {
        alert("Price must be greater than 0");
        return false;
    }

    let stock = parseInt(document.getElementById('stock').value);
    if(stock < 0) {
        alert("Stock quantity cannot be negative.");
        return false;
    }

    let cat = document.getElementById('category_id').value;
    if(cat === "") {
        alert("Please select a valid category.");
        return false;
    }

    let img = document.getElementById('image').files[0];
    if(img && img.size > 2 * 1024 * 1024) {
        alert("Image exceeds 2MB limit.");
        return false;
    }
    
    return true;
}
</script>

</body>
</html>