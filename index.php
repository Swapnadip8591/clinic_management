<!DOCTYPE html>
<html>
<head>
    <title>Clinic Management System</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Clinic Management System</h1>
        <br>
        <div class="button-row">
            <ul>
                <li><a href="add_patient.php">Enter New Patient</a></li>
                <li><a href="add_medicine.php">Enter New Medicine</a></li>
                <li><a href="add_visit.php">Enter New Visit</a></li>
            </ul>
        </div>
        <div class="button-row">
            <ul>
                <li><a href="show_patient.php">Show Patient Details</a></li>
                <li><a href="show_medicine.php">Show Medicine List</a></li>
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
