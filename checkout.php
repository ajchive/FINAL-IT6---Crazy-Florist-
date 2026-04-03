<?php include 'includes/header.php'; ?>
<?php include 'includes/db.php'; ?>

<?php
if (!isset($_SESSION['user_id'])) {
    die("Please login first.");
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT cart.*, products.product_name, products.price 
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
?>

<h1>Checkout</h1>

<form action="place_order.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="fullname" placeholder="Full Name" required><br><br>
    <input type="text" name="contact_number" placeholder="Contact Number" required><br><br>
    <textarea name="address" placeholder="Delivery Address" required></textarea><br><br>

    <h3>Total: ₱<?php echo $total; ?></h3>

    <label>Payment Method:</label>
    <select name="payment_method" id="payment_method" required onchange="toggleProof()">
        <option value="">Select Payment</option>
        <option value="COD">Cash on Delivery</option>
        <option value="GCash">GCash</option>
        <option value="Maya">Maya</option>
    </select><br><br>

    <div id="paymentProofSection" style="display:none;">
        <p>Upload Payment Proof:</p>
        <input type="file" name="proof_image"><br><br>
    </div>

    <button type="submit">Place Order</button>
</form>

<script>
function toggleProof() {
    var paymentMethod = document.getElementById("payment_method").value;
    var proofSection = document.getElementById("paymentProofSection");

    if (paymentMethod === "GCash" || paymentMethod === "Maya") {
        proofSection.style.display = "block";
    } else {
        proofSection.style.display = "none";
    }
}
</script>

<?php include 'includes/footer.php'; ?>