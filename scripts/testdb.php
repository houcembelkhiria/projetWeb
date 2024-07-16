<?php
require_once 'db.php';
// test with http://localhost/ProjetWeb/scripts/testdb.php in browser
// Initialize database connection
$database = new Database();
$pdo = $database->getConnection();

if ($pdo) {
    echo "Connected to the database successfully!";
} else {
    echo "Failed to connect to the database.";
}
?>
