<?php
require_once '../config/database.php';
require_once '../includes/header.php';

$db = new Database();
$conn = $db->connect();

// Get category filter from URL
$category = isset($_GET['category']) ? $_GET['category'] : null;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Build query
$where = $category ? "WHERE category = :category" : "";
$params = [];
if ($category) {
    $params[':category'] = $category;
}

// Sorting options
switch($sort) {
    case 'price_low': $order_by = 'price ASC'; break;
    case 'price_high': $order_by = 'price DESC'; break;
    case 'newest':
    default: $order_by = 'created_at DESC'; break;
}

$query = "SELECT * FROM products $where ORDER BY $order_by";
$stmt = $conn->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$category_name = $category ? ucfirst($category) : 'All';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $category_name ?> Collection | Honkai Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --space-purple: #2A2356;
            --star-cyan: #4ED8D8;
            --nebula-pink: #FF69B4;
            --dark-space: #0f0f1a;
        }

        body {
            background-color: #f8f9fa;
            color: #333;
        }

        .products-section {
            padding: 4rem 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .section-header h1 {
            font-size: 2.8rem;
            color: var(--space-purple);
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .section-header h1:after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--star-cyan);
            margin: 0.5rem auto;
        }

        .category-description {
            font-size: 1.2rem;
            color: rgba(42, 35, 86, 0.8);
            max-width: 600px;
            margin: 0 auto 2rem;
        }

        .sort-options {
            margin: 2rem auto;
            max-width: 300px;
        }

        .sort-form select {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid var(--star-cyan);
            border-radius: 50px;
            background: white;
            color: var(--space-purple);
            font-weight: bold;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%234ED8D8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sort-form select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(78, 216, 216, 0.3);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .product-image {
            height: 280px;
            overflow: hidden;
            position: relative;
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
            flex-grow: 1;
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
            margin: 0.5rem 0;
        }

        .category-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            background: rgba(42, 35, 86, 0.1);
            color: var(--space-purple);
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: bold;
            margin-top: 0.5rem;
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
            margin-top: auto;
        }

        .add-to-cart:hover {
            background: var(--star-cyan);
            color: var(--space-purple);
        }

        .no-products {
            text-align: center;
            padding: 4rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .no-products p {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            color: var(--space-purple);
        }

        .btn {
            padding: 0.8rem 2rem;
            background: var(--star-cyan);
            color: var(--space-purple);
            border: none;
            font-weight: bold;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(78, 216, 216, 0.4);
            color: var(--space-purple);
        }

        @media (max-width: 768px) {
            .section-header h1 {
                font-size: 2.2rem;
            }
            
            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }

        @media (max-width: 576px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<section class="products-section">
    <div class="container">
        <div class="section-header">
            <h1><?= $category_name ?> Collection</h1>
            
            <?php if ($category): ?>
            <p class="category-description">
                Browse our <?= $category_name ?> collection
            </p>
            <?php endif; ?>
            
            <div class="sort-options">
                <form method="get" class="sort-form">
                    <?php if ($category): ?>
                    <input type="hidden" name="category" value="<?= $category ?>">
                    <?php endif; ?>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="newest" <?= $sort == 'newest' ? 'selected' : '' ?>>Newest</option>
                        <option value="price_low" <?= $sort == 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                        <option value="price_high" <?= $sort == 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                    </select>
                </form>
            </div>
        </div>
        
        <?php if (count($products) > 0): ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <a href="view.php?id=<?= $product['id'] ?>">
                        <div class="product-image">
                            <img src="/honkaishop/assets/images/products/<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        </div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <div class="price">$<?= number_format($product['price'], 2) ?></div>
                            <div class="category-badge"><?= ucfirst($product['category']) ?></div>
                        </div>
                    </a>
                    <button class="add-to-cart" data-id="<?= $product['id'] ?>">Add to Cart</button>
                </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="no-products">
            <p>No products found in this category.</p>
            <a href="/honkaishop/products/" class="btn">View All Products</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Add to cart functionality
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.getAttribute('data-id');
        const button = this;
        
        fetch('cart_action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Visual feedback
                button.textContent = 'Added!';
                button.style.backgroundColor = 'var(--star-cyan)';
                button.style.color = 'var(--space-purple)';
                
                setTimeout(() => {
                    button.textContent = 'Add to Cart';
                    button.style.backgroundColor = 'var(--space-purple)';
                    button.style.color = 'white';
                }, 1500);
                
                // Update cart count in header if you have one
                if (data.count) {
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.count;
                    }
                }
            } else {
                alert(data.message || 'Failed to add to cart');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding to cart');
        });
    });
});
</script>

</body>
</html>

<?php require_once '../includes/footer.php'; ?>