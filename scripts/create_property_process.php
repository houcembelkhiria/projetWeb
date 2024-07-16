<?php
session_start();
require_once '../classes/User.php';
require_once '../classes/Property.php';
require_once 'db.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $rent_amount = $_POST['rent_amount'];
    $amenities = $_POST['amenities'];
    
    // Handle photo upload
    $photo = $_FILES['photo'];
    $photo_data = file_get_contents($photo['tmp_name']); // Read photo data
        
    
    // Get the logged-in user's ID from session
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        $_SESSION['error_message'] = 'User ID not found in session.';
        header('Location: create_property.php');
        exit;
    }
    // Create a new Property object
    $property = new Property(null, $user_id, $title, $description, $location, $rent_amount, $amenities, $photo_data);
    
    // Database connection
    $database = new Database();
    $pdo = $database->getConnection();
    
    // Prepare SQL statement
    $stmt = $pdo->prepare("INSERT INTO properties (landlord_id, title, description, location, rent_amount, amenities, photo) VALUES (:landlord_id, :title, :description, :location, :rent_amount, :amenities, :photo)");
    
    // Bind parameters
    $stmt->bindParam(':landlord_id', $property->getLandlordId());
    $stmt->bindParam(':title', $property->getTitle());
    $stmt->bindParam(':description', $property->getDescription());
    $stmt->bindParam(':location', $property->getLocation());
    $stmt->bindParam(':rent_amount', $property->getRentAmount());
    $stmt->bindParam(':amenities', $property->getAmenities());
    $stmt->bindParam(':photo', $property->getPhoto(), PDO::PARAM_LOB); // Use PDO::PARAM_LOB for BLOB
    
    // Execute statement
    if ($stmt->execute()) {
        // Property added successfully
        $_SESSION['success_message'] = 'Property added successfully.';
        header('Location: ../pages/list_properties.php');
        exit;
    } else {
        // Error handling
        $_SESSION['error_message'] = 'Failed to add property.';
        header('Location: ../pages/create_property.php');
        exit;
    }
} else {
    // Redirect if accessed directly
    header('Location: ../pages/create_property.php');
    exit;
}
?>
