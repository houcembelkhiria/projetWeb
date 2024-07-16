<?php
session_start();
require_once '../scripts/db.php';
require_once '../classes/Property.php'; // Include the Property class

// Database connection
$database = new Database();
$pdo = $database->getConnection();

// Fetch properties from database with associated landlord username
$user_id = $_SESSION['user_id']; // Assuming 'user_id' is the session variable storing the logged-in user's ID
$stmt = $pdo->prepare("SELECT p.property_id, p.landlord_id, p.title, p.description, p.location, p.rent_amount, p.amenities, p.photo, u.username as landlord_username, r.rent_id
                       FROM properties p 
                       INNER JOIN users u ON p.landlord_id = u.id
                       LEFT JOIN rent r ON p.property_id = r.property_id AND r.end_date >= CURDATE()
                       WHERE p.landlord_id <> :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/welcome.css">
    <link rel="stylesheet" href="../style/navbar.css">
    <link rel="stylesheet" href="../style/cards.css">
    <title>Welcome</title>
    <style>
        .card.rented {
            background-color: #f8d7da; /* Light red background for rented properties */
        }
        .rented-text {
            color: red;
            font-weight: bold;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById("rent-modal");
            var spans = document.getElementsByClassName("close");
            var links = document.getElementsByClassName("rent-link");

            Array.from(links).forEach(function(link) {
                link.onclick = function() {
                    var propertyId = this.getAttribute('data-property-id');
                    document.getElementById('property-id-input').value = propertyId;
                    modal.style.display = "block";
                }
            });

            Array.from(spans).forEach(function(span) {
                span.onclick = function() {
                    modal.style.display = "none";
                }
            });

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        });
    </script>
</head>
<body>
<div class="navbar">
    <a href="welcome.php">Home</a>
    <?php if ($_SESSION['username'] === 'houcem'): ?>
        <a href="list_users.php">Manage users</a>
    <?php endif; ?>
    <a href="list_properties.php">Manage properties</a>
    <a class="logoutBtn" href="../scripts/logout.php">Logout</a>
    <a href="Profile.php">Profile</a>           

</div>

<h1>Welcome, <?php echo ucfirst(htmlspecialchars($_SESSION['username'])); ?>!</h1>

<div class="card-container">
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
        $isRented = !is_null($propertyData['rent_id']);
        ?>
        <div class="card <?php echo $isRented ? 'rented' : ''; ?>">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($property->getPhoto()); ?>" alt="Property Photo">
            <div class="card-content">
                <h3><?php echo htmlspecialchars($property->getTitle()); ?></h3>
                <p><?php echo htmlspecialchars($property->getDescription()); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($property->getLocation()); ?></p>
                <p><strong>Rent Amount:</strong> <?php echo htmlspecialchars($property->getRentAmount()); ?>/month</p>
                <p><strong>Landlord:</strong> <?php echo htmlspecialchars($propertyData['landlord_username']); ?></p>
                <?php if (!$isRented): ?>
                    <button class="rent-link" data-property-id="<?php echo $propertyData['property_id']; ?>">Rent</button>
                <?php else: ?>
                    <p class="rented-text">Already Rented</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal Dialog -->
<div id="rent-modal" class="modal">
    <div id="modal-content">
        <span class="close">&times;</span>
        <h2>Rent Property</h2>
        <form action="../scripts/process_rent.php" method="POST">
            <input type="hidden" id="property-id-input" name="property_id">
            <input type="hidden" name="tenant_id" value="<?php echo $_SESSION['user_id']; ?>">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>
            <br>
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required>
            <br>
            <input type="submit" value="Submit">
        </form>
    </div>
</div>

</body>
</html>
