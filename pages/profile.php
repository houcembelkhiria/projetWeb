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
$stmt = $pdo->prepare("SELECT property_id, start_date, end_date FROM rent WHERE tenant_id = :user_id ORDER BY start_date");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/profile.css">

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
                    <a href="Profile.php">Profile</a>           
          
            </div>

<h1>Welcome, <?php echo ucfirst(htmlspecialchars($_SESSION['username'])); ?> this is your dashboard</h1>

<div class="chart-container" style="width: 40%; margin: auto;">
    <canvas id="rentalChart"></canvas>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('rentalChart').getContext('2d');

    var rentalData = <?php echo json_encode($rentals); ?>;

    var dates = rentalData.map(function(rental) {
        return rental.start_date;
    });

    var counts = {};
    dates.forEach(function(date) {
        counts[date] = (counts[date] || 0) + 1;
    });

    var chartLabels = Object.keys(counts);
    var chartData = Object.values(counts);

    var rentalChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Rented Properties',
                data: chartData,
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
});
</script>

</body>
</html>
