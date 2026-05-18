<?php include 'admin_nav.php'; ?>

<div class="page-header">
    <h1>Edit Book (MVC)</h1>
</div>

<?php if(!empty($message)) echo $message; ?>

<form method="post" enctype="multipart/form-data" onsubmit="return validateBookForm()">
    <label>Title:</label> 
    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
    
    <label>Author:</label> 
    <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
    
    <label>Category:</label>
    <select name="category_id" required>
        <?php foreach ($categories as $cat): ?>
            <?php $sel = ($cat['id'] == $book['category_id']) ? "selected" : ""; ?>
            <option value="<?php echo $cat['id']; ?>" <?php echo $sel; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
        <?php endforeach; ?>
    </select>
    
    <label>Description:</label> 
    <textarea name="description" rows="4"><?php echo htmlspecialchars($book['description'] ?? ''); ?></textarea>
    
    <label>Price (Tk):</label> 
    <input type="number" step="0.01" name="price" id="price" value="<?php echo $book['price']; ?>" required min="0.01">
    
    <label>Stock Quantity:</label> 
    <input type="number" name="stock" id="stock" value="<?php echo $book['stock']; ?>" required min="0">
    
    <label>Update Image (Leave empty to keep current):</label> 
    <input type="file" name="image" id="image" accept="image/png, image/jpeg, image/jpg">

    <input type="submit" name="update" value="Update Book" class="btn">
</form>

<script>
function validateBookForm() {
    if(parseFloat(document.getElementById('price').value) <= 0) return false;
    if(parseInt(document.getElementById('stock').value) < 0) return false;
    let img = document.getElementById('image').files[0];
    if(img && img.size > 2 * 1024 * 1024) return false;
    return true;
}
</script>
</body>
</html>