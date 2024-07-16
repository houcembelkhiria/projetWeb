<!-- create_property.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Property</title>
    <link rel="stylesheet" href="../style/users.css">
    <link rel="stylesheet" href="../style/navbar.css">
</head>
<body>

<div class="navbar">
                    <a href="welcome.php">Home</a>
                    <a href="list_users.php">Manage users</a>    
                    <a href="list_properties.php">Manage properties</a>           
                    <a href="Profile.php">Profile</a>           

       
            </div>

    <div class="form-container">

    <form action="../scripts/create_property_process.php" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br><br>
        
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" cols="50"></textarea><br><br>
        
        <label for="location">Location:</label>
        <input type="text" id="location" name="location"><br><br>
        
        <label for="rent_amount">Rent Amount:</label>
        <input type="text" id="rent_amount" name="rent_amount" required><br><br>
        

        
        <label for="photo">Photo:</label>
        <input type="file" id="photo" name="photo" accept="image/*"><br><br>
        <div class="buttons">
        <button type="submit" >Add Property</button>
        <button type="button" onclick="window.location.href = 'list_properties.php';" id="cancel">Cancel</button>
        </div>
    </form>
</div>
</body>
</html>
