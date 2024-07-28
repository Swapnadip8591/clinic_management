<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Show Visit Details</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Visit Details</h1>
        <table>
            <tr>
                <th>Visit ID</th>
                <th>Patient Name</th>
                <th style="width: 90px;">Visit Date</th>
                <th>Disease Diagnosed</th>
                <th>Prescription Details</th>
                <th>Receipt Details</th>
                <th>Total Amount</th>
            </tr>
            <?php
            $stmt = $conn->query("SELECT visits.VisitID, patients.PatientName, visits.VisitDate, visits.DiseaseDiagnosed, visits.PrescriptionDetails, receipts.ReceiptDetails, receipts.TotalAmount 
                                  FROM visits
                                  JOIN patients ON visits.PatientID = patients.PatientID
                                  JOIN receipts ON visits.VisitID = receipts.VisitID");
            while ($row = $stmt->fetch()) {
                echo '<tr>';
                echo '<td>' . $row['VisitID'] . '</td>';
                echo '<td>' . $row['PatientName'] . '</td>';
                echo '<td>' . $row['VisitDate'] . '</td>';
                echo '<td>' . $row['DiseaseDiagnosed'] . '</td>';
                echo '<td>' . $row['PrescriptionDetails'] . '</td>';
                echo '<td>' . $row['ReceiptDetails'] . '</td>';
                echo '<td>' . $row['TotalAmount'] . '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</body>
</html>
