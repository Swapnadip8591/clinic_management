<?php
session_start();
include 'db.php';

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Show Visit Details</title>
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
        <h1>Your Visit Details</h1>
        <table>
            <tr>
                <th>Patient Name</th>
                <th>Visit Date</th>
                <th>Disease Diagnosed</th>
                <th>Prescription Details</th>
                <th>Receipt Details</th>
                <th>Total Amount</th>
                <th>Action</th>
            </tr>
            <?php
            $stmt = $conn->prepare("SELECT visits.VisitID, patients.PatientName, visits.VisitDate, visits.DiseaseDiagnosed, visits.PrescriptionDetails, receipts.ReceiptDetails, receipts.TotalAmount 
                                    FROM visits
                                    JOIN patients ON visits.PatientID = patients.PatientID
                                    JOIN receipts ON visits.VisitID = receipts.VisitID
                                    WHERE visits.UserID = ?
                                    ORDER BY visits.VisitDate DESC");
            $stmt->execute([$doctor_id]);
            
            while ($row = $stmt->fetch()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['PatientName']) . '</td>';
                echo '<td>' . htmlspecialchars($row['VisitDate']) . '</td>';
                echo '<td>' . htmlspecialchars($row['DiseaseDiagnosed']) . '</td>';
                echo '<td>' . htmlspecialchars($row['PrescriptionDetails']) . '</td>';
                echo '<td>' . htmlspecialchars($row['ReceiptDetails']) . '</td>';
                echo '<td>' . htmlspecialchars($row['TotalAmount']) . '</td>';
                echo '<td><a href="update_visit.php?id=' . $row['VisitID'] . '" class="edit-btn">Edit</a></td>';
                echo '</tr>';
            }
            ?>
        </table>
        <br>
        <ul><li><a href="index_doctor.php">Back to Dashboard</a></li></ul>
    </div>
</body>
</html>