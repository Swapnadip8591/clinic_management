<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Visit</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script>
        function addMedicine() {
            var medicineList = document.getElementById('medicineList');
            var selectedMedicine = medicineList.options[medicineList.selectedIndex];
            var medicineName = selectedMedicine.text;
            var medicineId = selectedMedicine.value;
            var includeInReceipt = document.getElementById('includeInReceipt').checked;

            var prescriptionField = document.getElementById('prescription_details');
            if (prescriptionField.value) {
                prescriptionField.value += ', ';
            }
            prescriptionField.value += medicineName;

            var receiptField = document.getElementById('receipt_details');
            if (includeInReceipt) {
                if (receiptField.value) {
                    receiptField.value += ', ';
                }
                receiptField.value += medicineName + '=' + selectedMedicine.getAttribute('data-price');
            }

            var totalField = document.getElementById('total_amount');
            var currentTotal = parseFloat(totalField.value);
            if (includeInReceipt) {
                currentTotal += parseFloat(selectedMedicine.getAttribute('data-price'));
            }
            totalField.value = currentTotal.toFixed(2);
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Add Visit</h1>
        <form action="add_visit.php" method="post">
            <label for="patient_name">Patient Name</label>
            <input type="text" id="patient_name" name="patient_name" required>
            
            <label for="visit_date">Visit Date</label>
            <input type="date" id="visit_date" name="visit_date" required>

            <label for="disease_diagnosed">Disease Diagnosed</label>
            <input type="text" id="disease_diagnosed" name="disease_diagnosed" required>

            <label for="prescription_details">Prescription Details</label>
            <textarea id="prescription_details" name="prescription_details" class="full-width" readonly></textarea>

            <label for="medicineList">Select Medicine</label>
            <select id="medicineList">
                <?php
                $stmt = $conn->query("SELECT * FROM Medicines");
                while ($row = $stmt->fetch()) {
                    echo '<option value="' . $row['MedicineID'] . '" data-price="' . $row['Price'] . '">' . $row['MedicineName'] . '</option>';
                }
                ?>
            </select>
            <input type="checkbox" id="includeInReceipt"> Include in Receipt
            <button type="button" onclick="addMedicine()">Add Medicine</button>
            
            <label for="receipt_details">Receipt Details</label>
            <textarea id="receipt_details" name="receipt_details" class="full-width" readonly></textarea>
            
            <label for="total_amount">Total Amount</label>
            <input type="text" id="total_amount" name="total_amount" value="0.00" readonly>

            <input type="submit" value="Add Visit">

            <ul><li><a href="index.php">Go to Main Menu</a></li></ul>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $patient_name = $_POST['patient_name'];
            $visit_date = $_POST['visit_date'];
            $disease_diagnosed = $_POST['disease_diagnosed'];
            $prescription_details = $_POST['prescription_details'];
            $receipt_details = $_POST['receipt_details'];
            $total_amount = $_POST['total_amount'];

            // Get Patient ID by Name
            $stmt = $conn->prepare("SELECT PatientID FROM Patients WHERE PatientName = ?");
            $stmt->execute([$patient_name]);
            $patient = $stmt->fetch();
            if (!$patient) {
                echo "Patient not found!";
                exit;
            }
            $patient_id = $patient['PatientID'];

            // Check if the visit is within 5 days from the last visit
            $stmt = $conn->prepare("SELECT VisitDate FROM Visits WHERE PatientID = ? ORDER BY VisitDate DESC LIMIT 1");
            $stmt->execute([$patient_id]);
            $last_visit = $stmt->fetch();
            $fee = 100;
            if ($last_visit) {
                $last_visit_date = new DateTime($last_visit['VisitDate']);
                $current_visit_date = new DateTime($visit_date);
                $interval = $last_visit_date->diff($current_visit_date)->days;
                if ($interval <= 5) {
                    $fee = 0;
                }
            }

            // Add the visit
            $stmt = $conn->prepare("INSERT INTO Visits (PatientID, VisitDate, DiseaseDiagnosed, PrescriptionDetails, FeeCharged) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$patient_id, $visit_date, $disease_diagnosed, $prescription_details, $fee]);

            // Add the receipt
            $stmt = $conn->prepare("INSERT INTO Receipts (VisitID, ReceiptDetails, TotalAmount) VALUES (?, ?, ?)");
            $visit_id = $conn->lastInsertId();
            $total_amount += $fee;
            $stmt->execute([$visit_id, "Fee=$fee, $receipt_details", $total_amount]);

            echo "New visit added successfully!";
        }
        ?>
    </div>
</body>
</html>
