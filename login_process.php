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
            exit; // Ensure no further code is executed after the redirect
        } else {
            echo "Incorrect password. <a href='login.php'>Try Again</a>";
        }
    } else {
        echo "User not found. <a href='register.php'>Register</a>";
    }

    $conn->close();
}
?>
