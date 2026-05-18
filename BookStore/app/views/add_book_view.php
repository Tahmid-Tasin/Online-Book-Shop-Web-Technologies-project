<?php include 'admin_nav.php'; ?>

<div class="page-header">
    <h1>Add Book (MVC)</h1>
</div>

<?php if(!empty($message)) echo $message; ?>

<form method="post" enctype="multipart/form-data" onsubmit="return validateBookForm()">
    <label>Title:</label> 
    <input type="text" name="title" id="title" required>
    
    <label>Author:</label> 
    <input type="text" name="author" id="author" required>
    
    <label>Category:</label>
    <select name="category_id" id="category_id" required>
        <option value="" disabled selected>-- Select a Category --</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
        <?php endforeach; ?>
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
function validateBookForm() {
    let price = parseFloat(document.getElementById('price').value);
    if(price <= 0) { alert("Price must be greater than 0"); return false; }

    let stock = parseInt(document.getElementById('stock').value);
    if(stock < 0) { alert("Stock quantity cannot be negative."); return false; }

    let img = document.getElementById('image').files[0];
    if(img && img.size > 2 * 1024 * 1024) { alert("Image exceeds 2MB limit."); return false; }
    
    return true;
}
</script>

</body>
</html>