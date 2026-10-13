<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userType = $_POST['userType'];
    
    try {
        if ($userType === 'customer') {
            $sql = "SELECT * FROM customers WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = 'customer';
                $_SESSION['user_name'] = $user['name'];
                
                echo json_encode(['success' => true, 'userType' => 'customer']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
            }
        } else if ($userType === 'sales') {
            $sql = "SELECT * FROM salespersons WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = 'sales';
                $_SESSION['user_name'] = $user['name'];
                
                echo json_encode(['success' => true, 'userType' => 'sales']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
            }
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Login failed: ' . $e->getMessage()]);
    }
}
?>