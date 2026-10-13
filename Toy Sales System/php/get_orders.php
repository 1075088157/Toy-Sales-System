<?php
include 'db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$userType = $_SESSION['user_type'];
$userId = $_SESSION['user_id'];

try {
    if ($userType === 'customer') {
        // Customers can only view their own orders
        $sql = "SELECT o.id, o.quantity, o.custom_details, o.payment_method, o.status, 
                       o.estimated_delivery, o.created_at, t.name, t.description, t.price 
                FROM orders o 
                JOIN toys t ON o.toy_id = t.id 
                WHERE o.customer_id = ? 
                ORDER BY o.created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        $orders = $stmt->fetchAll();
        
        if (count($orders) === 0) {
            echo '<p>You don\'t have any orders yet.</p>';
            exit();
        }
        
        foreach ($orders as $order) {
            echo '<div class="order-item">';
            echo '<h3>' . $order['name'] . '</h3>';
            echo '<p>' . $order['description'] . '</p>';
            echo '<div class="toy-price">$' . $order['price'] . ' x ' . $order['quantity'] . '</div>';
            echo '<p><strong>Custom Requirements:</strong> ' . ($order['custom_details'] ?: 'None') . '</p>';
            echo '<p><strong>Payment Method:</strong> ' . $order['payment_method'] . '</p>';
            echo '<p><strong>Order Date:</strong> ' . $order['created_at'] . '</p>';
            echo '<p><strong>Estimated Delivery Date:</strong> ' . $order['estimated_delivery'] . '</p>';
            
            $statusClass = 'status-' . $order['status'];
            echo '<p><strong>Status:</strong> <span class="order-status ' . $statusClass . '">' . $order['status'] . '</span></p>';
            
            echo '</div>';
        }
    } else if ($userType === 'sales') {
        // Sales staff can view all orders
        $sql = "SELECT o.id, o.quantity, o.custom_details, o.payment_method, o.status, 
                       o.estimated_delivery, o.created_at, t.name, t.description, t.price,
                       c.name as customer_name, c.email as customer_email
                FROM orders o 
                JOIN toys t ON o.toy_id = t.id 
                JOIN customers c ON o.customer_id = c.id
                ORDER BY o.created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $orders = $stmt->fetchAll();
        
        if (count($orders) === 0) {
            echo '<p>There are no orders at the moment.</p>';
            exit();
        }
        
        foreach ($orders as $order) {
            echo '<div class="order-item">';
            echo '<h3>' . $order['name'] . ' - Customer: ' . $order['customer_name'] . '</h3>';
            echo '<p>' . $order['description'] . '</p>';
            echo '<div class="toy-price">$' . $order['price'] . ' x ' . $order['quantity'] . '</div>';
            echo '<p><strong>Customer Email:</strong> ' . $order['customer_email'] . '</p>';
            echo '<p><strong>Custom Requirements:</strong> ' . ($order['custom_details'] ?: 'None') . '</p>';
            echo '<p><strong>Payment Method:</strong> ' . $order['payment_method'] . '</p>';
            echo '<p><strong>Order Date:</strong> ' . $order['created_at'] . '</p>';
            
            echo '<div class="form-group">';
            echo '<label for="estimated-delivery-' . $order['id'] . '">Estimated Delivery Date:</label>';
            echo '<input type="date" id="estimated-delivery-' . $order['id'] . '" name="estimated_delivery" value="' . $order['estimated_delivery'] . '">';
            echo '</div>';
            
            echo '<div class="form-group">';
            echo '<label for="status-' . $order['id'] . '">Order Status:</label>';
            echo '<select id="status-' . $order['id'] . '" name="status">';
            echo '<option value="pending" ' . ($order['status'] == 'pending' ? 'selected' : '') . '>Pending</option>';
            echo '<option value="confirmed" ' . ($order['status'] == 'confirmed' ? 'selected' : '') . '>Confirmed</option>';
            echo '<option value="shipped" ' . ($order['status'] == 'shipped' ? 'selected' : '') . '>Shipped</option>';
            echo '<option value="delivered" ' . ($order['status'] == 'delivered' ? 'selected' : '') . '>Delivered</option>';
            echo '</select>';
            echo '</div>';
            
            $statusClass = 'status-' . $order['status'];
            echo '<p><strong>Current Status:</strong> <span class="order-status ' . $statusClass . '">' . $order['status'] . '</span></p>';
            
            echo '<button class="btn update-order-status" data-order-id="' . $order['id'] . '">Update Order Status</button>';
            echo '</div>';
        }
    }
} catch (PDOException $e) {
    echo '<p>Error occurred while retrieving orders.</p>';
}
?>