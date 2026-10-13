<?php
include 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'customer') {
    $customerId = $_SESSION['user_id'];
    $wishlistId = $_POST['wishlistId'];
    $quantity = $_POST['quantity'];
    $customDetails = $_POST['customDetails'];
    
    try {
        // Verify that the wishlist item belongs to the current user
        $sql = "SELECT * FROM wishlist WHERE id = ? AND customer_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$wishlistId, $customerId]);
        $wishlistItem = $stmt->fetch();
        
        if (!$wishlistItem) {
            echo json_encode(['success' => false, 'message' => 'Wishlist item not found or no access permission']);
            exit();
        }
        
        // Update wishlist item
        $sql = "UPDATE wishlist SET quantity = ?, custom_details = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$quantity, $customDetails, $wishlistId]);
        
        echo json_encode(['success' => true, 'message' => 'Wishlist updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to update wishlist: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
}
?>