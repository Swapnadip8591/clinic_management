<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Patient</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Add Patient</h1>
        <form action="add_patient.php" method="post">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>
            
            <label for="age">Age</label>
            <input type="text" id="age" name="age" required>
            
            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            
            <label for="contact">Contact Number</label>
            <input type="text" id="contact" name="contact" required>
            
            <label for="email">Email</label>
            <input type="text" id="email" name="email" required>
            
            <label for="address">Address</label>
            <textarea id="address" name="address" class="full-width"></textarea>
            
            <label for="medical_history">Medical History</label>
            <textarea id="medical_history" name="medical_history" class="full-width"></textarea>
            
            <input type="submit" value="Add Patient">

            <ul><li><a href="index.php">Go to Main Menu</a></li></ul>
        </form>

        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $stmt = $conn->prepare("INSERT INTO patients (PatientName, Age, Gender, ContactNumber, Email, Address, MedicalHistory) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$_POST['name'], $_POST['age'], $_POST['gender'], $_POST['contact'], $_POST['email'], $_POST['address'], $_POST['medical_history']]);
                echo "<p>New patient added successfully!</p>";
            } catch (PDOException $e) {
                echo "<p>Error: " . $e->getMessage() . "</p>";
            }
        }
        ?>
    </div>
</body>
</html>
