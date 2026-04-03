<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    die("Access denied.");
}
?>

<h1>Add New Product</h1>

<p>
    <a href="product.php">Back to Products</a> |
    <a href="dashboard.php">Dashboard</a>
</p>

<hr>

<form method="POST" enctype="multipart/form-data">
    <label>Product Name:</label><br>
    <input type="text" name="product_name" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" required></textarea><br><br>

    <label>Price:</label><br>
    <input type="number" step="0.01" name="price" required><br><br>

    <label>Stock:</label><br>
    <input type="number" name="stock" required><br><br>

    <label>Category:</label><br>
    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php
        $cat_result = $conn->query("SELECT * FROM categories");
        while ($cat = $cat_result->fetch_assoc()) {
            echo "<option value='" . $cat['id'] . "'>" . $cat['category_name'] . "</option>";
        }
        ?>
    </select><br><br>

    <label>Featured:</label><br>
    <select name="featured">
        <option value="no">No</option>
        <option value="yes">Yes</option>
    </select><br><br>

    <label>Product Image:</label><br>
    <input type="file" name="image"><br><br>

    <button type="submit" name="add_product">Add Product</button>
</form>

<?php
if (isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];
    $featured = $_POST['featured'];

    $image_name = "";

    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . "_" . $_FILES['image']['name'];
        $target = "../uploads/products/" . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $stmt = $conn->prepare("INSERT INTO products (product_name, description, price, stock, category_id, image, featured) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdisss", $product_name, $description, $price, $stock, $category_id, $image_name, $featured);

    if ($stmt->execute()) {
        echo "<script>alert('Product added successfully!'); window.location='product.php';</script>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
}
?>