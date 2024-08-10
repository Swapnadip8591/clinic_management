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
            </tr>
            <?php } ?>
        </table>
        <ul>
            <li><a href="<?php echo $dashboard_link; ?>">Back to Dashboard</a></li>
        </ul>
    </div>
</body>
</html>
