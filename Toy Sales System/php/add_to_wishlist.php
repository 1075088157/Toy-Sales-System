<?php
include 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'customer') {
    $customerId = $_SESSION['user_id'];
    $toyId = $_POST['toyId'];
    
    try {
        // Check if already exists in wishlist
        $sql = "SELECT * FROM wishlist WHERE customer_id = ? AND toy_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$customerId, $toyId]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            echo json_encode(['success' => false, 'message' => 'This toy is already in your wishlist']);
        } else {
            $sql = "INSERT INTO wishlist (customer_id, toy_id) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$customerId, $toyId]);
            
            echo json_encode(['success' => true, 'message' => 'Added to wishlist successfully']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to add to wishlist: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
}
?>