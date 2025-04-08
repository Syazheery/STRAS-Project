<?php
require_once 'config/database.php';
require_once 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Honkai Star Rail Collectibles</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --space-purple: #2A2356;
            --star-cyan: #4ED8D8;
            --nebula-pink: #FF69B4;
        }

        .about-hero {
            background: linear-gradient(rgba(42, 35, 86, 0.9), rgba(42, 35, 86, 0.7)),
                        url('images/space-bg.jpg') center/cover;
            padding: 8rem 0;
            color: white;
            text-align: center;
        }

        .about-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            padding: 4rem 0;
        }

        .about-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }

        .about-card:hover {
            transform: translateY(-10px);
        }

        .about-icon {
            font-size: 2.5rem;
            color: var(--star-cyan);
            margin-bottom: 1rem;
        }

        .values-section {
            margin: 5rem 0;
            padding: 4rem 0;
            background: rgba(42, 35, 86, 0.1);
            border-radius: 20px;
        }

        .value-item {
            padding: 2rem;
            background: white;
            border-radius: 15px;
            margin: 1rem 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Video Section Styles */
        .video-showcase {
            padding: 6rem 0 4rem;
            background: #0f0f1a;
            position: relative;
            overflow: hidden;
        }

        .video-container-xxl {
            position: relative;
            padding-top: 42.86%; /* 21:9 aspect ratio */
            max-width: 1920px;
            margin: 0 auto;
        }

        .video-container-xxl iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .section-heading h2 {
            color: var(--star-cyan);
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .section-heading .subheading {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .community-cta {
            text-align: center;
            padding: 4rem 0;
            background: var(--space-purple);
            color: white;
        }

        .social-icon {
            font-size: 2rem;
            color: white;
            margin: 0 1rem;
            transition: color 0.3s ease;
        }

        .social-icon:hover {
            color: var(--star-cyan);
        }

        @media (max-width: 992px) {
            .video-container-xxl {
                padding-top: 56.25%; /* 16:9 aspect ratio */
            }
            
            .about-hero {
                padding: 4rem 0;
            }
        }

        @media (max-width: 768px) {
            .video-container-xxl {
                padding-top: 75%; /* 4:3 aspect ratio */
            }
            
            .section-heading h2 {
                font-size: 2rem;
            }
            
            .about-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<main class="about-page">
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container">
            <h1>Our Story</h1>
            <p>Bringing the world of Honkai Star Rail to life through premium collectibles</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="about-content">
        <div class="container">
            <div class="about-grid">
                <!-- Mission Card -->
                <div class="about-card">
                    <div class="about-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3>Our Mission</h3>
                    <p>To provide authentic, high-quality Honkai Star Rail merchandise that lets fans celebrate their favorite characters and moments from the game.</p>
                </div>

                <!-- History Card -->
                <div class="about-card">
                    <div class="about-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3>Our History</h3>
                    <p>Founded in 2025 by a group of passionate Honkai fans, we've grown from a small online store to the premier destination for official Honkai merchandise worldwide.</p>
                </div>

                <!-- Promise Card -->
                <div class="about-card">
                    <div class="about-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3>Our Promise</h3>
                    <p>Every product is officially licensed and held to the highest quality standards before reaching our customers.</p>
                </div>
            </div>

            <!-- Values Section -->
            <div class="values-section">
                <h2 class="text-center mb-5">Our Core Values</h2>
                <div class="values-list">
                    <div class="value-item">
                        <h3>Authenticity</h3>
                        <p>We only sell officially licensed merchandise to ensure quality and support the creators.</p>
                    </div>
                    <div class="value-item">
                        <h3>Passion</h3>
                        <p>We're fans first, and that passion drives everything we do.</p>
                    </div>
                    <div class="value-item">
                        <h3>Community</h3>
                        <p>We're building a space where Honkai fans can connect and celebrate together.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Section -->
    <section class="video-showcase">
        <div class="container-fluid px-0">
            <div class="section-heading text-center mb-5">
                <h2>Our Collection Showcase</h2>
                <p class="subheading">See our premium Honkai Star Rail merchandise in action</p>
            </div>
            <div class="video-container-xxl">
                <iframe src="https://www.youtube.com/embed/HmJtusY2n6E?si=9npATRkf7oS8QY6H" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                </iframe>
                <div class="video-fallback">
                    <a href="https://youtu.be/HmJtusY2n6E?si=9npATRkf7oS8QY6H" 
                       target="_blank" 
                       class="btn btn-gradient">
                        <i class="fas fa-play mr-2"></i> Watch on YouTube
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Community CTA -->
    <section class="community-cta">
        <div class="container">
            <h2>Join Our Trailblazing Journey</h2>
            <p>Connect with fellow Trailblazers in our community</p>
            <div class="social-links">
                <a href="#" class="social-icon"><i class="fab fa-discord"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </section>
</main>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php require_once 'includes/footer.php'; ?>
