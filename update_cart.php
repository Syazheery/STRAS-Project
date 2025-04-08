<?php
require_once 'config/database.php';
require_once 'includes/cart_functions.php';

initCart();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->connect();
    
    $product_id = $_POST['product_id'] ?? null;
    
    if ($product_id) {
        if (isset($_POST['update'])) {
            $quantity = (int)($_POST['quantity'] ?? 1);
            if ($quantity > 0) {
                $_SESSION['cart'][$product_id] = $quantity;
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
        } 
        elseif (isset($_POST['remove'])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
}

header('Location: cart.php');
exit;
?>
