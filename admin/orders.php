<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    die("Access denied.");
}
?>

<h1>Manage Orders</h1>

<p>
    <a href="dashboard.php">Back to Dashboard</a> |
    <a href="product.php">Manage Products</a> |
    <a href="logout.php">Logout</a>
</p>

<hr>

<?php
$sql = "SELECT * FROM orders ORDER BY id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Contact</th>
            <th>Address</th>
            <th>Total</th>
            <th>Payment Method</th>
            <th>Payment Status</th>
            <th>Order Status</th>
            <th>Created At</th>
            <th>Action</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['fullname'] . "</td>";
        echo "<td>" . $row['contact_number'] . "</td>";
        echo "<td>" . $row['address'] . "</td>";
        echo "<td>₱" . $row['total_amount'] . "</td>";
        echo "<td>" . $row['payment_method'] . "</td>";
        echo "<td>" . $row['payment_status'] . "</td>";
        echo "<td>" . $row['order_status'] . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "<td><a href='view_order.php?id=" . $row['id'] . "'>View</a></td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p>No orders found.</p>";
}
?>