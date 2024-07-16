<?php
session_start();
require_once '../scripts/db.php';
require_once '../classes/Property.php'; // Include the Property class

// Ensure user is logged in and retrieve user ID
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../pages/index.php');
    exit;
}
$user_id = $_SESSION['user_id']; // Adjust this based on your session variable storing user ID

// Database connection
$database = new Database();
$pdo = $database->getConnection();

// Fetch properties from database for the logged-in user
$stmt = $pdo->prepare("SELECT * FROM properties WHERE landlord_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List Properties</title>
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
    <a href="Profile.php">Profile</a>           

</div>

<table class="userTable" border="1">
    <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Location</th>
            <th>Rent Amount</th>
            <th>Photo</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($properties as $propertyData): ?>
            <?php
            // Create a Property object for each row of data
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
            ?>
            <tr>
                <td><?php echo htmlspecialchars($property->getTitle()); ?></td>
                <td><?php echo htmlspecialchars($property->getDescription()); ?></td>
                <td><?php echo htmlspecialchars($property->getLocation()); ?></td>
                <td><?php echo htmlspecialchars($property->getRentAmount()); ?></td>
                <td><img src="data:image/jpeg;base64,<?php echo base64_encode($property->getPhoto()); ?>" width="100" height="100"></td> <!-- Display photo -->
                <td class="actions">
                    <a href="edit_property.php?property_id=<?php echo $property->getPropertyId(); ?>">Edit</a>
                    <a href="delete_property.php?property_id=<?php echo $property->getPropertyId(); ?>" onclick="return confirm('Are you sure you want to delete this property?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>
<a id="create" href="create_property.php">Add Property</a>
</body>
</html>
