<?php
include 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$dashboard_link = ($_SESSION['role'] === 'doctor') ? 'index_doctor.php' : 'index_assistant.php';
$stmt = $conn->query("SELECT * FROM medicines");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Show Medicine List</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .edit-btn { 
            background-color: #4CAF50; 
            color: white; 
            padding: 5px 10px; 
            text-decoration: none; 
            display: inline-block; 
            border-radius: 3px; 
        }
        .edit-btn:hover { background-color: #45a049; }
    </style>
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
                <th>Update</th>
            </tr>
            <?php while ($row = $stmt->fetch()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['MedicineID']); ?></td>
                <td><?php echo htmlspecialchars($row['MedicineName']); ?></td>
                <td><?php echo htmlspecialchars($row['ManufacturingDate']); ?></td>
                <td><?php echo htmlspecialchars($row['ExpiryDate']); ?></td>
                <td><?php echo htmlspecialchars($row['Company']); ?></td>
                <td><?php echo htmlspecialchars($row['Price']); ?></td>
                <td><a href="update_medicine.php?id=<?php echo $row['MedicineID']; ?>" class="edit-btn">Edit</a></td>
            </tr>
            <?php } ?>
        </table>
        <ul>
            <li><a href="<?php echo $dashboard_link; ?>">Back to Dashboard</a></li>
        </ul>
    </div>
</body>
</html>