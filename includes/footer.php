<footer class="site-footer">
    <style>
        :root {
            --space-purple: #2A2356;
            --star-cyan: #4ED8D8;
            --nebula-pink: #FF69B4;
            --dark-space: #0f0f1a;
        }

        .site-footer {
            background: linear-gradient(135deg, var(--space-purple), #3a2e7a);
            color: white;
            padding: 4rem 0 0;
            position: relative;
            overflow: hidden;
        }

        .site-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/honkaishop/assets/images/star-pattern.png') center/cover;
            opacity: 0.1;
            pointer-events: none;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-column {
            position: relative;
            z-index: 1;
        }

        .footer-heading {
            color: var(--star-cyan);
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
        }

        .footer-heading::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--nebula-pink);
            border-radius: 3px;
        }

        .footer-about {
            color: rgba(255,255,255,0.8);
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: white;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background: var(--star-cyan);
            color: var(--space-purple);
            transform: translateY(-3px);
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            padding-left: 1.5rem;
        }

        .footer-links a::before {
            content: '▹';
            position: absolute;
            left: 0;
            color: var(--star-cyan);
            transition: transform 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
            padding-left: 2rem;
        }

        .footer-links a:hover::before {
            transform: rotate(90deg);
            color: var(--nebula-pink);
        }

        .footer-contact {
            list-style: none;
            padding: 0;
        }

        .footer-contact li {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            color: rgba(255,255,255,0.8);
        }

        .footer-contact i {
            color: var(--star-cyan);
            width: 20px;
            text-align: center;
        }

        .footer-bottom {
            border-top: 1px solid rgba(78, 216, 216, 0.2);
            padding: 2rem 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
            text-align: center;
        }

        .payment-methods {
            display: flex;
            gap: 1.5rem;
            font-size: 1.8rem;
        }

        .payment-methods i {
            color: rgba(255,255,255,0.7);
            transition: all 0.3s ease;
        }

        .payment-methods i:hover {
            color: var(--star-cyan);
            transform: translateY(-3px);
        }

        .copyright {
            color: rgba(255,255,255,0.6);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
            
            .footer-bottom {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .footer-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="container">
        <div class="footer-grid">
            <!-- About Column -->
            <div class="footer-column">
                <h3 class="footer-heading">Honkai Shop</h3>
                <p class="footer-about">Your premier destination for official Honkai Star Rail merchandise and collectibles.</p>
                <div class="social-links">
                    <a href="#" class="social-icon" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon" aria-label="Discord"><i class="fab fa-discord"></i></a>
                </div>
            </div>

            <!-- Quick Links Column -->
            <div class="footer-column">
                <h3 class="footer-heading">Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="/honkaishop/">Home</a></li>
                    <li><a href="/honkaishop/products/">Products</a></li>
                    <li><a href="/honkaishop/about.php">About Us</a></li>
                    <li><a href="/honkaishop/contact.php">Contact</a></li>
                    <li><a href="/honkaishop/contact.php">FAQ</a></li>
                </ul>
            </div>

            <!-- Categories Column -->
            <div class="footer-column">
                <h3 class="footer-heading">Categories</h3>
                <ul class="footer-links">
                    <li><a href="/honkaishop/products/?category=figures">Figures</a></li>
                    <li><a href="/honkaishop/products/?category=apparel">Apparel</a></li>
                    <li><a href="/honkaishop/products/?category=accessories">Accessories</a></li>
                </ul>
            </div>

            <!-- Contact Column -->
            <div class="footer-column">
                <h3 class="footer-heading">Contact Us</h3>
                <ul class="footer-contact">
                    <li><i class="fas fa-envelope"></i> support@honkai.shop</li>
                    <li><i class="fas fa-phone"></i> +1 (555) 123-4567</li>
                    <li><i class="fas fa-map-marker-alt"></i> 123 Belobog, Jarilo-IV</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="payment-methods">
                <i class="fab fa-cc-visa" title="Visa"></i>
                <i class="fab fa-cc-mastercard" title="Mastercard"></i>
                <i class="fab fa-cc-paypal" title="PayPal"></i>
                <i class="fab fa-cc-stripe" title="Stripe"></i>
            </div>
            <div class="copyright">
                &copy; <?= date('Y') ?> Honkai Shop. All rights reserved.
            </div>
        </div>
    </div>
</footer>