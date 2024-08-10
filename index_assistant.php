<!DOCTYPE html>
<html>
<head>
    <title>Assistant Dashboard</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php
    session_start();
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'assistant') {
        header("Location: login.php");
        exit();
    }
    ?>
    <div class="container">
        <h1>Clinic Management System</h1>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        <br>
        <div class="button-row">
            <ul>
                <li><a href="add_patient.php">Enter New Patient</a></li>
                <li><a href="show_patient.php">Show Patient Details</a></li>
            </ul>
        </div>
        <div class="button-row">
            <ul>
                <li><a href="show_all_visit.php">Show All Visit Details</a></li>
                <li><a href="delete.php" class="button">Delete Data</a></li>
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