<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// Contact form processing
$formErrors = [];
$formSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation
    if (empty($name)) $formErrors['name'] = 'Name is required';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formErrors['email'] = 'Valid email is required';
    }
    if (empty($subject)) $formErrors['subject'] = 'Subject is required';
    if (strlen($message) < 20) $formErrors['message'] = 'Message must be at least 20 characters';

    if (empty($formErrors)) {
        $formSuccess = true;
        error_log("New contact form submission: 
            Name: $name
            Email: $email
            Subject: $subject
            Message: $message");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Honkai Star Rail Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --space-purple: #2A2356;
            --star-cyan: #4ED8D8;
            --nebula-pink: #FF69B4;
            --dark-space: #0f0f1a;
        }

        .contact-hero {
            background: linear-gradient(rgba(42, 35, 86, 0.9), rgba(42, 35, 86, 0.7)),
                        url('assets/images/space-bg.jpg') center/cover;
            padding: 6rem 0;
            color: white;
            text-align: center;
        }

        .contact-hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .contact-hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .contact-container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 1rem;
        }

        /* Form Section */
        .contact-form-section {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 3rem;
        }

        .contact-form-section h2,
        .faq-section h2,
        .contact-info h2 {
            color: var(--space-purple);
            font-size: 2rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .contact-form-section h2 i,
        .faq-section h2 i,
        .contact-info h2 i {
            color: var(--star-cyan);
        }

        .alert.success {
            background: rgba(46, 204, 113, 0.2);
            color: #27ae60;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            border-left: 4px solid #27ae60;
        }

        .alert.success i {
            font-size: 1.2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--space-purple);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid rgba(42, 35, 86, 0.2);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--star-cyan);
            box-shadow: 0 0 0 3px rgba(78, 216, 216, 0.2);
        }

        .form-group.error input,
        .form-group.error select,
        .form-group.error textarea {
            border-color: #e74c3c;
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            display: block;
        }

        .btn-primary {
            background: var(--space-purple);
            color: white;
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 50px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background: var(--star-cyan);
            color: var(--space-purple);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(78, 216, 216, 0.4);
        }

        /* FAQ Section */
        .faq-section {
            margin-bottom: 3rem;
        }

        .faq-category {
            margin-bottom: 2rem;
        }

        .faq-category h3 {
            color: var(--space-purple);
            font-size: 1.3rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .faq-category h3 i {
            color: var(--star-cyan);
        }

        .faq-item {
            margin-bottom: 0.5rem;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .faq-question {
            width: 100%;
            padding: 1rem 1.5rem;
            background: white;
            border: none;
            text-align: left;
            font-weight: 600;
            color: var(--space-purple);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .faq-question:hover {
            background: rgba(42, 35, 86, 0.05);
        }

        .faq-question i {
            transition: transform 0.3s ease;
        }

        .faq-answer {
            background: rgba(42, 35, 86, 0.03);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .faq-answer p {
            padding: 1rem 1.5rem;
            margin: 0;
            color: var(--space-purple);
        }

        /* Contact Info */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .info-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .info-icon {
            width: 60px;
            height: 60px;
            background: rgba(78, 216, 216, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .info-icon i {
            font-size: 1.5rem;
            color: var(--star-cyan);
        }

        .info-card h3 {
            color: var(--space-purple);
            margin-bottom: 0.5rem;
        }

        .info-card p {
            color: rgba(42, 35, 86, 0.8);
            margin-bottom: 0.5rem;
        }

        .btn-small {
            padding: 0.5rem 1.5rem;
            background: var(--space-purple);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-small:hover {
            background: var(--star-cyan);
            color: var(--space-purple);
        }

        @media (max-width: 768px) {
            .contact-hero {
                padding: 4rem 0;
            }
            
            .contact-hero h1 {
                font-size: 2.2rem;
            }
            
            .contact-form-section,
            .info-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>

<main class="contact-page">
    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <h1>Need Help, Trailblazer?</h1>
            <p>We're here to assist with any questions about your orders or our products</p>
        </div>
    </section>

    <div class="container contact-container">
        <!-- Contact Form -->
        <section class="contact-form-section">
            <h2><i class="fas fa-paper-plane"></i> Send Us a Message</h2>
            
            <?php if ($formSuccess): ?>
                <div class="alert success">
                    <i class="fas fa-check-circle"></i>
                    Message sent successfully! We'll respond within 24 hours.
                </div>
            <?php endif; ?>

            <form method="POST" class="contact-form">
                <div class="form-group <?= isset($formErrors['name']) ? 'error' : '' ?>">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                    <?php if (isset($formErrors['name'])): ?>
                        <span class="error-message"><?= $formErrors['name'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group <?= isset($formErrors['email']) ? 'error' : '' ?>">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    <?php if (isset($formErrors['email'])): ?>
                        <span class="error-message"><?= $formErrors['email'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group <?= isset($formErrors['subject']) ? 'error' : '' ?>">
                    <label for="subject">Subject</label>
                    <select id="subject" name="subject" required>
                        <option value="">Select a topic</option>
                        <option value="Order Help" <?= ($_POST['subject'] ?? '') === 'Order Help' ? 'selected' : '' ?>>Order Help</option>
                        <option value="Product Question" <?= ($_POST['subject'] ?? '') === 'Product Question' ? 'selected' : '' ?>>Product Question</option>
                        <option value="Shipping Inquiry" <?= ($_POST['subject'] ?? '') === 'Shipping Inquiry' ? 'selected' : '' ?>>Shipping Inquiry</option>
                        <option value="Other" <?= ($_POST['subject'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                    <?php if (isset($formErrors['subject'])): ?>
                        <span class="error-message"><?= $formErrors['subject'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group <?= isset($formErrors['message']) ? 'error' : '' ?>">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" rows="5" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    <?php if (isset($formErrors['message'])): ?>
                        <span class="error-message"><?= $formErrors['message'] ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
            </form>
        </section>

        <!-- FAQ Section -->
        <section class="faq-section">
            <h2><i class="fas fa-question-circle"></i> Frequently Asked Questions</h2>
            
            <div class="faq-accordion">
                <!-- Shipping FAQs -->
                <div class="faq-category">
                    <h3><i class="fas fa-truck"></i> Shipping & Delivery</h3>
                    <div class="faq-item">
                        <button class="faq-question">
                            How long does shipping take?
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Standard shipping takes 3-5 business days within the US. International shipping typically takes 10-15 business days. Expedited options are available at checkout.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            Do you ship internationally?
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Yes! We ship worldwide. International orders may be subject to customs fees which are the responsibility of the customer.</p>
                        </div>
                    </div>
                </div>

                <!-- Order FAQs -->
                <div class="faq-category">
                    <h3><i class="fas fa-shopping-cart"></i> Orders & Payments</h3>
                    <div class="faq-item">
                        <button class="faq-question">
                            What payment methods do you accept?
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>We accept Visa, Mastercard, American Express, and PayPal.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            Can I cancel or change my order?
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Orders can be modified within 1 hour of placement. After that, please contact our support team immediately.</p>
                        </div>
                    </div>
                </div>

                <!-- Product FAQs -->
                <div class="faq-category">
                    <h3><i class="fas fa-box-open"></i> Product Questions</h3>
                    <div class="faq-item">
                        <button class="faq-question">
                            Are your products official merchandise?
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Yes! All our products are 100% officially licensed by miHoYo. We provide certificates of authenticity with limited edition items.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <button class="faq-question">
                            What if my item arrives damaged?
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Contact us within 7 days of delivery with photos of the damaged item and packaging. We'll send a replacement or issue a refund.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Info -->
        <section class="contact-info">
            <h2><i class="fas fa-info-circle"></i> Other Ways to Reach Us</h2>
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Email Us</h3>
                    <p>support@honkaishop.com</p>
                    <p>Response time: 24 hours</p>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h3>Call Us</h3>
                    <p>+1 (555) HI3-SHOP</p>
                    <p>Mon-Fri: 9AM-5PM PST</p>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fab fa-discord"></i>
                    </div>
                    <h3>Discord</h3>
                    <p>Join our Honkai community</p>
                    <a href="#" class="btn btn-small">Connect</a>
                </div>
            </div>
        </section>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// FAQ Accordion Functionality
document.querySelectorAll('.faq-question').forEach(button => {
    button.addEventListener('click', () => {
        const faqItem = button.closest('.faq-item');
        const answer = faqItem.querySelector('.faq-answer');
        const icon = button.querySelector('i');
        
        // Toggle this item
        const isOpen = answer.style.maxHeight;
        answer.style.maxHeight = isOpen ? null : answer.scrollHeight + 'px';
        icon.className = isOpen ? 'fas fa-chevron-down' : 'fas fa-chevron-up';
        
        // Close other open items
        document.querySelectorAll('.faq-item').forEach(item => {
            if (item !== faqItem) {
                item.querySelector('.faq-answer').style.maxHeight = null;
                item.querySelector('i').className = 'fas fa-chevron-down';
            }
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
