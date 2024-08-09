<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deleteType = $_POST['deleteType'];
    $deleteId = $_POST['deleteId'];

    switch ($deleteType) {
        case 'patient':
            // Delete patient and related visits
            $stmt = $conn->prepare("DELETE FROM visits WHERE patientID = ?");
            $stmt->execute([$deleteId]);
            $stmt = $conn->prepare("DELETE FROM patients WHERE patientID = ?");
            $stmt->execute([$deleteId]);
            echo "Patient and related visits deleted successfully.";
            break;

        case 'medicine':
            // Delete medicine
            $stmt = $conn->prepare("DELETE FROM medicines WHERE medicineID = ?");
            $stmt->execute([$deleteId]);
            echo "Medicine deleted successfully.";
            break;

        case 'visit':
            // Delete visit
            $stmt = $conn->prepare("DELETE FROM receipts WHERE visitID = ?");
            $stmt->execute([$deleteId]);
            $stmt = $conn->prepare("DELETE FROM visits WHERE visitID = ?");
            $stmt->execute([$deleteId]);
            echo "Visit deleted successfully.";
            break;

        default:
            echo "Invalid delete type.";
            break;
    }
}

// Fetch data for dropdowns
$patients = $conn->query("SELECT PatientID, PatientName FROM Patients");
$medicines = $conn->query("SELECT MedicineID, MedicineName FROM Medicines");
$visits = $conn->query("SELECT VisitID, PatientID, VisitDate FROM Visits");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Data</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function updateDeleteOptions() {
            const deleteType = document.getElementById('deleteType').value;
            const deleteOptions = document.getElementById('deleteOptions');
            let optionsHtml = '';

            switch (deleteType) {
                case 'patient':
                    <?php 
                    $stmt = $conn->query("SELECT PatientID, PatientName FROM Patients");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
                    ?>
                        optionsHtml += `<option value="<?php echo $row['PatientID']; ?>"><?php echo htmlspecialchars($row['PatientName']); ?></option>`;
                    <?php endwhile; ?>
                    break;
                case 'medicine':
                    <?php 
                    $stmt = $conn->query("SELECT MedicineID, MedicineName FROM Medicines");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
                    ?>
                        optionsHtml += `<option value="<?php echo $row['MedicineID']; ?>"><?php echo htmlspecialchars($row['MedicineName']); ?></option>`;
                    <?php endwhile; ?>
                    break;
                case 'visit':
                    <?php 
                    $stmt = $conn->query("SELECT VisitID, PatientID, VisitDate FROM Visits");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
                    ?>
                        optionsHtml += `<option value="<?php echo $row['VisitID']; ?>">PatientID: <?php echo $row['PatientID']; ?>, Date: <?php echo $row['VisitDate']; ?></option>`;
                    <?php endwhile; ?>
                    break;
                default:
                    optionsHtml = '<option>Select type first</option>';
                    break;
            }

            deleteOptions.innerHTML = optionsHtml;
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Delete Data</h2>
        <form method="POST" action="delete.php" class="form-container">
            <label for="deleteType">Select Type to Delete:</label>
            <select id="deleteType" name="deleteType" onchange="updateDeleteOptions()">
                <option value="">--Select Type--</option>
                <option value="patient">Patient</option>
                <option value="medicine">Medicine</option>
                <option value="visit">Visit</option>
            </select>
            <br>
            <label for="deleteOptions">Select Item to Delete:</label>
            <select id="deleteOptions" name="deleteId">
                <option value="">--Select Item--</option>
            </select>
            <br>
            <button type="submit">Delete Data</button>
        </form>
    </div>
</body>
</html>