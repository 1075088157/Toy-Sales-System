<?php
session_start();
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en-HK">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smiles and Sunshine Toys - Home</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="container">
            <h1>Smiles and Sunshine Toys</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="register.html">Register</a></li>
                    <li><a href="#" id="loginLink">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <h2>Customize Exclusive Toys, Create Unique Memories</h2>
                <p>We provide high-quality custom toys, giving every child a unique playmate</p>
                <a href="register.html" class="btn">Register Now</a>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <h2>Our Services</h2>
                <div class="feature-grid">
                    <div class="feature">
                        <h3>Online Customization</h3>
                        <p>Easily browse and customize exclusive toys to create unique memories</p>
                    </div>
                    <div class="feature">
                        <h3>Fast Service</h3>
                        <p>We promise quick order processing to ensure you receive products promptly</p>
                    </div>
                    <div class="feature">
                        <h3>Order Tracking</h3>
                        <p>Check order status anytime to stay updated on the latest progress</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Login</h2>
            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="userType">User Type:</label>
                    <select id="userType" name="userType" required>
                        <option value="customer">Customer</option>
                        <option value="sales">Sales Staff</option>
                    </select>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2023 Smiles and Sunshine Toys Limited</p>
        </div>
    </footer>

    <script>
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
            
            // Login form submission
            $('#loginForm').submit(function(e) {
                e.preventDefault();
                
                var formData = $(this).serialize();
                
                $.ajax({
                    url: 'php/login.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        try {
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
                        } catch (e) {
                            alert('Error occurred during login!');
                        }
                    },
                    error: function() {
                        alert('Error occurred during login!');
                    }
                });
            });
        });
    </script>
</body>
</html>