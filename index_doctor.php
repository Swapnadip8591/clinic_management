<?php
session_start();

// Check if the user is logged in and is a doctor
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'doctor') {
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Clinic Management System</h1>
        <h2>Welcome, Dr. <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        <br>
        <div class="button-row">
            <ul>
                <li><a href="add_medicine.php">Enter New Medicine</a></li>
                <li><a href="show_medicine.php">Show Medicine List</a></li>
                <li><a href="add_visit.php">Enter New Visit</a></li>
            </ul>
        </div>
        <div class="button-row">
            <ul>
                <li><a href="show_patient.php">Show Patient Details</a></li>
                <li><a href="delete.php" class="button">Delete Data</a></li>
                <li><a href="show_visit.php">Show Visit Details</a></li>
            </ul>
        </div>
        <br>
        <div class="logout-form">
            <form action="logout.php" method="post">
                <input type="submit" value="Logout">
            </form>
        </div>
    </div>
</body>
</html>
