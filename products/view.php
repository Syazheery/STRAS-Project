<?php
require_once '../config/database.php';
require_once '../includes/header.php';

// Fetch product from database
$db = new Database();
$conn = $db->connect();

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$product) {
  header('Location: /honkaishop/products/');
  exit;
}

// Fetch related products
$stmt = $conn->prepare("SELECT * FROM products WHERE category = ? AND id != ? LIMIT 4");
$stmt->execute([$product['category'], $product['id']]);
$related_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="product-detail-container">
  <div class="product-gallery">
    <div class="thumbnail-container">
      <img src="/honkaishop/assets/images/products/<?= $product['image'] ?>" class="thumbnail" alt="Thumbnail 1">
    </div>
    <div class="main-image">
      <img src="/honkaishop/assets/images/products/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
    </div>
  </div>
  
  <div class="product-info">
    <h1 class="product-title"><?= $product['name'] ?></h1>
    <div class="product-price">$<?= number_format($product['price'], 2) ?></div>
    
    <div class="product-description">
      <p><?= $product['description'] ?></p>
    </div>
    
    <div class="product-meta">
      <div class="meta-item">
        <span class="meta-label">Category:</span>
        <span class="meta-value"><?= $product['category'] ?></span>
      </div>
      <div class="meta-item">
        <span class="meta-label">Availability:</span>
        <span class="meta-value"><?= $product['stock'] > 0 ? 'In Stock' : 'Out of Stock' ?></span>
      </div>
    </div>
    
    <div class="quantity-selector">
      <label for="quantity">Quantity:</label>
      <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>">
    </div>
    
    <button class="add-to-cart" data-product-id="<?= $product['id'] ?>">Add to Cart</button>
    
    <div class="product-tabs">
      <div class="tab-header">
        <button class="tab-btn active" data-tab="description">Description</button>
        <button class="tab-btn" data-tab="details">Details</button>
        <button class="tab-btn" data-tab="shipping">Shipping</button>
      </div>
      
      <div class="tab-content active" id="description">
        <p><?= $product['description'] ?></p>
      </div>
      
      <div class="tab-content" id="details">
        <p>Product details coming soon...</p>
      </div>
      
      <div class="tab-content" id="shipping">
        <p>Standard shipping: 3-5 business days</p>
        <p>Express shipping available</p>
      </div>
    </div>
  </div>
</section>

<?php if(!empty($related_products)): ?>
<section class="related-products">
  <h2 class="related-title">You May Also Like</h2>
  <div class="product-grid">
    <?php foreach($related_products as $related): ?>
    <div class="product-card">
      <a href="/honkaishop/products/view.php?id=<?= $related['id'] ?>">
        <div class="product-image">
          <img src="/honkaishop/assets/images/products/<?= $related['image'] ?>" alt="<?= $related['name'] ?>">
        </div>
        <div class="product-info">
          <h3><?= $related['name'] ?></h3>
          <div class="price">$<?= number_format($related['price'], 2) ?></div>
        </div>
      </a>
      <button class="btn add-to-cart" data-product-id="<?= $related['id'] ?>">Add to Cart</button>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<script>
// Tab functionality
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    // Remove active class from all
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    
    // Add active class to clicked
    btn.classList.add('active');
    document.getElementById(btn.dataset.tab).classList.add('active');
  });
});

// Thumbnail click functionality
document.querySelectorAll('.thumbnail').forEach(thumb => {
  thumb.addEventListener('click', () => {
    const mainImg = document.querySelector('.main-image img');
    mainImg.src = thumb.src;
  });
});
</script>

<?php require_once '../includes/footer.php'; ?>