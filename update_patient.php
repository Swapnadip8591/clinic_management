<?php
include 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$dashboard_link = ($_SESSION['role'] === 'doctor') ? 'index_doctor.php' : 'index_assistant.php';

if (!isset($_GET['id'])) {
    echo "No patient ID provided.";
    exit();
}

$patient_id = $_GET['id'];

// Fetch patient data
$stmt = $conn->prepare("SELECT * FROM patients WHERE PatientID = ?");
$stmt->execute([$patient_id]);
$patient = $stmt->fetch();

if (!$patient) {
    echo "Patient not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $medical_history = $_POST['medical_history'];

    $stmt = $conn->prepare("UPDATE patients SET Age = ?, Gender = ?, ContactNumber = ?, Email = ?, Address = ?, MedicalHistory = ? WHERE PatientID = ?");
    $stmt->execute([$age, $gender, $contact, $email, $address, $medical_history, $patient_id]);

    echo "Patient information updated successfully.";
    header("Location: show_patient.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Patient</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Update Patient</h1>
        <form action="update_patient.php?id=<?php echo $patient_id; ?>" method="post">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($patient['PatientName']); ?>" readonly>
            
            <label for="age">Age</label>
            <input type="text" id="age" name="age" value="<?php echo htmlspecialchars($patient['Age']); ?>" required>
            
            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="Male" <?php if($patient['Gender'] == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if($patient['Gender'] == 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other" <?php if($patient['Gender'] == 'Other') echo 'selected'; ?>>Other</option>
            </select>
            
            <label for="contact">Contact Number</label>
            <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($patient['ContactNumber']); ?>" required>
            
            <label for="email">Email</label>
            <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($patient['Email']); ?>" required>
            
            <label for="address">Address</label>
            <textarea id="address" name="address" class="full-width"><?php echo htmlspecialchars($patient['Address']); ?></textarea>
            
            <label for="medical_history">Medical History</label>
            <textarea id="medical_history" name="medical_history" class="full-width"><?php echo htmlspecialchars($patient['MedicalHistory']); ?></textarea>
            
            <input type="submit" value="Update Patient">
            
            <ul><li><a href="show_patient.php">Back to Patient List</a></li></ul>
        </form>
    </div>
</body>
</html>