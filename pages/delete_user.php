<?php
require_once '../classes/User.php';
require_once '../scripts/db.php';

// Initialize database connection
$database = new Database();
$pdo = $database->getConnection();

$id = $_GET['id'];

// Delete user from the database
$stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
$stmt->bindParam(':id', $id);
if ($stmt->execute()) {
    echo "User deleted successfully.";
} else {
    echo "Error deleting user.";
}

// Redirect back to list_users.php after deletion
header('Location: list_users.php');
exit;
?>
