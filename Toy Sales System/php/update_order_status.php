<?php
include 'db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'sales') {
    $orderId = $_POST['orderId'];
    $status = $_POST['status'];
    $estimatedDelivery = $_POST['estimatedDelivery'];
    
    try {
        // Update order status and estimated delivery date
        $sql = "UPDATE orders SET status = ?, estimated_delivery = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$status, $estimatedDelivery, $orderId]);
        
        echo json_encode(['success' => true, 'message' => 'Order status updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to update order status: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
}
?>