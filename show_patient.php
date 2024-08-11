<?php
include 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Determine the correct dashboard link based on user role
$dashboard_link = ($_SESSION['role'] === 'doctor') ? 'index_doctor.php' : 'index_assistant.php';
$stmt = $conn->query("SELECT * FROM patients");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Show Patient Details</title>
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
        <h2>Patient Details</h2>
        <table>
            <tr>
                <th>Patient ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Contact Number</th>
                <th>Address</th>
                <th>Email</th>
                <th>Medical History</th>
                <th>Update</th>
            </tr>
            <?php while ($row = $stmt->fetch()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['PatientID']); ?></td>
                <td><?php echo htmlspecialchars($row['PatientName']); ?></td>
                <td><?php echo htmlspecialchars($row['Age']); ?></td>
                <td><?php echo htmlspecialchars($row['Gender']); ?></td>
                <td><?php echo htmlspecialchars($row['ContactNumber']); ?></td>
                <td><?php echo htmlspecialchars($row['Address']); ?></td>
                <td><?php echo htmlspecialchars($row['Email']); ?></td>
                <td><?php echo htmlspecialchars($row['MedicalHistory']); ?></td>
                <td><a href="update_patient.php?id=<?php echo $row['PatientID']; ?>" class="edit-btn">Edit</a></td>
            </tr>
            <?php } ?>
        </table>
        <ul>
            <li><a href="<?php echo $dashboard_link; ?>">Back to Dashboard</a></li>
        </ul>
    </div>
</body>
</html>