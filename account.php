<?php
require_once 'config/database.php';
session_start();

// Redirect if not logged in
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = new Database();
$conn = $db->connect();

// Get user info
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get user orders (limit to 3 for cleaner display)
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 3");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<style>
    :root {
        --space-purple: #2A2356;
        --star-cyan: #4ED8D8;
        --nebula-pink: #FF69B4;
        --dark-space: #0f0f1a;
    }

    .account-section {
        background: white;
        padding: 3rem 0;
        min-height: 100vh;
    }

    .account-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .account-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 3rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(42, 35, 86, 0.1);
    }

    .account-header h1 {
        color: var(--space-purple);
        font-size: 2.5rem;
    }

    .btn-danger {
        padding: 0.8rem 1.8rem;
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 50px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-danger:hover {
        background: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .account-content {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 3rem;
    }

    .account-sidebar {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        padding: 1.5rem;
        height: fit-content;
    }

    .account-sidebar ul {
        list-style: none;
    }

    .account-sidebar li {
        margin-bottom: 1rem;
    }

    .account-sidebar a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0.8rem 1rem;
        color: var(--space-purple);
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .account-sidebar a:hover {
        background: rgba(78, 216, 216, 0.1);
        color: var(--star-cyan);
        transform: translateX(5px);
    }

    .account-sidebar a.active {
        background: rgba(78, 216, 216, 0.2);
        color: var(--star-cyan);
        font-weight: bold;
    }

    .account-sidebar i {
        width: 20px;
        text-align: center;
        color: var(--star-cyan);
    }

    .account-main {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        padding: 2rem;
    }

    .section-title {
        color: var(--space-purple);
        font-size: 2rem;
        margin-bottom: 2rem;
        position: relative;
        padding-bottom: 0.5rem;
    }

    .section-title:after {
        content: '';
        display: block;
        width: 80px;
        height: 4px;
        background: var(--star-cyan);
        margin-top: 0.5rem;
    }

    .orders-list {
        display: grid;
        gap: 1.5rem;
    }

    .order-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 1px solid rgba(42, 35, 86, 0.1);
    }

    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid rgba(42, 35, 86, 0.1);
    }

    .order-header h3 {
        color: var(--space-purple);
        margin-bottom: 0.5rem;
    }

    .order-header p {
        color: rgba(42, 35, 86, 0.7);
        font-size: 0.9rem;
    }

    .order-status {
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: bold;
    }

    .order-status.pending {
        background: rgba(241, 196, 15, 0.1);
        color: #f1c40f;
    }

    .order-status.completed {
        background: rgba(46, 204, 113, 0.1);
        color: #2ecc71;
    }

    .order-status.processing {
        background: rgba(52, 152, 219, 0.1);
        color: #3498db;
    }

    .order-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
    }

    .order-footer p {
        color: var(--space-purple);
        font-weight: bold;
        font-size: 1.1rem;
    }

    .btn {
        padding: 0.8rem 1.5rem;
        background: var(--space-purple);
        color: white;
        border: none;
        border-radius: 50px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn:hover {
        background: var(--star-cyan);
        color: var(--space-purple);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(78, 216, 216, 0.4);
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background: rgba(42, 35, 86, 0.03);
        border-radius: 10px;
        border: 2px dashed rgba(42, 35, 86, 0.1);
    }

    .empty-state i {
        font-size: 3rem;
        color: rgba(42, 35, 86, 0.1);
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        color: var(--space-purple);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: rgba(42, 35, 86, 0.7);
        margin-bottom: 1.5rem;
    }

    @media (max-width: 992px) {
        .account-content {
            grid-template-columns: 1fr;
        }

        .account-sidebar {
            margin-bottom: 2rem;
        }
    }

    @media (max-width: 768px) {
        .account-header h1 {
            font-size: 2rem;
        }

        .order-header, .order-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .account-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }
</style>

<section class="account-section">
    <div class="account-container">
        <div class="account-header">
            <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>
            <a href="logout.php" class="btn-danger">Logout</a>
        </div>
        
        <div class="account-content">
            <div class="account-sidebar">
                <ul>
                    <li><a href="account.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="cart.php"><i class="fas fa-box-open"></i> My Orders</a></li>
                    <li><a href="contact.php"><i class="fas fa-question-circle"></i> Support</a></li>
                </ul>
            </div>
            
            <div class="account-main">
                <h2 class="section-title">Recent Orders</h2>
                
                <?php if(count($orders) > 0): ?>
                    <div class="orders-list">
                        <?php foreach ($orders as $order): ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <div>
                                        <h3>Order #<?php echo $order['id']; ?></h3>
                                        <p>Placed on <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                                    </div>
                                    <div>
                                        <span class="order-status <?php echo strtolower($order['status']); ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="order-footer">
                                    <p>Total: $<?php echo number_format($order['total'], 2); ?></p>
                                    <a href="order.php?id=<?php echo $order['id']; ?>" class="btn">View Details</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div style="text-align: center; margin-top: 2rem;">
                        <a href="orders.php" class="btn">View All Orders</a>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <h3>No Orders Yet</h3>
                        <p>Your order history will appear here once you make a purchase</p>
                        <a href="/honkaishop/products/" class="btn">Start Shopping</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
