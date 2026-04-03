<?php
include 'includes/db.php';
include 'includes/header.php';

// featured products
$featured = $conn->query("SELECT * FROM products WHERE featured = 1 LIMIT 3");
?>

<!-- HERO -->
<section class="hero">
  <div class="hero-grid container">
    <div class="hero-text">
      <h1>Bloom Beyond Ordinary 🌸</h1>
      <p>
        Discover elegant floral arrangements crafted to make every moment unforgettable.
        From birthdays to heartfelt surprises, Crazy Florist brings beauty right to your doorstep.
      </p>
      <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:18px;">
        <a href="shop.php" class="btn">Shop Now</a>
        <a href="#featured" class="btn btn-outline">View Featured</a>
      </div>
    </div>
    <div class="hero-image">
      <img src="..\CrazyFlorist\img\f (33).jpg" alt="Beautiful Flower Bouquet">
    </div>
  </div>
</section>

<!-- WHY CHOOSE US -->
<section style="padding: 20px 0 40px;">
  <div class="container">
    <h2 class="section-title center">Why Choose Crazy Florist?</h2>
    <div class="cards">
      <div class="card center">
        <h3>🌷 Fresh Flowers</h3>
        <p class="muted">Handpicked blooms arranged with care and creativity.</p>
      </div>
      <div class="card center">
        <h3>🚚 Fast Delivery</h3>
        <p class="muted">Same-day delivery available for selected areas.</p>
      </div>
      <div class="card center">
        <h3>💐 Affordable Elegance</h3>
        <p class="muted">Premium floral designs without the premium stress.</p>
      </div>
    </div>
  </div>
</section>

<!-- FEATURED PRODUCTS -->
<section id="featured" style="padding: 10px 0 40px;">
  <div class="container">
    <h2 class="section-title center">Featured Bouquets</h2>
    <div class="product-grid">
      <?php while($row = $featured->fetch_assoc()): ?>
        <div class="product-card">
          <img src="uploads/products/<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
          <div class="product-body">
            <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
            <p class="muted"><?php echo htmlspecialchars($row['description']); ?></p>
            <p class="price">₱<?php echo number_format($row['price'], 2); ?></p>
            <a href="shop.php" class="btn">Order Now</a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<!-- GALLERY -->
<section style="padding: 10px 0 40px;">
  <div class="container">
    <h2 class="section-title center">Floral Moments</h2>
    <div class="gallery-grid">
      <img src="..\CrazyFlorist\img\f (32).jpg" alt="Flowers 1">
      <img src="..\CrazyFlorist\img\f (20).jpg" alt="Flowers 2">
      <img src="..\CrazyFlorist\img\f (36).jpg" alt="Flowers 3">
    </div>
  </div>
</section>

<!-- CTA -->
<section style="padding: 30px 0;">
  <div class="container">
    <div class="card center" style="padding:30px;">
      <h2 style="margin-bottom:10px;">Make Someone Smile Today 💕</h2>
      <p class="muted">Order a beautiful bouquet and let flowers say what words cannot.</p>
      <a href="shop.php" class="btn" style="margin-top:15px;">Start Shopping</a>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>