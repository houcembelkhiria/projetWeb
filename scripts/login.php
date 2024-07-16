<?php
session_start();
require_once '../classes/User.php';
require_once 'db.php';

// Initialize database connection
$database = new Database();
$pdo = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Find user by username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE upper(username) = upper(:username)");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_data) {
        $user = new User($user_data['id'], $user_data['username'], $user_data['password']);

        if ($user->verifyPassword($password)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user->getUsername();
            $_SESSION['user_id'] = $user->getId(); 
            $_SESSION['user_role'] = $user->getRole(); 
            header('Location: ../pages/welcome.php');
            exit;
        } else {
            echo "<script>alert('Invalid username or password'); window.location.href = '../pages/index.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Invalid username or password'); window.location.href = '../pages/index.php';</script>";
        exit;
    }
}
?>
