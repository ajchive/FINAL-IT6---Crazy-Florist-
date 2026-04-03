<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    die("Access denied.");
}

if (!isset($_GET['id'])) {
    die("Product ID missing.");
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}
?>

<h1>Edit Product</h1>

<p>
    <a href="products.php">Back to Products</a> |
    <a href="dashboard.php">Dashboard</a>
</p>

<hr>

<form method="POST" enctype="multipart/form-data">
    <label>Product Name:</label><br>
    <input type="text" name="product_name" value="<?php echo $product['product_name']; ?>" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" required><?php echo $product['description']; ?></textarea><br><br>

    <label>Price:</label><br>
    <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required><br><br>

    <label>Stock:</label><br>
    <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required><br><br>

    <label>Category:</label><br>
    <select name="category_id" required>
        <option value="">Select Category</option>
        <?php
        $cat_result = $conn->query("SELECT * FROM categories");
        while ($cat = $cat_result->fetch_assoc()) {
            $selected = ($cat['id'] == $product['category_id']) ? "selected" : "";
            echo "<option value='" . $cat['id'] . "' $selected>" . $cat['category_name'] . "</option>";
        }
        ?>
    </select><br><br>

    <label>Featured:</label><br>
    <select name="featured">
        <option value="no" <?php if($product['featured'] == 'no') echo 'selected'; ?>>No</option>
        <option value="yes" <?php if($product['featured'] == 'yes') echo 'selected'; ?>>Yes</option>
    </select><br><br>

    <p>Current Image:</p>
    <?php
    if (!empty($product['image'])) {
        echo "<img src='../uploads/products/" . $product['image'] . "' width='120'><br><br>";
    } else {
        echo "<p>No image uploaded.</p>";
    }
    ?>

    <label>New Product Image (optional):</label><br>
    <input type="file" name="image"><br><br>

    <button type="submit" name="update_product">Update Product</button>
</form>

<?php
if (isset($_POST['update_product'])) {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];
    $featured = $_POST['featured'];

    $image_name = $product['image'];

    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . "_" . $_FILES['image']['name'];
        $target = "../uploads/products/" . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $stmt = $conn->prepare("UPDATE products SET product_name=?, description=?, price=?, stock=?, category_id=?, image=?, featured=? WHERE id=?");
    $stmt->bind_param("ssdisssi", $product_name, $description, $price, $stock, $category_id, $image_name, $featured, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully!'); window.location='products.php';</script>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
}
?>