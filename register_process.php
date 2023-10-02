<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "user_login");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert user data into the database
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<html><head><title>Registration Successful</title><link rel='stylesheet' type='text/css' href='styless.css'></head><body>";
        echo "<h2 class='success-message'>Registration successful. <a href='login.php'>Login</a></h2>";
        echo "</body></html>";
    } else {
        echo "<html><head><title>Registration Error</title><link rel='stylesheet' type='text/css' href='styles.css'></head><body>";
        echo "<h2 class='error-message'>Error: " . $sql . "<br>" . $conn->error . "</h2>";
        echo "</body></html>";
    }

    $conn->close();
}
?>
