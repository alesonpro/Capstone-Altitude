<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "user_login");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve user data from the database
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION["username"] = $username;
            header("Location: dashboard.php"); // Redirect to the dashboard
        } else {
            echo "<html><head><title>Login Error</title><link rel='stylesheet' type='text/css' href='styles.css'></head><body>";
            echo "<div class='login-container'>";
            echo "<h2 class='error-message'>Incorrect password. <a href='login.php'>Try Again</a></h2>";
            echo "</div>";
            echo "</body></html>";
        }
    } else {
        echo "<html><head><title>Login Error</title><link rel='stylesheet' type='text/css' href='styless.css'></head><body>";
        echo "<div class='login-container'>";
        echo "<h2 class='error-message'>User not found. <a href='register.php'>Register</a></h2>";
        echo "</div>";
        echo "</body></html>";
    }

    $conn->close();
}
?>
