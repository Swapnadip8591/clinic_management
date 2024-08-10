<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT UserID, Username, Password, Role FROM Users WHERE Username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['user_id'] = $user['UserID'];
        $_SESSION['username'] = $user['Username'];
        $_SESSION['role'] = $user['Role'];

        if ($user['Role'] == 'doctor') {
            $_SESSION['doctor_id'] = $user['UserID'];
            header("Location: index_doctor.php");
        } else if ($user['Role'] == 'assistant') {
            header("Location: index_assistant.php");
        }
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
        <h1>Login</h1>
        <?php if (isset($error)) { echo '<p class="error-message">' . $error . '</p>'; } ?>
        <form method="post" action="login.php">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

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

            <input type="submit" value="Login">
        </form>
        <a href="register.php" class="register-link">Register</a>
    </div>
</body>
</html>