<?php
session_start();
include 'includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: shop.php");
    exit();
}

$product_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT id, product_name, stock FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['cart_message'] = "Product not found.";
    header("Location: shop.php");
    exit();
}

$product = $result->fetch_assoc();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$current_qty_in_cart = isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id] : 0;

if ($current_qty_in_cart >= $product['stock']) {
    $_SESSION['cart_message'] = "Cannot add more. Stock limit reached for " . $product['product_name'] . ".";
    header("Location: shop.php");
    exit();
}

if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] += 1;
} else {
    $_SESSION['cart'][$product_id] = 1;
}

$_SESSION['cart_message'] = $product['product_name'] . " added to cart successfully!";

header("Location: shop.php");
exit();
?>
