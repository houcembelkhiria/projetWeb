<?php
session_start();
require_once '../classes/User.php';
require_once '../scripts/db.php';

// Initialize database connection
$database = new Database();
$pdo = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password
    $hashedPassword = hash('sha256', $password);

    // Insert user into the database
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('User created successfully.'); window.location.href = 'list_users.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error creating user.'); window.location.href = 'create_user.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/users.css">
    <link rel="stylesheet" href="../style/navbar.css">
    <title>Create User</title>
</head>
<body>
<div class="navbar">
                    <a href="welcome.php">Home</a>
                    <a href="list_users.php">Manage users</a>    
                    <a href="list_properties.php">Manage properties</a>           
                    <a href="Profile.php">Profile</a>           
       
            </div>
    <h1>Create User</h1>
    <div class="form-container">
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <div class="buttons">
            <button type="submit">Create User</button>
            <button type="button" onclick="window.location.href = 'list_users.php';" id="cancel">Cancel</button>
            </div>
    </form>
    </div>
</body>
</html>
