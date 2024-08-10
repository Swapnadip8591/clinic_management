<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        try {
            $stmt = $conn->prepare("INSERT INTO Users (Username, Password, Role) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hashed_password, $role]);
            
            session_start();
            $_SESSION['user_id'] = $conn->lastInsertId();
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            if ($role == 'doctor') {
                $_SESSION['doctor_id'] = $_SESSION['user_id'];
                header("Location: index_doctor.php");
            } else {
                header("Location: index_assistant.php");
            }
            exit();
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="styles_login_register.css">
    <style>
        .role-selection {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .role-option {
            display: flex;
            align-items: center;
        }
        .role-option input[type="radio"] {
            margin-right: 5px;
        }
        .role-option label {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <?php if (isset($error)) { echo '<p class="error-message">' . $error . '</p>'; } ?>
        <form method="post" action="register.php">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <div class="role-selection">
                <div class="role-option">
                    <input type="radio" id="doctor" name="role" value="doctor" required>
                    <label for="doctor">Doctor</label>
                </div>
                <div class="role-option">
                    <input type="radio" id="assistant" name="role" value="assistant" required>
                    <label for="assistant">Assistant</label>
                </div>
            </div>

            <input type="submit" value="Register">
        </form>
        <a href="login.php" class="register-link">Login</a>
    </div>
</body>
</html>