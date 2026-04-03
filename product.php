<?php include 'includes/header.php'; ?>
<?php include 'includes/db.php'; ?>

<?php
if (!isset($_GET['id'])) {
    die("Product not found.");
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

<h1><?php echo $product['product_name']; ?></h1>
<p><?php echo $product['description']; ?></p>
<p>Price: ₱<?php echo $product['price']; ?></p>
<p>Stock: <?php echo $product['stock']; ?></p>

<form action="add_to_cart.php" method="POST">
    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
    <label>Quantity:</label>
    <input type="number" name="quantity" value="1" min="1">
    <button type="submit">Add to Cart</button>
</form>

<p>Available Payment Methods: COD, GCash, Maya</p>

<?php include 'includes/footer.php'; ?>

