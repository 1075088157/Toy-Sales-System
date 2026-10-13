<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'sales') {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en-HK">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Staff Dashboard - Smiles and Sunshine Toys</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="container">
            <h1>Smiles and Sunshine Toys</h1>
            <nav>
                <ul>
                    <li>Welcome, <?php echo $_SESSION['user_name']; ?></li>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#" id="logoutBtn">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="dashboard">
            <div class="sidebar">
                <h3>Sales Staff Dashboard</h3>
                <ul class="dashboard-nav">
                    <li class="active"><a href="#" data-target="customer-orders">Customer Orders</a></li>
                </ul>
            </div>
            
            <div class="dashboard-content">
                <!-- Customer Orders Section -->
                <div id="customer-orders" class="section active">
                    <h2>Customer Order Management</h2>
                    <div id="customer-orders-container">
                        <!-- Customer orders will be loaded via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2023 Smiles and Sunshine Toys Limited</p>
        </div>
    </footer>

    <script>
        // Automatically load customer orders when page loads
        $(document).ready(function() {
            loadCustomerOrders();
            
            // Logout functionality
            $('#logoutBtn').click(function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to log out?')) {
                    window.location.href = 'logout.php';
                }
            });
        });
        
        // Load sales staff's customer orders
        function loadCustomerOrders() {
            $.ajax({
                url: 'php/get_orders.php',
                type: 'GET',
                data: { userType: 'sales' },
                success: function(response) {
                    $('#customer-orders-container').html(response);
                },
                error: function() {
                    $('#customer-orders-container').html('<p>Error loading customer orders.</p>');
                }
            });
        }
        
        // Update order status
        $(document).on('click', '.update-order-status', function() {
            var orderId = $(this).data('order-id');
            var status = $('#status-' + orderId).val();
            var estimatedDelivery = $('#estimated-delivery-' + orderId).val();
            
            $.ajax({
                url: 'php/update_order_status.php',
                type: 'POST',
                data: { 
                    orderId: orderId,
                    status: status,
                    estimatedDelivery: estimatedDelivery
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Order status updated!');
                        loadCustomerOrders();
                    } else {
                        alert('Failed to update order status: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error occurred while updating order status!');
                }
            });
        });
    </script>
</body>
</html>