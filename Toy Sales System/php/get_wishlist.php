<?php
include 'db_connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$customerId = $_SESSION['user_id'];

try {
    $sql = "SELECT w.id, w.quantity, w.custom_details, t.name, t.description, t.price, t.image_url 
            FROM wishlist w 
            JOIN toys t ON w.toy_id = t.id 
            WHERE w.customer_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$customerId]);
    $wishlistItems = $stmt->fetchAll();
    
    if (count($wishlistItems) === 0) {
        echo '<p>Your wishlist is empty.</p>';
        exit();
    }
    
    foreach ($wishlistItems as $item) {
        echo '<div class="wishlist-item">';
        echo '<h3>' . $item['name'] . '</h3>';
        echo '<p>' . $item['description'] . '</p>';
        echo '<div class="toy-price">$' . $item['price'] . '</div>';
        
        echo '<div class="form-group">';
        echo '<label for="quantity-' . $item['id'] . '">Quantity:</label>';
        echo '<input type="number" id="quantity-' . $item['id'] . '" name="quantity" value="' . $item['quantity'] . '" min="1">';
        echo '</div>';
        
        echo '<div class="form-group">';
        echo '<label for="custom-details-' . $item['id'] . '">Custom Requirements:</label>';
        echo '<textarea id="custom-details-' . $item['id'] . '" name="custom_details">' . $item['custom_details'] . '</textarea>';
        echo '</div>';
        
        echo '<div class="form-group">';
        echo '<label for="payment-method-' . $item['id'] . '">Payment Method:</label>';
        echo '<select id="payment-method-' . $item['id'] . '" name="payment_method">';
        echo '<option value="credit_card">Credit Card</option>';
        echo '<option value="debit_card">Debit Card</option>';
        echo '<option value="paypal">PayPal</option>';
        echo '<option value="bank_transfer">Bank Transfer</option>';
        echo '</select>';
        echo '</div>';
        
        echo '<button class="btn update-wishlist" data-wishlist-id="' . $item['id'] . '">Update Wishlist</button>';
        echo '<button class="btn submit-order" data-wishlist-id="' . $item['id'] . '">Submit Order</button>';
        echo '</div>';
    }
} catch (PDOException $e) {
    echo '<p>Error occurred while retrieving wishlist.</p>';
}
?>