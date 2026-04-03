<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<section style="padding: 30px 0;">
    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:10px;">
            <h2 class="section-title" style="margin:0;">My Orders</h2>
            <a href="shop.php" class="btn btn-outline">← Back to Shop</a>
        </div>

        <?php if($result->num_rows == 0): ?>
            <div class="card center">
                <p>You have no orders yet.</p>
                <a href="shop.php" class="btn">Start Shopping</a>
            </div>
        <?php else: ?>
            <div class="card" style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f6c5e0;">
                            <th style="padding:12px; text-align:left;">Order ID</th>
                            <th style="padding:12px; text-align:left;">Full Name</th>
                            <th style="padding:12px; text-align:left;">Contact</th>
                            <th style="padding:12px; text-align:left;">Address</th>
                            <th style="padding:12px; text-align:left;">Total</th>
                            <th style="padding:12px; text-align:left;">Payment</th>
                            <th style="padding:12px; text-align:left;">Payment Status</th>
                            <th style="padding:12px; text-align:left;">Order Status</th>
                            <th style="padding:12px; text-align:left;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($order = $result->fetch_assoc()): ?>
                            <tr style="border-bottom:1px solid #eee;">
                                <td style="padding:12px;">#<?php echo $order['id']; ?></td>
                                <td style="padding:12px;"><?php echo htmlspecialchars($order['fullname']); ?></td>
                                <td style="padding:12px;"><?php echo htmlspecialchars($order['contact_number']); ?></td>
                                <td style="padding:12px;"><?php echo htmlspecialchars($order['address']); ?></td>
                                <td style="padding:12px;">₱<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td style="padding:12px;"><?php echo htmlspecialchars($order['payment_method']); ?></td>
                                <td style="padding:12px;"><?php echo htmlspecialchars($order['payment_status']); ?></td>
                                <td style="padding:12px;"><?php echo htmlspecialchars($order['order_status']); ?></td>
                                <td style="padding:12px;"><?php echo $order['created_at']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>