<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$total = 0;
?>

<section style="padding: 30px 0;">
    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:10px;">
            <h2 class="section-title" style="margin:0; font-size:2rem;">Your Cart 🛒</h2>
            <a href="shop.php" class="btn btn-outline">← Continue Shopping</a>
        </div>

        <?php if (empty($_SESSION['cart'])): ?>
            <div class="card center">
                <p>Your cart is currently empty.</p>
                <a href="shop.php" class="btn">Browse Flowers</a>
            </div>
        <?php else: ?>
            <div class="card" style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f6c5e0;">
                            <th style="padding:12px; text-align:left;">Image</th>
                            <th style="padding:12px; text-align:left;">Product</th>
                            <th style="padding:12px; text-align:left;">Price</th>
                            <th style="padding:12px; text-align:left;">Quantity</th>
                            <th style="padding:12px; text-align:left;">Subtotal</th>
                            <th style="padding:12px; text-align:left;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $id => $qty): ?>
                            <?php
                            $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
                            $stmt->bind_param("i", $id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $product = $result->fetch_assoc();

                            if ($product):
                                $subtotal = $product['price'] * $qty;
                                $total += $subtotal;
                            ?>
                            <tr style="border-bottom:1px solid #eee;">
                                <td style="padding:12px;">
                                    <img src="uploads/products/<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" style="width:80px; height:80px; object-fit:cover; border-radius:10px;">
                                </td>
                                <td style="padding:12px;"><?php echo htmlspecialchars($product['product_name']); ?></td>
                                <td style="padding:12px;">₱<?php echo number_format($product['price'], 2); ?></td>
                                <td style="padding:12px;"><?php echo $qty; ?></td>
                                <td style="padding:12px;">₱<?php echo number_format($subtotal, 2); ?></td>
                                <td style="padding:12px;">
                                    <a href="remove_from_cart.php?id=<?php echo $id; ?>" class="btn btn-outline">Remove</a>
                                </td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="card" style="margin-top:20px; text-align:right;">
                <h3>Total: <span style="color:var(--primary);">₱<?php echo number_format($total, 2); ?></span></h3>
                <a href="checkout.php" class="btn" style="margin-top:10px;">Proceed to Checkout</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>