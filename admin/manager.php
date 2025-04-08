<?php
// Start session at the very top
session_start();

// Check if user is manager/admin before any output
if (!isset($_SESSION['user_role']) || ($_SESSION['user_role'] !== 'manager' && $_SESSION['user_role'] !== 'admin')) {
    header('Location: /');
    exit;
}

require_once '../config/database.php';
require_once '../includes/header.php';

$db = new Database();
$conn = $db->connect();
// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        // Add product logic
        $name = trim($_POST['name']);
        $price = trim($_POST['price']);
        $category = trim($_POST['category']);
        $description = trim($_POST['description']);
        $stock = trim($_POST['stock']);
        
        // Handle image upload
        $image = 'default.jpg';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/images/products/';
            $image = basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
        }
        
        $stmt = $conn->prepare("INSERT INTO products (name, price, category, description, image, stock) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $price, $category, $description, $image, $stock]);
        
        $_SESSION['message'] = 'Product added successfully!';
        header('Location: manager.php');
        exit;
    } elseif (isset($_POST['update_product'])) {
        // Update product logic
        $id = $_POST['id'];
        $name = trim($_POST['name']);
        $price = trim($_POST['price']);
        $category = trim($_POST['category']);
        $description = trim($_POST['description']);
        $stock = trim($_POST['stock']);
        
        // Handle image update if new image was uploaded
        $image = $_POST['current_image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../assets/images/products/';
            $image = basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
        }
        
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, category = ?, description = ?, image = ?, stock = ? WHERE id = ?");
        $stmt->execute([$name, $price, $category, $description, $image, $stock, $id]);
        
        $_SESSION['message'] = 'Product updated successfully!';
        header('Location: manager.php');
        exit;
    } elseif (isset($_POST['delete_product'])) {
        // Delete product logic
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['message'] = 'Product deleted successfully!';
        header('Location: manager.php');
        exit;
    }
}

// Get all products
$stmt = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Manager | HonkaiShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --space-purple: #2A2356;
            --star-cyan: #4ED8D8;
            --nebula-pink: #FF69B4;
            --dark-space: #0f0f1a;
        }

        .manager-page {
            padding: 4rem 0;
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .manager-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .manager-header h1 {
            color: var(--space-purple);
            font-size: 2.5rem;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .manager-header h1:after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--star-cyan);
            margin: 0.5rem auto;
        }

        .product-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .product-table th {
            background: var(--space-purple);
            color: white;
        }

        .product-table img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .btn-manager {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: rgba(78, 216, 216, 0.1);
            color: var(--space-purple);
        }

        .btn-edit:hover {
            background: var(--star-cyan);
        }

        .btn-delete {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }

        .btn-delete:hover {
            background: #e74c3c;
            color: white;
        }

        .btn-add {
            background: var(--space-purple);
            color: white;
            padding: 0.8rem 1.5rem;
            margin-bottom: 2rem;
        }

        .btn-add:hover {
            background: var(--star-cyan);
            color: var(--space-purple);
        }

        .modal-content {
            border-radius: 15px;
            overflow: hidden;
        }

        .modal-header {
            background: var(--space-purple);
            color: white;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: var(--space-purple);
        }

        .form-control {
            border: 2px solid rgba(42, 35, 86, 0.2);
            border-radius: 8px;
            padding: 0.8rem 1rem;
        }

        .form-control:focus {
            border-color: var(--star-cyan);
            box-shadow: 0 0 0 3px rgba(78, 216, 216, 0.2);
        }

        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="manager-page">
        <div class="container">
            <div class="manager-header">
                <h1>Product Manager</h1>
                <p>Manage your Honkai Star Rail merchandise</p>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['message'] ?>
                    <?php unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <button type="button" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fas fa-plus"></i> Add New Product
            </button>

            <div class="product-table">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><img src="/honkaishop/assets/images/products/<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>"></td>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td>$<?= number_format($product['price'], 2) ?></td>
                                <td><?= ucfirst($product['category']) ?></td>
                                <td><?= $product['stock'] ?></td>
                                <td>
                                    <button type="button" class="btn btn-edit me-2" data-bs-toggle="modal" data-bs-target="#editProductModal" 
                                        data-id="<?= $product['id'] ?>"
                                        data-name="<?= htmlspecialchars($product['name']) ?>"
                                        data-price="<?= $product['price'] ?>"
                                        data-category="<?= $product['category'] ?>"
                                        data-description="<?= htmlspecialchars($product['description']) ?>"
                                        data-image="<?= $product['image'] ?>"
                                        data-stock="<?= $product['stock'] ?>">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-delete" data-bs-toggle="modal" data-bs-target="#deleteProductModal" 
                                        data-id="<?= $product['id'] ?>"
                                        data-name="<?= htmlspecialchars($product['name']) ?>">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Product</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" class="form-control" required>
                                <option value="figures">Figures</option>
                                <option value="apparel">Apparel</option>
                                <option value="accessories">Accessories</option>
                                <option value="collectibles">Collectibles</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Stock Quantity</label>
                            <input type="number" name="stock" class="form-control" min="0" required>
                        </div>
                        <div class="form-group">
                            <label>Product Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="edit_id">
                    <input type="hidden" name="current_image" id="edit_current_image">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" id="edit_price" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" id="edit_category" class="form-control" required>
                                <option value="figures">Figures</option>
                                <option value="apparel">Apparel</option>
                                <option value="accessories">Accessories</option>
                                <option value="collectibles">Collectibles</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Stock Quantity</label>
                            <input type="number" name="stock" id="edit_stock" class="form-control" min="0" required>
                        </div>
                        <div class="form-group">
                            <label>Product Image</label>
                            <input type="file" name="image" class="form-control">
                            <small class="text-muted">Current: <span id="edit_image_name"></span></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="id" id="delete_id">
                    <div class="modal-body">
                        <p>Are you sure you want to delete <strong id="delete_product_name"></strong>?</p>
                        <p class="text-danger">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_product" class="btn btn-danger">Delete Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Edit modal data population
        document.getElementById('editProductModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('edit_id').value = button.getAttribute('data-id');
            document.getElementById('edit_name').value = button.getAttribute('data-name');
            document.getElementById('edit_price').value = button.getAttribute('data-price');
            document.getElementById('edit_category').value = button.getAttribute('data-category');
            document.getElementById('edit_description').value = button.getAttribute('data-description');
            document.getElementById('edit_stock').value = button.getAttribute('data-stock');
            document.getElementById('edit_current_image').value = button.getAttribute('data-image');
            document.getElementById('edit_image_name').textContent = button.getAttribute('data-image');
        });

        // Delete modal data population
        document.getElementById('deleteProductModal').addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('delete_id').value = button.getAttribute('data-id');
            document.getElementById('delete_product_name').textContent = button.getAttribute('data-name');
        });
    </script>
</body>
</html>

<?php include '../includes/footer.php'; ?>