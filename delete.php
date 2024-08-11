<?php
include 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$user_role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Determine the correct dashboard link based on user role
$dashboard_link = ($user_role === 'doctor') ? 'index_doctor.php' : 'index_assistant.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deleteType = $_POST['deleteType'];
    $deleteId = $_POST['deleteId'];

    // Check if assistant is trying to delete medicine
    if ($user_role === 'assistant' && $deleteType === 'medicine') {
        echo "Assistants are not authorized to delete medicines.";
    } else {
        switch ($deleteType) {
            case 'patient':
                // Delete patient and related visits
                $stmt = $conn->prepare("DELETE FROM receipts WHERE visitID IN (SELECT visitID FROM visits WHERE patientID = ?)");
                $stmt->execute([$deleteId]);
                $stmt = $conn->prepare("DELETE FROM visits WHERE patientID = ?");
                $stmt->execute([$deleteId]);
                $stmt = $conn->prepare("DELETE FROM patients WHERE patientID = ?");
                $stmt->execute([$deleteId]);
                echo "Patient and related visits deleted successfully.";
                break;

            case 'medicine':
                // Only doctors can delete medicines
                $stmt = $conn->prepare("DELETE FROM medicines WHERE medicineID = ?");
                $stmt->execute([$deleteId]);
                echo "Medicine deleted successfully.";
                break;

            case 'visit':
                // Delete visit
                if ($user_role === 'doctor') {
                    // Check if the visit belongs to the logged-in doctor
                    $stmt = $conn->prepare("SELECT COUNT(*) FROM visits WHERE visitID = ? AND UserID = ?");
                    $stmt->execute([$deleteId, $user_id]);
                    $count = $stmt->fetchColumn();
                    if ($count == 0) {
                        echo "You are not authorized to delete this visit.";
                        break;
                    }
                }
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
}

// Fetch data for dropdowns
$patients = $conn->query("SELECT PatientID, PatientName FROM Patients");

// Only fetch medicines if the user is a doctor
$medicines = ($user_role === 'doctor') ? $conn->query("SELECT MedicineID, MedicineName FROM Medicines") : null;
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
                <?php if ($user_role === 'doctor'): ?>
                case 'medicine':
                    <?php 
                    $stmt = $conn->query("SELECT MedicineID, MedicineName FROM Medicines");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
                    ?>
                        optionsHtml += `<option value="<?php echo $row['MedicineID']; ?>"><?php echo htmlspecialchars($row['MedicineName']); ?></option>`;
                    <?php endwhile; ?>
                    break;
                <?php endif; ?>
                case 'visit':
                    <?php 
                    if ($user_role === 'doctor') {
                        $stmt = $conn->prepare("SELECT v.VisitID, p.PatientName, v.VisitDate 
                                                FROM Visits v 
                                                JOIN Patients p ON v.PatientID = p.PatientID 
                                                WHERE v.UserID = ?");
                        $stmt->execute([$user_id]);
                    } else {
                        $stmt = $conn->query("SELECT v.VisitID, p.PatientName, v.VisitDate 
                                              FROM Visits v 
                                              JOIN Patients p ON v.PatientID = p.PatientID");
                    }
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
                    ?>
                        optionsHtml += `<option value="<?php echo $row['VisitID']; ?>"><?php echo htmlspecialchars($row['PatientName']); ?>, Date: <?php echo $row['VisitDate']; ?></option>`;
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
                <?php if ($user_role === 'doctor'): ?>
                <option value="medicine">Medicine</option>
                <?php endif; ?>
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
        <ul>
            <li><a href="<?php echo $dashboard_link; ?>">Back to Dashboard</a></li>
        </ul>
    </div>
</body>
</html>