<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Honkai Star Rail Merchandise | Official Store</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;700&family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --space-purple: #2A2356;
            --star-cyan: #4ED8D8;
            --nebula-pink: #FF69B4;
            --dark-space: #0f0f1a;
        }

        /* Header Styles */
        header {
            background: white;
            box-shadow: 0 4px 20px rgba(42, 35, 86, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(42, 35, 86, 0.1);
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
        }

        .logo a {
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--space-purple);
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .logo span {
            color: var(--star-cyan);
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 1.5rem;
            align-items: center;
        }

        nav a {
            font-family: 'Segoe UI', sans-serif;
            font-weight: 600;
            color: var(--space-purple);
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 0;
        }

        nav a:hover {
            color: var(--star-cyan);
        }

        nav a:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--star-cyan);
            transition: width 0.3s ease;
        }

        nav a:hover:after {
            width: 100%;
        }

        .cart-icon {
            position: relative;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--star-cyan);
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.7rem;
            font-weight: bold;
        }

        .account-link, .login-link {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(78, 216, 216, 0.1);
            padding: 0.6rem 1rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .account-link:hover, .login-link:hover {
            background: rgba(78, 216, 216, 0.2);
            transform: translateY(-2px);
        }

        .account-link i, .login-link i {
            color: var(--star-cyan);
        }

        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--space-purple);
            cursor: pointer;
        }

        @media (max-width: 992px) {
            .header-container {
                padding: 0 1.5rem;
            }

            nav ul {
                gap: 1rem;
            }
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }

            nav {
                position: fixed;
                top: 80px;
                left: 0;
                width: 100%;
                background: white;
                box-shadow: 0 10px 20px rgba(42, 35, 86, 0.1);
                padding: 2rem;
                transform: translateY(-150%);
                transition: transform 0.3s ease;
                z-index: 999;
            }

            nav.active {
                transform: translateY(0);
            }

            nav ul {
                flex-direction: column;
                align-items: flex-start;
                gap: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <a href="/honkaishop/">Honkai<span>Shop</span></a>
            </div>
            
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            
            <nav id="mainNav">
                <ul>
                    <li><a href="/honkaishop/">Home</a></li>
                    <li><a href="/honkaishop/products/">Products</a></li>
                    <li><a href="/honkaishop/about.php">About</a></li>
                    <li><a href="/honkaishop/contact.php">Contact</a></li>
                    <li class="cart-icon">
                        <a href="/honkaishop/cart.php" class="nav-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count">
                                <?php 
                                    if (isset($_SESSION['user_id'])) {
                                        $db = new Database();
                                        $conn = $db->connect();
                                        $stmt = $conn->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
                                        $stmt->execute([$_SESSION['user_id']]);
                                        echo $stmt->fetchColumn() ?: 0;
                                    } else {
                                        echo 0;
                                    }
                                ?>
                            </span>
                        </a>
                    </li>
                    <li>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="/honkaishop/account.php" class="account-link">
                                <i class="fas fa-user-circle"></i> My Account
                            </a>
                        <?php else: ?>
                            <a href="/honkaishop/login.php" class="login-link">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mainNav = document.getElementById('mainNav');
        
        mobileMenuBtn.addEventListener('click', () => {
            mainNav.classList.toggle('active');
            mobileMenuBtn.innerHTML = mainNav.classList.contains('active') 
                ? '<i class="fas fa-times"></i>' 
                : '<i class="fas fa-bars"></i>';
        });
    </script>