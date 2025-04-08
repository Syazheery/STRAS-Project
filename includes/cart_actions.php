<?php
require_once '../config/database.php';
require_once '../includes/cart_functions.php';

// Start session securely
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_secure' => isset($_SERVER['HTTPS']),
        'cookie_httponly' => true
    ]);
}

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to manage your cart']);
    exit;
}

$db = new Database();
$conn = $db->connect();
$cartManager = new CartManager($conn);

$user_id = $_SESSION['user_id'];
$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

    try {
        switch ($action) {
            case 'add':
                if ($product_id) {
                    $cartManager->addItem($user_id, $product_id);
                    $response = [
                        'success' => true,
                        'message' => 'Product added to cart',
                        'count' => $cartManager->getItemCount($user_id)
                    ];
                }
                break;
                
            case 'update':
                if ($product_id && $quantity) {
                    $cartManager->updateQuantity($user_id, $product_id, $quantity);
                    $response = [
                        'success' => true,
                        'message' => 'Cart updated',
                        'count' => $cartManager->getItemCount($user_id)
                    ];
                }
                break;
                
            case 'remove':
                if ($product_id) {
                    $cartManager->removeItem($user_id, $product_id);
                    $response = [
                        'success' => true,
                        'message' => 'Item removed from cart',
                        'count' => $cartManager->getItemCount($user_id)
                    ];
                }
                break;
                
            default:
                $response['message'] = 'Invalid action';
        }
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);