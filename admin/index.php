<?php
require_once '../config/database.php';
session_start();

// Redirect if not admin
if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header('Location: /honkaishop/');
    exit;
}

$db = new Database();
$conn = $db->connect();

// Get stats
$stmt = $conn->query("SELECT COUNT(*) as total_products FROM products");
$total_products = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'];

$stmt = $conn->query("SELECT COUNT(*) as total_orders FROM orders");
$total_orders = $stmt->fetch(PDO::FETCH_ASSOC)['total_orders'];

$stmt = $conn->query("SELECT COUNT(*) as total_users FROM users");
$total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

$stmt = $conn->query("SELECT SUM(total) as revenue FROM orders WHERE status = 'completed'");
$revenue = $stmt->fetch(PDO::FETCH_ASSOC)['revenue'];

include '../includes/header.php';
?>

<section class="admin-section">
    <div class="container">
        <div class="admin-header">
            <h1>Admin Dashboard</h1>
            <nav class="admin-nav">
                <ul>
                    <li><a href="index.php" class="active">Dashboard</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="orders.php">Orders</a></li>
                    <li><a href="users.php">Users</a></li>
                </ul>
            </nav>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Products</h3>
                <p><?php echo $total_products; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Orders</h3>
                <p><?php echo $total_orders; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Users</h3>
                <p><?php echo $total_users; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Revenue</h3>
                <p>$<?php echo number_format($revenue, 2); ?></p>
            </div>
        </div>
        
        <div class="recent-orders">
            <h2>Recent Orders</h2>
            <?php
            $stmt = $conn->query("SELECT o.id, o.total, o.status, o.created_at, u.username 
                                 FROM orders o 
                                 JOIN users u ON o.user_id = u.id 
                                 ORDER BY o.created_at DESC LIMIT 5");
            $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo $order['username']; ?></td>
                            <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                            <td>$<?php echo number_format($order['total'], 2); ?></td>
                            <td><span class="status-badge <?php echo strtolower($order['status']); ?>"><?php echo ucfirst($order['status']); ?></span></td>
                            <td><a href="order.php?id=<?php echo $order['id']; ?>" class="btn btn-small">View</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>