<?php
session_start();
require_once '../scripts/db.php';

// Check if property_id is provided in the URL
if (!isset($_GET['property_id']) || !is_numeric($_GET['property_id'])) {
    $_SESSION['error_message'] = 'Invalid property ID.';
    header('Location: list_properties.php');
    exit;
}

// Get property_id from the URL
$property_id = $_GET['property_id'];

// Database connection
$database = new Database();
$pdo = $database->getConnection();

// Delete property from database
$stmt = $pdo->prepare("DELETE FROM properties WHERE property_id = :property_id");
$stmt->bindParam(':property_id', $property_id);

if ($stmt->execute()) {
    $_SESSION['success_message'] = 'Property deleted successfully.';
} else {
    $_SESSION['error_message'] = 'Failed to delete property.';
}

header('Location: list_properties.php');
exit;
?>
