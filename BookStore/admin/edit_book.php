<?php 
// Turn on error reporting for easier debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// This brings in your CSS, navigation bar, and database connection!
include 'admin_nav.php'; 

// Make sure an ID was passed in the URL
if (!isset($_GET['id'])) {
    echo "<div class='error-msg'>No book ID provided.</div>";
    exit;
}

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM books WHERE id=$id");
$book = mysqli_fetch_assoc($result);

if (!$book) {
    echo "<div class='error-msg'>Book not found in database.</div>";
    exit;
}

if(isset($_POST['update'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category_id = intval($_POST['category_id']);
    
    $image_path = $book['image_path']; 
    $uploadOk = 1;

    // PHP Validations
    if($price <= 0) { echo "<div class='error-msg'>Price must be greater than 0.</div>"; $uploadOk = 0; }
    if($stock < 0) { echo "<div class='error-msg'>Stock cannot be negative.</div>"; $uploadOk = 0; }

    if(!empty($_FILES["image"]["name"]) && $uploadOk == 1){
        $target_dir = "../public/uploads/books/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $new_image_path = time() . '_' . basename($_FILES["image"]["name"]);
        
        if($_FILES["image"]["size"] > 2000000) { echo "<div class='error-msg'>File too large (Max 2MB).</div>"; $uploadOk = 0; }
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") { echo "<div class='error-msg'>Only JPG, JPEG, PNG allowed.</div>"; $uploadOk = 0; }
        
        if($uploadOk == 1) {
            if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $new_image_path)){
                // Delete old image if it exists to save space on your hard drive
                if($image_path && file_exists($target_dir . $image_path)) {
                    unlink($target_dir . $image_path); 
                }
                $image_path = $new_image_path;
            } else {
                 echo "<div class='error-msg'>Error: Failed to save the new image.</div>"; 
                 $uploadOk = 0;
            }
        }
    }

    if($uploadOk == 1){
        $query = "UPDATE books SET title='$title', author='$author', description='$description', price='$price', stock='$stock', category_id='$category_id', image_path='$image_path' WHERE id=$id";
        if(mysqli_query($conn, $query)){
            // Redirect back to books list after successful update
            echo "<script>window.location.href='books.php';</script>";
            exit;
        } else {
            echo "<div class='error-msg'>Database Error: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<div class="page-header">
    <h1>Edit Book</h1>
</div>

<form method="post" enctype="multipart/form-data" onsubmit="return validateBookForm()">
    <label>Title:</label> 
    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
    
    <label>Author:</label> 
    <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
    
    <label>Category:</label>
    <select name="category_id" required>
        <option value="" disabled>-- Select a Category --</option>
        <?php 
        $cats = mysqli_query($conn, "SELECT * FROM categories ORDER BY name ASC");
        if($cats && mysqli_num_rows($cats) > 0) {
            while($c = mysqli_fetch_assoc($cats)) { 
                // Select the current category this book belongs to
                $sel = ($c['id'] == $book['category_id']) ? "selected" : "";
                echo "<option value='{$c['id']}' $sel>{$c['name']}</option>"; 
            }
        } else {
            echo "<option value='0'>No categories found</option>";
        }
        ?>
    </select>
    
    <label>Description:</label> 
    <textarea name="description" rows="4"><?php echo htmlspecialchars($book['description'] ?? ''); ?></textarea>
    
    <label>Price (Tk):</label> 
    <input type="number" step="0.01" name="price" id="price" value="<?php echo $book['price']; ?>" required min="0.01">
    
    <label>Stock Quantity:</label> 
    <input type="number" name="stock" id="stock" value="<?php echo $book['stock'] ?? 0; ?>" required min="0">
    
    <label>Update Image (Leave empty to keep current image):</label> 
    <?php if($book['image_path']) { echo "<p style='font-size: 12px; margin-top: 0;'><em>Current: " . htmlspecialchars($book['image_path']) . "</em></p>"; } ?>
    <input type="file" name="image" id="image" accept="image/png, image/jpeg, image/jpg">

    <input type="submit" name="update" value="Update Book" class="btn">
</form>

<script>
function validateBookForm() {
    if(parseFloat(document.getElementById('price').value) <= 0) { alert("Price must be > 0"); return false; }
    if(parseInt(document.getElementById('stock').value) < 0) { alert("Stock cannot be negative."); return false; }
    
    let img = document.getElementById('image').files[0];
    if(img && img.size > 2 * 1024 * 1024) { alert("Image exceeds 2MB limit"); return false; }
    return true;
}
</script>

</body>
</html>