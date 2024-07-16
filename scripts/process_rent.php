<?php
session_start();
require_once '../scripts/db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $property_id = $_POST['property_id'];
    $tenant_id = $_POST['tenant_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Database connection
    $database = new Database();
    $pdo = $database->getConnection();

    // Insert data into rent table
    $stmt = $pdo->prepare("INSERT INTO rent (property_id, tenant_id, start_date, end_date) VALUES (:property_id, :tenant_id, :start_date, :end_date)");
    $stmt->bindParam(':property_id', $property_id);
    $stmt->bindParam(':tenant_id', $tenant_id);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);

    if ($stmt->execute()) {
        echo "Rent added successfully!";
        // Redirect to a success page or back to the welcome page
        header('Location: ../pages/welcome.php');
        exit();
    } else {
        echo "Error adding rent!";
    }
}
?>
