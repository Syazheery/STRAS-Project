<?php
class CartManager {
    private $conn;
    
    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }
    
    public function addItem($user_id, $product_id, $quantity = 1) {
        $this->validateProduct($product_id, $quantity);
        
        // Check if item already exists in cart
        $stmt = $this->conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        
        if ($stmt->rowCount() > 0) {
            // Update existing item
            $stmt = $this->conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$quantity, $user_id, $product_id]);
        } else {
            // Add new item
            $stmt = $this->conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $product_id, $quantity]);
        }
        
        return true;
    }
    
    public function updateQuantity($user_id, $product_id, $quantity) {
        $this->validateProduct($product_id, $quantity);
        
        $stmt = $this->conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        return $stmt->execute([$quantity, $user_id, $product_id]);
    }
    
    public function removeItem($user_id, $product_id) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        return $stmt->execute([$user_id, $product_id]);
    }
    
    public function getCartItems($user_id) {
        $query = "SELECT p.id, p.name, p.price, p.image, p.stock, c.quantity 
                  FROM cart c
                  JOIN products p ON c.product_id = p.id
                  WHERE c.user_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        
        $cart_items = [];
        $subtotal = 0;
        
        while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $total = $item['price'] * $item['quantity'];
            $subtotal += $total;
            
            $cart_items[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'image' => $item['image'],
                'quantity' => $item['quantity'],
                'max_quantity' => $item['stock'],
                'total' => $total
            ];
        }
        
        return [
            'items' => $cart_items,
            'subtotal' => $subtotal,
            'count' => $this->getItemCount($user_id)
        ];
    }
    
    public function getItemCount($user_id) {
        $stmt = $this->conn->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return (int)$stmt->fetchColumn();
    }
    
    private function validateProduct($product_id, $quantity) {
        // ... (keep your existing validation code)
    }
}
?>