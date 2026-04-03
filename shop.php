<?php
include 'includes/db.php';
include 'includes/header.php';

$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<section style="padding: 30px 0;">
    <div class="container">
        <div class="center" style="margin-bottom: 30px;">
            <h2 class="section-title" style="font-size:2rem;">Our Floral Collection 🌷</h2>
            <p class="muted">Browse our handcrafted bouquets and floral arrangements for every occasion.</p>
        </div>

        <?php if (isset($_SESSION['cart_message'])): ?>
    <div class="card center" style="margin-bottom:20px; background:#fff0f8; border:1px solid #f6c5e0;">
        <p style="margin:0; font-weight:600; color:#6d2c91;">
            <?php 
                echo $_SESSION['cart_message']; 
                unset($_SESSION['cart_message']);
            ?>
        </p>
    </div>
<?php endif; ?>

        <div class="product-grid">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="uploads/products//<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                    <div class="product-body">
                        <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                        
                        <p class="muted"><?php echo htmlspecialchars($row['description']); ?></p>
                        <p class="price">₱<?php echo number_format($row['price'], 2); ?></p>

                        <?php if ($row['stock'] > 0): ?>
                            <p class="small" style="color:green; font-weight:600;">In Stock (<?php echo $row['stock']; ?>)</p>
                            <a href="add_to_cart.php?id=<?php echo $row['id']; ?>" class="btn">Add to Cart</a>
                        <?php else: ?>
                            <p class="small" style="color:red; font-weight:600;">Out of Stock</p>
                            <button class="btn" disabled style="opacity:0.6; cursor:not-allowed;">Unavailable</button>
                        <?php endif; ?>

                        <?php if (!empty($row['featured']) && $row['featured'] == 1): ?>
                            <p class="small" style="margin-top:10px; color:#c988d9; font-weight:700;">✨ Featured Bouquet</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>