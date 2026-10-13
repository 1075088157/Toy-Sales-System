<?php
include 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'customer') {
    $customerId = $_SESSION['user_id'];
    $wishlistId = $_POST['wishlistId'];
    $paymentMethod = $_POST['paymentMethod'];
    
    try {
        // Get wishlist item details
        $sql = "SELECT w.customer_id, w.toy_id, w.quantity, w.custom_details, t.price 
                FROM wishlist w 
                JOIN toys t ON w.toy_id = t.id 
                WHERE w.id = ? AND w.customer_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$wishlistId, $customerId]);
        $wishlistItem = $stmt->fetch();
        
        if (!$wishlistItem) {
            echo json_encode(['success' => false, 'message' => 'Wishlist item not found or no access permission']);
            exit();
        }
        
        // Calculate estimated delivery date (7 days from now)
        $estimatedDelivery = date('Y-m-d', strtotime('+7 days'));
        
        // Create order
        $sql = "INSERT INTO orders (customer_id, toy_id, quantity, custom_details, payment_method, estimated_delivery) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $customerId, 
            $wishlistItem['toy_id'], 
            $wishlistItem['quantity'], 
            $wishlistItem['custom_details'], 
            $paymentMethod,
            $estimatedDelivery
        ]);
        
        // Remove item from wishlist
        $sql = "DELETE FROM wishlist WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$wishlistId]);
        
        echo json_encode(['success' => true, 'message' => 'Order submitted successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to submit order: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
}
?>