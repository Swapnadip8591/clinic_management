<?php
session_start();
include 'db.php';

// Check if user is logged in and is an assistant
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'assistant') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Show All Visit Details</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>All Visit Details</h1>
        <table>
            <tr>
                <th>Patient Name</th>
                <th>Doctor Name</th>
                <th style="width: 90px;">Visit Date</th>
                <th>Disease Diagnosed</th>
                <th>Prescription Details</th>
                <th>Receipt Details</th>
                <th>Total Amount</th>
            </tr>
            <?php
            $stmt = $conn->query("SELECT visits.VisitID, patients.PatientName, users.Username as DoctorName, visits.VisitDate, visits.DiseaseDiagnosed, visits.PrescriptionDetails, receipts.ReceiptDetails, receipts.TotalAmount 
                                  FROM visits
                                  JOIN patients ON visits.PatientID = patients.PatientID
                                  JOIN users ON visits.UserID = users.UserID
                                  JOIN receipts ON visits.VisitID = receipts.VisitID
                                  ORDER BY visits.VisitDate DESC");
            
            while ($row = $stmt->fetch()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['PatientName']) . '</td>';
                echo '<td>' . htmlspecialchars($row['DoctorName']) . '</td>';
                echo '<td>' . htmlspecialchars($row['VisitDate']) . '</td>';
                echo '<td>' . htmlspecialchars($row['DiseaseDiagnosed']) . '</td>';
                echo '<td>' . htmlspecialchars($row['PrescriptionDetails']) . '</td>';
                echo '<td>' . htmlspecialchars($row['ReceiptDetails']) . '</td>';
                echo '<td>' . htmlspecialchars($row['TotalAmount']) . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
        <br>
        <ul><li><a href="index_assistant.php">Back to Dashboard</a></li></ul>
    </div>
</body>
</html>