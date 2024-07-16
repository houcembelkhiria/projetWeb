<?php
require_once '../classes/User.php';
require_once '../scripts/db.php';

// Initialize database connection
$database = new Database();
$pdo = $database->getConnection();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password
    $hashedPassword = hash('sha256', $password);

    // Update user in the database
    $stmt = $pdo->prepare("UPDATE users SET username = :username, password = :password WHERE id = :id");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        echo "<script>alert('User update successfully.'); window.location.href = 'list_users.php';</script>";
    } else {
        echo "Error updating user.";
    }
} else {
    $id = $_GET['id'];

    // Fetch user from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/users.css">
    <link rel="stylesheet" href="../style/navbar.css">

    <title>Edit User</title>
</head>
<body>
            <div class="navbar">
                    <a href="welcome.php">Home</a>
                    <a href="list_users.php">Manage users</a>    
                    <a href="list_properties.php">Manage properties</a>    
                    <a href="Profile.php">Profile</a>           
       
       
            </div>

    <h1>Edit User</h1>
    <div class="form-container">
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <div class="buttons">
            <button type="submit">Update User</button>
            <button type="button" onclick="window.location.href = 'list_users.php';" id="cancel">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>
