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
    echo "No medicine ID provided.";
    exit();
}

$medicine_id = $_GET['id'];

// Fetch medicine data
$stmt = $conn->prepare("SELECT * FROM medicines WHERE MedicineID = ?");
$stmt->execute([$medicine_id]);
$medicine = $stmt->fetch();

if (!$medicine) {
    echo "Medicine not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manufacturing_date = $_POST['manufacturing_date'];
    $expiry_date = $_POST['expiry_date'];
    $company = $_POST['company'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("UPDATE medicines SET ManufacturingDate = ?, ExpiryDate = ?, Company = ?, Price = ? WHERE MedicineID = ?");
    $stmt->execute([$manufacturing_date, $expiry_date, $company, $price, $medicine_id]);

    echo "Medicine information updated successfully.";
    header("Location: show_medicine.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Medicine</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Update Medicine</h1>
        <form action="update_medicine.php?id=<?php echo $medicine_id; ?>" method="post">
            <label for="medicine_name">Medicine Name</label>
            <input type="text" id="medicine_name" name="medicine_name" value="<?php echo htmlspecialchars($medicine['MedicineName']); ?>" readonly>
            
            <label for="manufacturing_date">Manufacturing Date</label>
            <input type="date" id="manufacturing_date" name="manufacturing_date" value="<?php echo htmlspecialchars($medicine['ManufacturingDate']); ?>" required>
            
            <label for="expiry_date">Expiry Date</label>
            <input type="date" id="expiry_date" name="expiry_date" value="<?php echo htmlspecialchars($medicine['ExpiryDate']); ?>" required>
            
            <label for="company">Company</label>
            <input type="text" id="company" name="company" value="<?php echo htmlspecialchars($medicine['Company']); ?>" required>
            
            <label for="price">Price</label>
            <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($medicine['Price']); ?>" required>
            
            <input type="submit" value="Update Medicine">
            
            <ul><li><a href="show_medicine.php">Back to Medicine List</a></li></ul>
        </form>
    </div>
</body>
</html>