<?php include 'includes/header.php'; ?>
<?php include 'includes/db.php'; ?>

<?php
if (!isset($_GET['id'])) {
    die("No order found.");
}

$order_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
?>

<h1>Order Successful!</h1>
<p>Thank you for ordering from Crazy Florist.</p>
<p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
<p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
<p><strong>Order Status:</strong> <?php echo $order['order_status']; ?></p>

<a href="shop.php">Continue Shopping</a>

<?php include 'includes/footer.php'; ?>