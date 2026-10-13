$(document).ready(function() {
    // Login modal functionality
    $('#loginLink').click(function(e) {
        e.preventDefault();
        $('#loginModal').show();
    });
    
    $('.close').click(function() {
        $('#loginModal').hide();
    });
    
    $(window).click(function(e) {
        if ($(e.target).is('#loginModal')) {
            $('#loginModal').hide();
        }
    });
    
    // Tab functionality
    $('.tab-link').click(function() {
        var tabId = $(this).data('tab');
        
        $('.tab-link').removeClass('active');
        $(this).addClass('active');
        
        $('.tab-content').removeClass('active');
        $('#' + tabId).addClass('active');
    });
    
    // Customer registration form submission
    $('#customerForm').submit(function(e) {
        e.preventDefault();
        
        var password = $('#customerPassword').val();
        var confirmPassword = $('#confirmPassword').val();
        
        if (password !== confirmPassword) {
            alert('Passwords do not match!');
            return;
        }
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: 'php/register.php',
            type: 'POST',
            data: formData + '&userType=customer',
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    alert('Registration successful!');
                    window.location.href = 'customer_dashboard.php';
                } else {
                    alert('Registration failed: ' + result.message);
                }
            },
            error: function() {
                alert('Error occurred during registration!');
            }
        });
    });
    
    // Sales staff registration form submission
    $('#salesForm').submit(function(e) {
        e.preventDefault();
        
        var password = $('#salesPassword').val();
        var confirmPassword = $('#salesConfirmPassword').val();
        
        if (password !== confirmPassword) {
            alert('Passwords do not match!');
            return;
        }
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: 'php/register.php',
            type: 'POST',
            data: formData + '&userType=sales',
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    alert('Registration successful!');
                    window.location.href = 'sales_dashboard.php';
                } else {
                    alert('Registration failed: ' + result.message);
                }
            },
            error: function() {
                alert('Error occurred during registration!');
            }
        });
    });
    
    // Login form submission
    $('#loginForm').submit(function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: 'php/login.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    alert('Login successful!');
                    if (result.userType === 'customer') {
                        window.location.href = 'customer_dashboard.php';
                    } else {
                        window.location.href = 'sales_dashboard.php';
                    }
                } else {
                    alert('Login failed: ' + result.message);
                }
            },
            error: function() {
                alert('Error occurred during login!');
            }
        });
    });
    
    // Dashboard navigation
    $('.dashboard-nav a').click(function(e) {
        e.preventDefault();
        var target = $(this).data('target');
        
        $('.dashboard-nav li').removeClass('active');
        $(this).parent().addClass('active');
        
        $('.section').removeClass('active');
        $('#' + target).addClass('active');
        
        // Load corresponding content based on tab
        if (target === 'toys') {
            loadToys();
        } else if (target === 'wishlist') {
            loadWishlist();
        } else if (target === 'orders') {
            loadOrders();
        } else if (target === 'customer-orders') {
            loadCustomerOrders();
        }
    });
    
    // Load toys list
    function loadToys() {
        $.ajax({
            url: 'php/get_toys.php',
            type: 'GET',
            success: function(response) {
                $('#toys-container').html(response);
            },
            error: function() {
                $('#toys-container').html('<p>Error loading toys list.</p>');
            }
        });
    }
    
    // Load wishlist
    function loadWishlist() {
        $.ajax({
            url: 'php/get_wishlist.php',
            type: 'GET',
            success: function(response) {
                $('#wishlist-container').html(response);
            },
            error: function() {
                $('#wishlist-container').html('<p>Error loading wishlist.</p>');
            }
        });
    }
    
    // Load customer orders
    function loadOrders() {
        $.ajax({
            url: 'php/get_orders.php',
            type: 'GET',
            data: { userType: 'customer' },
            success: function(response) {
                $('#orders-container').html(response);
            },
            error: function() {
                $('#orders-container').html('<p>Error loading orders.</p>');
            }
        });
    }
    
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
    
    // Add to wishlist
    $(document).on('click', '.add-to-wishlist', function() {
        var toyId = $(this).data('toy-id');
        
        $.ajax({
            url: 'php/add_to_wishlist.php',
            type: 'POST',
            data: { toyId: toyId },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    alert('Added to wishlist!');
                } else {
                    alert('Failed to add to wishlist: ' + result.message);
                }
            },
            error: function() {
                alert('Error occurred while adding to wishlist!');
            }
        });
    });
    
    // Update wishlist item
    $(document).on('click', '.update-wishlist', function() {
        var wishlistId = $(this).data('wishlist-id');
        var quantity = $('#quantity-' + wishlistId).val();
        var customDetails = $('#custom-details-' + wishlistId).val();
        
        $.ajax({
            url: 'php/update_wishlist.php',
            type: 'POST',
            data: { 
                wishlistId: wishlistId,
                quantity: quantity,
                customDetails: customDetails
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    alert('Wishlist updated!');
                    loadWishlist();
                } else {
                    alert('Failed to update wishlist: ' + result.message);
                }
            },
            error: function() {
                alert('Error occurred while updating wishlist!');
            }
        });
    });
    
    // Submit order
    $(document).on('click', '.submit-order', function() {
        var wishlistId = $(this).data('wishlist-id');
        var paymentMethod = $('#payment-method-' + wishlistId).val();
        
        $.ajax({
            url: 'php/submit_order.php',
            type: 'POST',
            data: { 
                wishlistId: wishlistId,
                paymentMethod: paymentMethod
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    alert('Order submitted!');
                    loadWishlist();
                    loadOrders();
                } else {
                    alert('Failed to submit order: ' + result.message);
                }
            },
            error: function() {
                alert('Error occurred while submitting order!');
            }
        });
    });
    
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
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    alert('Order status updated!');
                    loadCustomerOrders();
                } else {
                    alert('Failed to update order status: ' + result.message);
                }
            },
            error: function() {
                alert('Error occurred while updating order status!');
            }
        });
    });
});