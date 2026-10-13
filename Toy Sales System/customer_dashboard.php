<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en-HK">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - Smiles and Sunshine Toys</title>
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
                <h3>Customer Dashboard</h3>
                <ul class="dashboard-nav">
                    <li class="active"><a href="#" data-target="toys">Browse Toys</a></li>
                    <li><a href="#" data-target="wishlist">My Wishlist</a></li>
                    <li><a href="#" data-target="orders">My Orders</a></li>
                </ul>
            </div>
            
            <div class="dashboard-content">
                <!-- Browse Toys Section -->
                <div id="toys" class="section active">
                    <h2>Browse Toys</h2>
                    
                    <!-- Search and Filter Area -->
                    <div class="search-filter" style="margin-bottom: 20px; display: flex; align-items: center;">
                        <div class="form-group" style="flex: 1; margin-right: 10px;">
                            <input type="text" id="searchInput" placeholder="Search toy name or description..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
                        <button class="btn" id="searchBtn" style="padding: 10px 20px;">Search</button>
                        <button class="btn" id="clearSearchBtn" style="padding: 10px 20px; margin-left: 10px; background-color: #6c757d;">Clear</button>
                    </div>
                    
                    <!-- Search Results Info -->
                    <div id="searchInfo" style="margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; display: none;"></div>
                    
                    <!-- Loading Animation -->
                    <div class="loading" id="toysLoading">
                        <div class="spinner"></div>
                        <p>Loading...</p>
                    </div>
                    
                    <!-- Toys List -->
                    <div class="toys-grid" id="toys-container">
                        <!-- Toys will be loaded via AJAX -->
                    </div>
                    
                    <!-- Pagination Controls -->
                    <div class="pagination" id="pagination">
                        <!-- Pagination will be generated via JavaScript -->
                    </div>
                </div>
                
                <!-- Wishlist Section -->
                <div id="wishlist" class="section">
                    <h2>My Wishlist</h2>
                    
                    <!-- Loading Animation -->
                    <div class="loading" id="wishlistLoading">
                        <div class="spinner"></div>
                        <p>Loading...</p>
                    </div>
                    
                    <div id="wishlist-container">
                        <!-- Wishlist will be loaded via AJAX -->
                    </div>
                </div>
                
                <!-- Orders Section -->
                <div id="orders" class="section">
                    <h2>My Orders</h2>
                    
                    <!-- Loading Animation -->
                    <div class="loading" id="ordersLoading">
                        <div class="spinner"></div>
                        <p>Loading...</p>
                    </div>
                    
                    <div id="orders-container">
                        <!-- Orders will be loaded via AJAX -->
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
        // Current search state
        var currentSearch = '';
        
        // Automatically load toys when page loads
        $(document).ready(function() {
            loadToys(1);
            
            // Logout functionality
            $('#logoutBtn').click(function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to log out?')) {
                    window.location.href = 'logout.php';
                }
            });
            
            // Search functionality
            $('#searchBtn').click(function() {
                currentSearch = $('#searchInput').val().trim();
                loadToys(1);
            });
            
            // Clear search
            $('#clearSearchBtn').click(function() {
                $('#searchInput').val('');
                currentSearch = '';
                loadToys(1);
            });
            
            // Search on Enter key
            $('#searchInput').keypress(function(e) {
                if (e.which === 13) {
                    currentSearch = $('#searchInput').val().trim();
                    loadToys(1);
                }
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
                    loadToys(1);
                } else if (target === 'wishlist') {
                    loadWishlist();
                } else if (target === 'orders') {
                    loadOrders();
                }
            });
        });
        
        // Load toys list
        function loadToys(page = 1) {
            $('#toysLoading').addClass('active');
            $('#searchInfo').hide();
            
            $.ajax({
                url: 'php/get_toys.php',
                type: 'GET',
                data: { 
                    page: page,
                    search: currentSearch
                },
                success: function(response) {
                    $('#toys-container').html(response);
                    generatePagination(page);
                    
                    // Display search info
                    if (currentSearch) {
                        $('#searchInfo').html('Search results: "' + currentSearch + '"').show();
                    }
                    
                    $('#toysLoading').removeClass('active');
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    $('#toys-container').html(
                        '<div style="text-align: center; padding: 2rem; color: #d9534f;">' +
                        '<p>Error loading toys list.</p>' +
                        '<p>Please refresh the page or try again later.</p>' +
                        '</div>'
                    );
                    $('#toysLoading').removeClass('active');
                }
            });
        }
        
        // Generate pagination controls
        function generatePagination(currentPage) {
            var paginationData = $('#pagination-data');
            var totalPages = parseInt(paginationData.data('total-pages')) || 1;
            currentPage = parseInt(currentPage) || 1;
            
            if (totalPages <= 1) {
                $('#pagination').html('');
                return;
            }
            
            var paginationHtml = '';
            
            // Previous page button
            if (currentPage > 1) {
                paginationHtml += '<button class="pagination-btn" onclick="loadToys(' + (currentPage - 1) + ')">Previous</button>';
            } else {
                paginationHtml += '<button class="pagination-btn" disabled>Previous</button>';
            }
            
            // Page number info
            paginationHtml += '<span class="pagination-info">Page ' + currentPage + ' of ' + totalPages + '</span>';
            
            // Next page button
            if (currentPage < totalPages) {
                paginationHtml += '<button class="pagination-btn" onclick="loadToys(' + (currentPage + 1) + ')">Next</button>';
            } else {
                paginationHtml += '<button class="pagination-btn" disabled>Next</button>';
            }
            
            // Page jump
            paginationHtml += '<div style="display: inline-block; margin-left: 20px;">';
            paginationHtml += '<span>Jump to</span>';
            paginationHtml += '<input type="number" class="pagination-input" id="jumpPage" min="1" max="' + totalPages + '" value="' + currentPage + '">';
            paginationHtml += '<span>page</span>';
            paginationHtml += '<button class="pagination-jump" onclick="jumpToPage()">Go</button>';
            paginationHtml += '</div>';
            
            $('#pagination').html(paginationHtml);
            
            // Jump on Enter key
            $('#jumpPage').keypress(function(e) {
                if (e.which === 13) {
                    jumpToPage();
                }
            });
        }
        
        // Jump to specified page
        function jumpToPage() {
            var page = parseInt($('#jumpPage').val());
            var totalPages = parseInt($('#pagination-data').data('total-pages')) || 1;
            
            if (isNaN(page) || page < 1) {
                page = 1;
            } else if (page > totalPages) {
                page = totalPages;
            }
            
            loadToys(page);
        }
        
        // Load wishlist
        function loadWishlist() {
            $('#wishlistLoading').addClass('active');
            
            $.ajax({
                url: 'php/get_wishlist.php',
                type: 'GET',
                success: function(response) {
                    $('#wishlist-container').html(response);
                    $('#wishlistLoading').removeClass('active');
                },
                error: function() {
                    $('#wishlist-container').html('<p>Error loading wishlist.</p>');
                    $('#wishlistLoading').removeClass('active');
                }
            });
        }
        
        // Load customer orders
        function loadOrders() {
            $('#ordersLoading').addClass('active');
            
            $.ajax({
                url: 'php/get_orders.php',
                type: 'GET',
                data: { userType: 'customer' },
                success: function(response) {
                    $('#orders-container').html(response);
                    $('#ordersLoading').removeClass('active');
                },
                error: function() {
                    $('#orders-container').html('<p>Error loading orders.</p>');
                    $('#ordersLoading').removeClass('active');
                }
            });
        }
        
        // Add to wishlist
        $(document).on('click', '.add-to-wishlist', function() {
            var toyId = $(this).data('toy-id');
            var button = $(this);
            
            // Disable button to prevent duplicate clicks
            button.prop('disabled', true).text('Adding...');
            
            $.ajax({
                url: 'php/add_to_wishlist.php',
                type: 'POST',
                data: { toyId: toyId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Added to wishlist!');
                    } else {
                        alert('Failed to add to wishlist: ' + response.message);
                    }
                    button.prop('disabled', false).text('Add to Wishlist');
                },
                error: function() {
                    alert('Error occurred while adding to wishlist!');
                    button.prop('disabled', false).text('Add to Wishlist');
                }
            });
        });
        
        // Update wishlist item
        $(document).on('click', '.update-wishlist', function() {
            var wishlistId = $(this).data('wishlist-id');
            var quantity = $('#quantity-' + wishlistId).val();
            var customDetails = $('#custom-details-' + wishlistId).val();
            var button = $(this);
            
            button.prop('disabled', true).text('Updating...');
            
            $.ajax({
                url: 'php/update_wishlist.php',
                type: 'POST',
                data: { 
                    wishlistId: wishlistId,
                    quantity: quantity,
                    customDetails: customDetails
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Wishlist updated!');
                        loadWishlist();
                    } else {
                        alert('Failed to update wishlist: ' + response.message);
                    }
                    button.prop('disabled', false).text('Update Wishlist');
                },
                error: function() {
                    alert('Error occurred while updating wishlist!');
                    button.prop('disabled', false).text('Update Wishlist');
                }
            });
        });
        
        // Submit order
        $(document).on('click', '.submit-order', function() {
            var wishlistId = $(this).data('wishlist-id');
            var paymentMethod = $('#payment-method-' + wishlistId).val();
            var button = $(this);
            
            if (!paymentMethod) {
                alert('Please select a payment method!');
                return;
            }
            
            button.prop('disabled', true).text('Submitting...');
            
            $.ajax({
                url: 'php/submit_order.php',
                type: 'POST',
                data: { 
                    wishlistId: wishlistId,
                    paymentMethod: paymentMethod
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Order submitted!');
                        loadWishlist();
                        loadOrders();
                    } else {
                        alert('Failed to submit order: ' + response.message);
                    }
                    button.prop('disabled', false).text('Submit Order');
                },
                error: function() {
                    alert('Error occurred while submitting order!');
                    button.prop('disabled', false).text('Submit Order');
                }
            });
        });
    </script>
</body>
</html>