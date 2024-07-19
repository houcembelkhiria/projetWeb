<?php
session_start();
require_once '../scripts/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Database connection
$database = new Database();
$pdo = $database->getConnection();

// Fetch rental data for the current user
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT r.property_id, r.start_date, r.end_date, p.title 
                       FROM rent r 
                       INNER JOIN properties p ON r.property_id = p.property_id 
                       WHERE r.tenant_id = :user_id 
                       ORDER BY r.start_date");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group rentals by property
$propertyRentals = [];
foreach ($rentals as $rental) {
    $propertyRentals[$rental['property_id']]['title'] = $rental['title'];
    if (!isset($propertyRentals[$rental['property_id']]['count'])) {
        $propertyRentals[$rental['property_id']]['count'] = 0;
    }
    $propertyRentals[$rental['property_id']]['count']++;
}

// Fetch properties owned by the current user and count of times rented
$stmt = $pdo->prepare("SELECT p.property_id, p.title, COUNT(r.rent_id) as rent_count 
                       FROM properties p 
                       LEFT JOIN rent r ON p.property_id = r.property_id 
                       WHERE p.landlord_id = :user_id 
                       GROUP BY p.property_id, p.title");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$ownedProperties = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/profile.css">
    <link rel="stylesheet" href="../style/navbar.css">
    <title>User Profile</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="navbar">
    <a href="welcome.php">Home</a>
    <a href="list_users.php">Manage users</a>
    <a href="list_properties.php">Manage properties</a>
    <a href="profile.php">Profile</a>
    <a class="logoutBtn" href="../scripts/logout.php">Logout</a>
</div>

<h1>Welcome, <?php echo ucfirst(htmlspecialchars($_SESSION['username'])); ?>! This is your dashboard</h1>
<div class="charts-container"> 
<div  class="chart-container" style="width: 80%; margin: auto;">
    <canvas id="rentalChart"></canvas>
</div>

<div class="chart-container" style="width: 80%; margin: 150px;">
    <canvas id="ownedPropertiesChart"></canvas>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var rentalCtx = document.getElementById('rentalChart').getContext('2d');
    var ownedCtx = document.getElementById('ownedPropertiesChart').getContext('2d');

    var rentalData = <?php echo json_encode($propertyRentals); ?>;
    var ownedData = <?php echo json_encode($ownedProperties); ?>;

    // Data for rental chart
    var rentalLabels = Object.values(rentalData).map(function(item) {
        return item.title;
    });
    var rentalCounts = Object.values(rentalData).map(function(item) {
        return item.count;
    });

    // Data for owned properties chart
    var ownedLabels = ownedData.map(function(item) {
        return item.title;
    });
    var ownedCounts = ownedData.map(function(item) {
        return item.rent_count;
    });

    // Rental Chart
    new Chart(rentalCtx, {
        type: 'bar',
        data: {
            labels: rentalLabels,
            datasets: [{
                label: 'Rented Properties',
                data: rentalCounts,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Owned Properties Chart
    new Chart(ownedCtx, {
        type: 'bar',
        data: {
            labels: ownedLabels,
            datasets: [{
                label: 'Owned Properties Rental',
                data: ownedCounts,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>

</body>
</html>
