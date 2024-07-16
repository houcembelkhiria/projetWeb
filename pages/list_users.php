<?php
require_once '../classes/User.php';
require_once '../scripts/db.php';

// Initialize database connection
$database = new Database();
$pdo = $database->getConnection();
// Fetch all users from the database
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/users.css">
    <link rel="stylesheet" href="../style/navbar.css">

    <title>List Users</title>
</head>
<body>
<div class="navbar">
                    <a href="welcome.php">Home</a>
                    <a href="list_users.php">Manage users</a>           
                    <a href="list_properties.php">Manage properties</a> 
                    <a href="Profile.php">Profile</a>           
          
            </div>
    <a id="create" href="create_user.php">Create User</a>
    <table class="userTable" border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['id']); ?></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td>
                <a id="edit" href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                <a id="delete" href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
