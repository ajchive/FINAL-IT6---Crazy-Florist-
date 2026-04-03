<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    die("Access denied.");
}

if (!isset($_GET['id'])) {
    die("Order ID missing.");
}

$order_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    die("Order not found.");
}
?>

<h1>View Order #<?php echo $order['id']; ?></h1>

<p>
    <a href="orders.php">Back to Orders</a> |
    <a href="dashboard.php">Dashboard</a>
</p>

<hr>

<h3>Customer Details</h3>
<p><strong>Name:</strong> <?php echo $order['fullname']; ?></p>
<p><strong>Contact:</strong> <?php echo $order['contact_number']; ?></p>
<p><strong>Address:</strong> <?php echo $order['address']; ?></p>
<p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
<p><strong>Payment Status:</strong> <?php echo $order['payment_status']; ?></p>
<p><strong>Order Status:</strong> <?php echo $order['order_status']; ?></p>
<p><strong>Total:</strong> ₱<?php echo $order['total_amount']; ?></p>

<hr>

<h3>Ordered Products</h3>

<?php
$item_sql = "SELECT order_items.*, products.product_name 
             FROM order_items 
             JOIN products ON order_items.product_id = products.id
             WHERE order_items.order_id = $order_id";
$item_result = $conn->query($item_sql);

if ($item_result->num_rows > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
          </tr>";

    while ($item = $item_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $item['product_name'] . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "<td>₱" . $item['price'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p>No order items found.</p>";
}
?>

<hr>

<h3>Payment Proof</h3>

<?php
$pay_stmt = $conn->prepare("SELECT * FROM payments WHERE order_id = ?");
$pay_stmt->bind_param("i", $order_id);
$pay_stmt->execute();
$pay_result = $pay_stmt->get_result();
$payment = $pay_result->fetch_assoc();

if ($payment) {
    echo "<p><strong>Payment Method:</strong> " . $payment['payment_method'] . "</p>";
    echo "<p><strong>Status:</strong> " . $payment['status'] . "</p>";
    echo "<img src='../uploads/payments/" . $payment['proof_image'] . "' width='250'><br><br>";
    echo "<a href='approve_payment.php?id=" . $payment['id'] . "'>Approve Payment</a> | ";
    echo "<a href='reject_payment.php?id=" . $payment['id'] . "'>Reject Payment</a>";
} else {
    echo "<p>No payment proof uploaded (possibly COD).</p>";
}
?>

<hr>

<h3>Update Order Status</h3>

<form method="POST">
    <select name="order_status" required>
        <option value="Pending" <?php if($order['order_status'] == 'Pending') echo 'selected'; ?>>Pending</option>
        <option value="Preparing" <?php if($order['order_status'] == 'Preparing') echo 'selected'; ?>>Preparing</option>
        <option value="Out for Delivery" <?php if($order['order_status'] == 'Out for Delivery') echo 'selected'; ?>>Out for Delivery</option>
        <option value="Delivered" <?php if($order['order_status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
        <option value="Cancelled" <?php if($order['order_status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
    </select>
    <button type="submit" name="update_status">Update Status</button>
</form>

<?php
if (isset($_POST['update_status'])) {
    $order_status = $_POST['order_status'];

    $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
    $stmt->bind_param("si", $order_status, $order_id);

    if ($stmt->execute()) {
        echo "<script>alert('Order status updated!'); window.location='view_order.php?id=$order_id';</script>";
    } else {
        echo "<p>Error updating order status.</p>";
    }
}
?>