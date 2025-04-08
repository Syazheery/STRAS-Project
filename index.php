<?php
require_once 'config/database.php';
require_once 'includes/header.php';

$db = new Database();
$conn = $db->connect();

// Get featured products
$featured_query = "SELECT * FROM products WHERE featured = 1 LIMIT 8";
$featured_stmt = $conn->prepare($featured_query);
$featured_stmt->execute();
$featured_products = $featured_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Honkai Star Rail Merchandise</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --space-purple: #2A2356;
            --star-cyan: #4ED8D8;
            --nebula-pink: #FF69B4;
            --dark-space: #0f0f1a;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(42, 35, 86, 0.9), rgba(42, 35, 86, 0.7)),
                        url('/honkaishop/assets/images/space-bg.jpg') center/cover;
            padding: 8rem 0;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-title {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .btn-large {
            padding: 0.8rem 2rem;
            font-size: 1.2rem;
            background: var(--star-cyan);
            border: none;
            color: var(--space-purple);
            font-weight: bold;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-large:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(78, 216, 216, 0.4);
        }

        /* Collections Section */
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin: 4rem 0 2rem;
            color: var(--space-purple);
            position: relative;
        }

        .section-title:after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--star-cyan);
            margin: 0.5rem auto;
        }

        .collection-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .collection-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .collection-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .collection-image {
            height: 250px;
            overflow: hidden;
        }

        .collection-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .collection-card:hover .collection-image img {
            transform: scale(1.05);
        }

        .collection-info {
            padding: 1.5rem;
            background: white;
        }

        .collection-name {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--space-purple);
        }

        .collection-link {
            color: var(--star-cyan);
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .collection-link:hover {
            color: var(--space-purple);
            transform: translateX(5px);
        }

        /* Featured Products */
        .featured-products {
            background: rgba(42, 35, 86, 0.05);
            padding: 4rem 0;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .product-image {
            height: 250px;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-info {
            padding: 1.5rem;
        }

        .product-info h3 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: var(--space-purple);
        }

        .price {
            font-size: 1.3rem;
            font-weight: bold;
            color: var(--star-cyan);
        }

        .add-to-cart {
            width: 100%;
            padding: 0.8rem;
            background: var(--space-purple);
            color: white;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-to-cart:hover {
            background: var(--star-cyan);
            color: var(--space-purple);
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(rgba(42, 35, 86, 0.9), rgba(42, 35, 86, 0.7)),
                        url('/honkaishop/assets/images/space-bg-2.jpg') center/cover;
            padding: 6rem 0;
            text-align: center;
            color: white;
        }

        .cta-title {
            font-size: 2.5rem;
            margin-bottom: 2rem;
        }

        .btn-outline {
            padding: 0.8rem 2rem;
            font-size: 1.1rem;
            background: transparent;
            border: 2px solid var(--star-cyan);
            color: white;
            font-weight: bold;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            background: var(--star-cyan);
            color: var(--space-purple);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(78, 216, 216, 0.4);
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.8rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .collection-grid, .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 5rem 0;
            }
            
            .hero-title {
                font-size: 2.2rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .cta-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .collection-grid, .product-grid {
                grid-template-columns: 1fr;
            }
            
            .hero-title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

<main class="homepage">
  <!-- Hero Section -->
  <section class="hero-section">
    <div class="hero-content">
      <h1 class="hero-title">Honkai Star Rail Merchandise</h1>
      <p class="hero-subtitle">Premium collectibles and apparel for devoted Trailblazers</p>
      <a href="/honkaishop/products/" class="btn btn-large">Shop Now</a>
    </div>
  </section>

  <!-- Featured Collections -->
  <section>
    <h2 class="section-title">Shop Collections</h2>
    <div class="collection-grid">
      <div class="collection-card">
        <div class="collection-image">
          <img src="/honkaishop/assets/images/collections/figures.jpg" alt="Figures Collection">
        </div>
        <div class="collection-info">
          <h3 class="collection-name">Figures</h3>
          <a href="/honkaishop/products/?category=figures" class="collection-link">View Collection →</a>
        </div>
      </div>
      
      <div class="collection-card">
        <div class="collection-image">
          <img src="/honkaishop/assets/images/collections/apparel.jpg" alt="Apparel Collection">
        </div>
        <div class="collection-info">
          <h3 class="collection-name">Official Apparel</h3>
          <a href="/honkaishop/products/?category=apparel" class="collection-link">View Collection →</a>
        </div>
      </div>
      
      <div class="collection-card">
        <div class="collection-image">
          <img src="/honkaishop/assets/images/collections/accessories.jpg" alt="Accessories Collection">
        </div>
        <div class="collection-info">
          <h3 class="collection-name">Accessories</h3>
          <a href="/honkaishop/products/?category=accessories" class="collection-link">View Collection →</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Featured Products -->
  <section class="featured-products">
    <h2 class="section-title">Featured Products</h2>
    <div class="product-grid">
      <?php foreach ($featured_products as $product): ?>
        <div class="product-card">
          <a href="/honkaishop/products/view.php?id=<?= $product['id'] ?>">
            <div class="product-image">
              <img src="/honkaishop/assets/images/products/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
            </div>
            <div class="product-info">
              <h3><?= htmlspecialchars($product['name']) ?></h3>
              <div class="price">$<?= number_format($product['price'], 2) ?></div>
            </div>
          </a>
          <button class="add-to-cart" data-id="<?= $product['id'] ?>">Add to Cart</button>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Call to Action -->
  <section class="cta-section">
    <h2 class="cta-title">Join Our Trailblazing Club</h2>
    <a href="/honkaishop/register.php" class="btn btn-outline">Sign Up Now</a>
  </section>
</main>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Simple add to cart functionality
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.getAttribute('data-id');
        // Here you would typically make an AJAX call to add to cart
        alert(`Product ${productId} added to cart!`);
    });
});
</script>

</body>
</html>

<?php require_once 'includes/footer.php'; ?>
