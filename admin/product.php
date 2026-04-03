<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    die("Access denied.");
}
?>

<h1>Manage Products</h1>

<p>
    <a href="dashboard.php">Back to Dashboard</a> |
    <a href="add_product.php">Add New Product</a> |
    <a href="logout.php">Logout</a>
</p>

<hr>

<?php
$sql = "SELECT products.*, categories.category_name 
        FROM products 
        LEFT JOIN categories ON products.category_id = categories.id
        ORDER BY products.id DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Category</th>
            <th>Featured</th>
            <th>Action</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";

        echo "<td>";
        if (!empty($row['image'])) {
            echo "<img src='../uploads/products/" . $row['image'] . "' width='80'>";
        } else {
            echo "No Image";
        }
        echo "</td>";

        echo "<td>" . $row['product_name'] . "</td>";
        echo "<td>" . $row['description'] . "</td>";
        echo "<td>₱" . $row['price'] . "</td>";
        echo "<td>" . $row['stock'] . "</td>";
        echo "<td>" . $row['category_name'] . "</td>";
        echo "<td>" . $row['featured'] . "</td>";
        echo "<td>
                <a href='edit_product.php?id=" . $row['id'] . "'>Edit</a> |
                <a href='delete_product.php?id=" . $row['id'] . "' onclick=\"return confirm('Delete this product?')\">Delete</a>
              </td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p>No products found.</p>";
}
?>