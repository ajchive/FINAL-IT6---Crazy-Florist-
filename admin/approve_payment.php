<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    die("Access denied.");
}

if (!isset($_GET['id'])) {
    die("Payment ID missing.");
}

$payment_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM payments WHERE id = ?");
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();
$payment = $result->fetch_assoc();

if (!$payment) {
    die("Payment not found.");
}

$conn->query("UPDATE payments SET status='Approved' WHERE id=$payment_id");
$conn->query("UPDATE orders SET payment_status='Paid' WHERE id=" . $payment['order_id']);

echo "<script>alert('Payment approved successfully!'); window.location='view_order.php?id=" . $payment['order_id'] . "';</script>";
?>