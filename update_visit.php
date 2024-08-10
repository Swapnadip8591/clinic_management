<?php
session_start();
include 'db.php';

// Check if user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];
$visit_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$visit_id) {
    echo "No visit ID provided.";
    exit();
}

// Fetch the visit data and receipt data
$stmt = $conn->prepare("SELECT v.*, p.PatientName, r.ReceiptDetails, r.TotalAmount FROM visits v 
                        JOIN patients p ON v.PatientID = p.PatientID 
                        LEFT JOIN receipts r ON v.VisitID = r.VisitID 
                        WHERE v.VisitID = ? AND v.UserID = ?");
$stmt->execute([$visit_id, $doctor_id]);
$visit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$visit) {
    echo "Visit not found or you don't have permission to edit it.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visit_date = $_POST['visit_date'];
    $disease_diagnosed = $_POST['disease_diagnosed'];
    $prescription_details = $_POST['prescription_details'];
    $fee_charged = $_POST['fee_charged'];
    $receipt_details = $_POST['receipt_details'];
    $total_amount = $_POST['total_amount'];

    // Update the visit
    $stmt = $conn->prepare("UPDATE visits SET VisitDate = ?, DiseaseDiagnosed = ?, PrescriptionDetails = ?, FeeCharged = ? WHERE VisitID = ?");
    $stmt->execute([$visit_date, $disease_diagnosed, $prescription_details, $fee_charged, $visit_id]);

    // Update the receipt
    $stmt = $conn->prepare("UPDATE receipts SET ReceiptDetails = ?, TotalAmount = ? WHERE VisitID = ?");
    $stmt->execute([$receipt_details, $total_amount, $visit_id]);

    echo "Visit updated successfully!";
    header("Location: show_visit.php");
    exit();
}

// Fetch all medicines for the dropdown
$medicines = $conn->query("SELECT * FROM Medicines")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Visit</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        /* ... (keep the existing styles) ... */
    </style>
    <script>
        function addMedicine() {
            var medicineList = document.getElementById('medicineList');
            var selectedMedicine = medicineList.options[medicineList.selectedIndex];
            var medicineName = selectedMedicine.text;
            var medicinePrice = parseFloat(selectedMedicine.getAttribute('data-price'));
            var includeInReceipt = document.getElementById('includeInReceipt').checked;

            var prescriptionField = document.getElementById('prescription_details');
            if (prescriptionField.value) prescriptionField.value += ', ';
            prescriptionField.value += medicineName;

            if (includeInReceipt) {
                var receiptField = document.getElementById('receipt_details');
                if (receiptField.value) receiptField.value += ', ';
                receiptField.value += medicineName + '=' + medicinePrice.toFixed(2);

                var totalAmountField = document.getElementById('total_amount');
                var currentTotal = parseFloat(totalAmountField.value);
                totalAmountField.value = (currentTotal + medicinePrice).toFixed(2);
            }

            updateTotalAmount();
        }

        function updateTotalAmount() {
            var feeCharged = parseFloat(document.getElementById('fee_charged').value) || 0;
            var totalAmount = parseFloat(document.getElementById('total_amount').value) || 0;
            document.getElementById('total_amount').value = (feeCharged + totalAmount).toFixed(2);
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Update Visit</h1>
        <form method="post">
            <label for="patient_name">Patient Name</label>
            <input type="text" id="patient_name" name="patient_name" value="<?php echo htmlspecialchars($visit['PatientName']); ?>" readonly>
            
            <label for="visit_date">Visit Date</label>
            <input type="date" id="visit_date" name="visit_date" value="<?php echo $visit['VisitDate']; ?>" required readonly>

            <label for="disease_diagnosed">Disease Diagnosed</label>
            <input type="text" id="disease_diagnosed" name="disease_diagnosed" value="<?php echo htmlspecialchars($visit['DiseaseDiagnosed']); ?>" required>

            <label for="prescription_details">Prescription Details</label>
            <textarea id="prescription_details" name="prescription_details" required><?php echo htmlspecialchars($visit['PrescriptionDetails']); ?></textarea>

            <label for="medicineList">Add Medicine</label>
            <select id="medicineList">
                <?php foreach ($medicines as $medicine): ?>
                    <option value="<?php echo $medicine['MedicineID']; ?>" data-price="<?php echo $medicine['Price']; ?>">
                        <?php echo htmlspecialchars($medicine['MedicineName']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="checkbox" id="includeInReceipt"> Include in Receipt
            <button type="button" onclick="addMedicine()">Add Medicine</button>

            <label for="receipt_details">Receipt Details</label>
            <textarea id="receipt_details" name="receipt_details"><?php echo htmlspecialchars($visit['ReceiptDetails']); ?></textarea>

            <label for="fee_charged">Fee Charged</label>
            <input type="number" id="fee_charged" name="fee_charged" value="<?php echo $visit['FeeCharged']; ?>"readonly onchange="updateTotalAmount()" required>

            <label for="total_amount">Total Amount</label>
            <input type="number" id="total_amount" name="total_amount" value="<?php echo $visit['TotalAmount']; ?>" readonly>

            <button type="submit">Update Visit</button>
        </form>
        <ul><li><a href="show_visit.php">Back to Visit List</a></li></ul>
    </div>
</body>
</html>