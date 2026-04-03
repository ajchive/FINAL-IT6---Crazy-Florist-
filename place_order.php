<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Please login first.");
}

$user_id = $_SESSION['user_id'];
$fullname = $_POST['fullname'];
$contact_number = $_POST['contact_number'];
$address = $_POST['address'];
$payment_method = $_POST['payment_method'];

$sql = "SELECT cart.*, products.price 
        FROM cart 
        JOIN products ON cart.product_id = products.id
        WHERE cart.user_id = $user_id";
$result = $conn->query($sql);

$total = 0;
$cart_items = [];

while($row = $result->fetch_assoc()) {
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
    $cart_items[] = $row;
}

$stmt = $conn->prepare("INSERT INTO orders (user_id, fullname, contact_number, address, total_amount, payment_method) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssds", $user_id, $fullname, $contact_number, $address, $total, $payment_method);
$stmt->execute();

$order_id = $stmt->insert_id;

foreach ($cart_items as $item) {
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    $stmt->execute();
}

if (($payment_method == "GCash" || $payment_method == "Maya") && isset($_FILES['proof_image']) && $_FILES['proof_image']['name'] != "") {
    $filename = time() . "_" . $_FILES['proof_image']['name'];
    $target = "uploads/payments/" . $filename;
    move_uploaded_file($_FILES['proof_image']['tmp_name'], $target);

    $stmt = $conn->prepare("INSERT INTO payments (order_id, payment_method, proof_image) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $order_id, $payment_method, $filename);
    $stmt->execute();
}

$conn->query("DELETE FROM cart WHERE user_id = $user_id");

echo "<script>alert('Order placed successfully!'); window.location='order_success.php?id=$order_id';</script>";
?>