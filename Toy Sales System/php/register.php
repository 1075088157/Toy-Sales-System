<?php
include 'db_connection.php';

// Set response header to JSON
header('Content-Type: application/json; charset=utf-8');

// Ensure no output before JSON
ob_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user type
    $userType = isset($_POST['userType']) ? $_POST['userType'] : '';
    
    // Basic validation
    if (empty($userType) || ($userType !== 'customer' && $userType !== 'sales')) {
        echo json_encode(['success' => false, 'message' => 'Invalid user type']);
        ob_end_flush();
        exit();
    }
    
    // Get form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
        ob_end_flush();
        exit();
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        ob_end_flush();
        exit();
    }
    
    // Validate password length
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters long']);
        ob_end_flush();
        exit();
    }
    
    try {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($userType === 'customer') {
            // Customer registration
            
            // Check if email already exists
            $sql = "SELECT id FROM customers WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'This email is already registered']);
                ob_end_flush();
                exit();
            }
            
            // Create customer account
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO customers (name, email, phone, password) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$name, $email, $phone, $hashedPassword]);
            
            if ($result) {
                // Set session variables
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['user_type'] = 'customer';
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Customer registration successful', 
                    'userType' => 'customer',
                    'redirect' => 'customer_dashboard.php'
                ]);
                ob_end_flush();
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Registration failed, please try again later']);
                ob_end_flush();
                exit();
            }
            
        } else if ($userType === 'sales') {
            // Sales staff registration
            
            // Check required fields
            $employeeId = isset($_POST['employee_id']) ? trim($_POST['employee_id']) : '';
            if (empty($employeeId)) {
                echo json_encode(['success' => false, 'message' => 'Please fill in employee ID']);
                ob_end_flush();
                exit();
            }
            
            // Check if email already exists
            $sql = "SELECT id FROM salespersons WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'This email is already registered']);
                ob_end_flush();
                exit();
            }
            
            // Check if employee ID already exists
            $sql = "SELECT id FROM salespersons WHERE employee_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$employeeId]);
            
            if ($stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'This employee ID is already in use']);
                ob_end_flush();
                exit();
            }
            
            // Create sales staff account
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO salespersons (name, email, phone, employee_id, password) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([$name, $email, $phone, $employeeId, $hashedPassword]);
            
            if ($result) {
                // Set session variables
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['user_type'] = 'sales';
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Sales staff registration successful', 
                    'userType' => 'sales',
                    'redirect' => 'sales_dashboard.php'
                ]);
                ob_end_flush();
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Registration failed, please try again later']);
                ob_end_flush();
                exit();
            }
        }
        
    } catch (PDOException $e) {
        // Handle database errors
        error_log("Registration error: " . $e->getMessage());
        
        if ($e->getCode() == 23000) {
            // Unique constraint violation
            echo json_encode(['success' => false, 'message' => 'Email or employee ID is already in use']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error occurred during registration: ' . $e->getMessage()]);
        }
        ob_end_flush();
        exit();
    }
} else {
    // Non-POST request
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    ob_end_flush();
    exit();
}
?>