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

$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>alert('Product deleted successfully!'); window.location='products.php';</script>";
} else {
    echo "<p>Error deleting product.</p>";
}
?>