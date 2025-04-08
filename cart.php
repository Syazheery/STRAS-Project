<?php
require_once 'config/database.php';
require_once 'includes/cart_functions.php';

// Initialize database connection
$db = new Database();
$conn = $db->connect();

// Create CartManager instance
$cartManager = new CartManager($conn);

// Get cart data
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

$cart_data = $cartManager->getCartItems($user_id);
$cart_items = $cart_data['items'];
$subtotal = $cart_data['subtotal'];
$shipping = $subtotal > 50 ? 0 : 5.99;
$total = $subtotal + $shipping;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart | HonkaiShop</title>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;700&family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --space-purple: #2A2356;
            --star-cyan: #4ED8D8;
            --nebula-pink: #FF69B4;
            --dark-space: #0f0f1a;
        }

        .cart-page {
            background: white;
            padding: 3rem 0;
            min-height: 70vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        h1 {
            color: var(--space-purple);
            font-family: 'Rajdhani', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        h1:after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--star-cyan);
            margin-top: 0.5rem;
        }

        /* Cart Items */
        .cart-form {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 3rem;
        }

        .cart-items {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 1.5rem;
            padding: 1.5rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(42, 35, 86, 0.1);
            border: 1px solid rgba(42, 35, 86, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .cart-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(42, 35, 86, 0.15);
        }

        .cart-item img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-details {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .item-details h3 {
            color: var(--space-purple);
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }

        .item-price {
            color: var(--space-purple);
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .quantity-controls label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: var(--space-purple);
        }

        .quantity-controls input {
            width: 60px;
            padding: 0.5rem;
            border: 1px solid rgba(42, 35, 86, 0.2);
            border-radius: 5px;
            text-align: center;
        }

        .btn-update, .btn-remove {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-update {
            background: rgba(78, 216, 216, 0.1);
            color: var(--star-cyan);
        }

        .btn-update:hover {
            background: var(--star-cyan);
            color: white;
        }

        .btn-remove {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }

        .btn-remove:hover {
            background: #e74c3c;
            color: white;
        }

        .item-total {
            font-weight: bold;
            color: var(--space-purple);
            font-size: 1.1rem;
            text-align: right;
        }

        /* Cart Summary */
        .cart-summary {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(42, 35, 86, 0.1);
            border: 1px solid rgba(42, 35, 86, 0.1);
            height: fit-content;
            position: sticky;
            top: 2rem;
        }

        .cart-summary h2 {
            color: var(--space-purple);
            font-family: 'Rajdhani', sans-serif;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(42, 35, 86, 0.1);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            color: var(--space-purple);
        }

        .summary-row.total {
            font-weight: bold;
            font-size: 1.2rem;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(42, 35, 86, 0.1);
        }

        .checkout-btn {
            display: block;
            width: 100%;
            padding: 1rem;
            background: var(--star-cyan);
            color: var(--space-purple);
            text-align: center;
            font-weight: bold;
            border-radius: 50px;
            text-decoration: none;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .checkout-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(78, 216, 216, 0.4);
        }

        /* Empty Cart */
        .empty-cart {
            text-align: center;
            padding: 3rem;
            background: rgba(42, 35, 86, 0.03);
            border-radius: 10px;
            border: 2px dashed rgba(42, 35, 86, 0.1);
        }

        .empty-cart i {
            font-size: 3rem;
            color: rgba(42, 35, 86, 0.1);
            margin-bottom: 1rem;
        }

        .empty-cart h2 {
            color: var(--space-purple);
            margin-bottom: 0.5rem;
        }

        .empty-cart p {
            color: rgba(42, 35, 86, 0.7);
            margin-bottom: 1.5rem;
        }

        .btn-continue {
            padding: 0.8rem 1.5rem;
            background: var(--space-purple);
            color: white;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-continue:hover {
            background: var(--star-cyan);
            color: var(--space-purple);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(78, 216, 216, 0.4);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .cart-form {
                grid-template-columns: 1fr;
            }
            
            .cart-summary {
                position: static;
            }
        }

        @media (max-width: 576px) {
            .cart-item {
                grid-template-columns: 1fr;
            }
            
            .quantity-controls {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="cart-page">
        <div class="container">
            <h1>Your Shopping Cart</h1>
            
            <?php if (!empty($cart_items)): ?>
                <form method="post" action="cart_action.php" class="cart-form" id="cart-form">
                    <div class="cart-items">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="cart-item" data-product-id="<?= htmlspecialchars($item['id']) ?>">
                                <img src="/honkaishop/assets/images/products/<?= htmlspecialchars($item['image']) ?>" 
                                     alt="<?= htmlspecialchars($item['name']) ?>">
                                <div class="item-details">
                                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                                    <div class="item-price">$<?= number_format($item['price'], 2) ?></div>
                                    
                                    <div class="quantity-controls">
                                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['id']) ?>">
                                        <label>
                                            <span>Quantity:</span>
                                            <input type="number" name="quantity" 
                                                   value="<?= htmlspecialchars($item['quantity']) ?>" 
                                                   min="1" 
                                                   max="<?= htmlspecialchars($item['max_quantity'] ?? '') ?>">
                                        </label>
                                        <button type="button" class="btn-update update-btn">
                                            <i class="fas fa-sync-alt"></i> Update
                                        </button>
                                        <button type="button" class="btn-remove remove-btn">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </div>
                                    
                                    <div class="item-total">$<?= number_format($item['total'], 2) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="cart-summary">
                        <h2>Order Summary</h2>
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span id="cart-subtotal">$<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping:</span>
                            <span id="cart-shipping">$<?= number_format($shipping, 2) ?></span>
                        </div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span id="cart-total">$<?= number_format($total, 2) ?></span>
                        </div>
                        <a href="checkout.php" class="checkout-btn">
                            <i class="fas fa-lock"></i> Proceed to Checkout
                        </a>
                    </div>
                </form>
            <?php else: ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h2>Your cart is empty</h2>
                    <p>Looks like you haven't added any items yet</p>
                    <a href="/honkaishop/products/" class="btn-continue">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const cartForm = document.getElementById('cart-form');
        
        if (cartForm) {
            // Update button handler
            document.querySelectorAll('.update-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const item = this.closest('.cart-item');
                    const productId = item.dataset.productId;
                    const quantity = item.querySelector('input[name="quantity"]').value;
                    
                    if (quantity < 1) {
                        alert('Quantity must be at least 1');
                        return;
                    }
                    
                    updateCartItem(productId, quantity);
                });
            });
            
            // Remove button handler
            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const item = this.closest('.cart-item');
                    const productId = item.dataset.productId;
                    
                    if (confirm('Are you sure you want to remove this item?')) {
                        removeCartItem(productId);
                    }
                });
            });
        }
        
        function updateCartItem(productId, quantity) {
            fetch('cart_action.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=update&product_id=${productId}&quantity=${quantity}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error updating cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the cart');
            });
        }
        
        function removeCartItem(productId) {
            fetch('cart_action.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=remove&product_id=${productId}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error removing item');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while removing the item');
            });
        }
    });
    </script>
</body>
</html>
