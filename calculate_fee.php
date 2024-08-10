<?php
include 'db.php';

function calculateFee($conn, $patient_id, $visit_date) {
    $stmt = $conn->prepare("SELECT VisitDate FROM Visits WHERE PatientID = ? AND VisitDate < ? ORDER BY VisitDate DESC LIMIT 1");
    $stmt->execute([$patient_id, $visit_date]);
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
    return $fee;
}

if (isset($_GET['patient_id']) && isset($_GET['visit_date'])) {
    $patient_id = $_GET['patient_id'];
    $visit_date = $_GET['visit_date'];
    $fee = calculateFee($conn, $patient_id, $visit_date);
    echo $fee;
} else {
    echo "Error: Missing parameters";
}