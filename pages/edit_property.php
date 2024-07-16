<?php
session_start();
require_once '../scripts/db.php';
require_once '../classes/Property.php'; // Include the Property class

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

// Fetch property details from database
$stmt = $pdo->prepare("SELECT * FROM properties WHERE property_id = :property_id");
$stmt->bindParam(':property_id', $property_id);
$stmt->execute();
$propertyData = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if property exists
if (!$propertyData) {
    $_SESSION['error_message'] = 'Property not found.';
    header('Location: list_properties.php');
    exit;
}

// Initialize Property object with fetched data
$property = new Property(
    $propertyData['property_id'],
    $propertyData['landlord_id'],
    $propertyData['title'],
    $propertyData['description'],
    $propertyData['location'],
    $propertyData['rent_amount'],
    $propertyData['amenities'],
    $propertyData['photo']
);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $rent_amount = $_POST['rent_amount'];
    $amenities = $_POST['amenities'];

    // Update property object with new data
    $property->setTitle($title);
    $property->setDescription($description);
    $property->setLocation($location);
    $property->setRentAmount($rent_amount);
    $property->setAmenities($amenities);

    // Update property in database
    $stmt = $pdo->prepare("UPDATE properties SET title = :title, description = :description, location = :location, rent_amount = :rent_amount, amenities = :amenities WHERE property_id = :property_id");

    // Bind parameters
    $stmt->bindParam(':title', $property->getTitle());
    $stmt->bindParam(':description', $property->getDescription());
    $stmt->bindParam(':location', $property->getLocation());
    $stmt->bindParam(':rent_amount', $property->getRentAmount());
    $stmt->bindParam(':amenities', $property->getAmenities());
    $stmt->bindParam(':property_id', $property->getPropertyId());

    // Execute statement
    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Property updated successfully.';
        header('Location: list_properties.php');
        exit;
    } else {
        $_SESSION['error_message'] = 'Failed to update property.';
        header("Location: edit_property.php?property_id={$property->getPropertyId()}");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Property</title>
    <link rel="stylesheet" href="../style/users.css">
    <link rel="stylesheet" href="../style/navbar.css">
    <style>
        /* Add your custom styles here */
    </style>
</head>
<body>
    <div class="navbar">
        <a href="welcome.php">Home</a>
        <a href="list_users.php">Manage users</a>
        <a href="list_properties.php">Manage properties</a>
        <a href="profile.php">Profile</a>
        
    </div>

    <div class="form-container">

    <form method="POST">
        <input type="hidden" name="property_id" value="<?php echo $property->getPropertyId(); ?>">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($property->getTitle()); ?>" required><br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($property->getDescription()); ?></textarea><br>
        <label for="location">Location:</label>
        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($property->getLocation()); ?>" required><br>
        <label for="rent_amount">Rent Amount:</label>
        <input type="number" id="rent_amount" name="rent_amount" value="<?php echo htmlspecialchars($property->getRentAmount()); ?>" required><br>
        <div class="buttons">
        <button type="submit" >Update Property</button>
        <button type="button" onclick="window.location.href = 'list_properties.php';" id="cancel">Cancel</button>
        </div>    </form>
</div>
</body>
</html>
