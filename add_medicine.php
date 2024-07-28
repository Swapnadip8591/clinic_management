<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Medicine</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Add Medicine</h1>
        <form action="add_medicine.php" method="post">
            <label for="medicine_name">Medicine Name</label>
            <input type="text" id="medicine_name" name="medicine_name" required>
            
            <label for="manufacturing_date">Manufacturing Date</label>
            <input type="date" id="manufacturing_date" name="manufacturing_date" required>
            
            <label for="expiry_date">Expiry Date</label>
            <input type="date" id="expiry_date" name="expiry_date" required>
            
            <label for="company">Company</label>
            <input type="text" id="company" name="company" required>
            
            <label for="price">Price</label>
            <input type="text" id="price" name="price" required>
            
            <input type="submit" value="Add Medicine">

            <ul><li><a href="index.php">Go to Main Menu</a></li></ul>
        </form>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $stmt = $conn->prepare("INSERT INTO medicines (MedicineName, ManufacturingDate, ExpiryDate, Company, Price) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$_POST['medicine_name'], $_POST['manufacturing_date'], $_POST['expiry_date'], $_POST['company'], $_POST['price']]);
                echo "<p>New medicine added successfully!</p>";
            } catch (PDOException $e) {
                echo "<p>Error: " . $e->getMessage() . "</p>";
            }
        }
        ?>
    </div>
</body>
</html>
