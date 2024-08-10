<?php
include 'db.php';

$stmt = $conn->query("SELECT * FROM medicines");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Show Medicine List</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Medicine List</h2>
        <table>
            <tr>
                <th>Medicine ID</th>
                <th>Medicine Name</th>
                <th>Manufacturing Date</th>
                <th>Expiry Date</th>
                <th>Company</th>
                <th>Price</th>
            </tr>
            <?php while ($row = $stmt->fetch()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['MedicineID']); ?></td>
                <td><?php echo htmlspecialchars($row['MedicineName']); ?></td>
                <td><?php echo htmlspecialchars($row['ManufacturingDate']); ?></td>
                <td><?php echo htmlspecialchars($row['ExpiryDate']); ?></td>
                <td><?php echo htmlspecialchars($row['Company']); ?></td>
                <td><?php echo htmlspecialchars($row['Price']); ?></td>
            </tr>
            <?php } ?>
        </table>
        <ul><li><a href="index_doctor.php">Back to Dashboard</a></li></ul>
    </div>
</body>
</html>
